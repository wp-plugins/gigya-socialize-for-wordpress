<link rel="stylesheet" href="<?php echo $this->info->pluginUrl; ?>/resources/login.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $this->info->jQueryUrl; ?>"></script>
<script type="text/javascript" src="<?php echo $this->info->pluginUrl; ?>/resources/gs-for-wordpress.js"></script>
<script type="text/javascript" src="<?php echo $this->info->socializeUrl; ?>"></script>
<script type="text/javascript" src="<?php echo $this->info->pluginUrl; ?>/resources/login.js"></script>
<script type='text/javascript'>
	var gigya_blog_homepage =  '<?php echo site_url('/'); ?>';
	jQuery(document).ready(function() {
		jQuery('.login #login #nav').after('<a id="gs-for-wordpress-redirect-url" style="display: none;" href="<?php echo admin_url(); ?>"></a>');
		<?php
		echo $this->getMainLoginUIComponentCode();
		?>
		gigya.services.socialize.showLoginUI(conf, login_params);
		gigya.services.socialize.addEventHandlers(conf,{onLogin:processLogin});
		<?php if( $_GET[ 'just-logged-out' ] == 1 ) { ?>
		if( typeof( gigya ) != 'undefined' ) {
			if( typeof( conf ) != 'undefined' ) {
				gigya.services.socialize.logout(conf,{});
			}
		}
		<?php
		}
		?>
		<?php if( $_GET[ 'action' ] == 'lostpassword' ) { ?>jQuery('#componentDiv').prepend('<p>If you previously signed-up using one of the services below, please click the appropriate button to login again.</p>').css('height', ( parseInt( jQuery('#componentDiv').css('height') ) + 50 ) + 'px' ); <?php } ?>
		
	});
</script>