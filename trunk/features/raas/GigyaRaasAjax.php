<?php

/**
 * @file
 * GigyaRaasAjax.php
 * An AJAX handler for login or register user to WP.
 */
class GigyaRaasAjax {

	private $gigya_account;

	public function __construct() {

		// Get settings variables.
		$this->global_options = get_option( GIGYA__SETTINGS_GLOBAL );
		$this->login_options  = get_option( GIGYA__SETTINGS_LOGIN );
	}

	/**
	 * This is Gigya login AJAX callback
	 */
	public function init() {

		// Get the data from the client (AJAX).
		$data = $_POST['data'];

		// Trap for login users
		if ( is_user_logged_in() ) {
			$prm = array( 'msg' => __( 'There already a logged in user' ) );
			wp_send_json_error( $prm );
		}

		// Check Gigya's signature validation.
		$is_sig_validate = SigUtils::validateUserSignature(
				$data['UID'],
				$data['signatureTimestamp'],
				GIGYA__API_SECRET,
				$data['UIDSignature']
		);

		// Gigya user validate trap.
		if ( empty( $is_sig_validate ) ) {
			$prm = array( 'msg' => __( 'There a problem to validate your user' ) );
			wp_send_json_error( $prm );
		}

		// Initialize Gigya account.
		$gigyaCMS            = new GigyaCMS();
		$this->gigya_account = $gigyaCMS->getAccount( $data['UID'] );

		// Check if there already WP user with the same email.
		$wp_user = get_user_by( 'email', $this->gigya_account['profile']['email'] );
		if ( ! empty( $wp_user ) ) {

			$primary_user = $gigyaCMS->isPrimaryUser( $this->gigya_account['loginIDs']['emails'], $wp_user->data->user_email );

			// If this user is not the primary user account in Gigya
			// we delete the account (we don't want two different users with the same email)
			if ( empty( $primary_user ) ) {

				$gigyaCMS->deleteAccountByGUID( $this->gigya_account['UID'] );

				$providers = $gigyaCMS->getProviders( $this->gigya_account );

				$msg = sprintf( __( 'We found your email in our system.<br>Please login to your existing account using your <strong>%1$s</strong> identity.' ), $providers['primary'], $providers['secondary'] );

				$prm = array( 'msg' => $msg );
				wp_send_json_error( $prm );
			}

			// Login this user.
			$this->login( $wp_user );

		} else {

			// Register new user.
			$this->register();

		}

		wp_send_json_success();
	}

	/**
	 * Login existing WP user.
	 *
	 * @param $wp_user
	 */
	public function login( $wp_user ) {

		// Login procedure.
		wp_clear_auth_cookie();
		wp_set_current_user( $wp_user->ID );
		wp_set_auth_cookie( $wp_user->ID );

		// Hook for changing WP user metadata from Gigya's user.
		do_action( 'gigya_after_raas_login', $this->gigya_account, $wp_user );

		// Do others login Implementations.
		do_action( 'wp_login', $wp_user->data->user_login, $wp_user );
	}

	/**
	 * Register new WP user from Gigya user.
	 */
	private function register() {

		// Register a new user to WP with params from Gigya.
		$name  = $this->gigya_account['profile']['firstName'] . ' ' . $this->gigya_account['profile']['lastName'];
		$email = $this->gigya_account['profile']['email'];

		// If the name of the new user is already exist in the system,
		// WP will reject the registration and return an error. to prevent this
		// we attach an extra value to the name to make it unique.
		$username_exist = username_exists( $name );
		if ( ! empty( $username_exist ) ) {
			$name .= uniqid( '-' );
		}

		// Hook just before register new user from Gigya RaaS.
		do_action( 'gigya_before_raas_register', $name, $email );

		$user_id = register_new_user( $name, $email );

		// On registration error.
		if ( ! empty( $user_id->errors ) ) {
			$msg = '';
			foreach ( $user_id->errors as $error ) {
				foreach ( $error as $err ) {
					$msg .= $err . "\n";
				}
			}

			// Return JSON to client.
			wp_send_json_error( array( 'msg' => $msg ) );
		}

		// Login the user.
		$wp_user = get_userdata( $user_id );
		$this->login( $wp_user );
	}
}