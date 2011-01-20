<?php
class GigyaSO_Core {
	public function __construct() {}
	# script tag for gigya api
	public function render_gigya_js(){
	?>
		<script type="text/javascript" src="http://cdn.gigya.com/JS/socialize.js?apikey=<?php echo gigya_get_option("api_key");?>"></script>
	<?php 
	}
	# render gigya plugin css
	public function render_css(){
	?>
		<link media="all" type="text/css" href="<?php echo GIGYA_PLUGIN_URL;?>/css/gigya.css" rel="stylesheet">
		<link media="all" type="text/css" href="<?php echo GIGYA_PLUGIN_URL;?>/css/jqueryui/custom-theme/jquery-ui-1.8.7.custom.css" rel="stylesheet">
	<?php 	
	}
	# render all required javascript for plugin
	public function render_js(){
		$wp_js_path = get_bloginfo('wpurl').'/'.WPINC.'/js'; 
		$js_path = GIGYA_PLUGIN_URL."/js";
	?>
		
		<!--  Jquery  -->
		<script type="text/javascript" src="<?php echo $wp_js_path;?>/jquery/jquery.js"></script>
		<script  type="text/javascript" src="<?php echo $js_path;?>/jquery.tmpl.js"></script>
		<!--  JSON2  -->
		<script type="text/javascript" src="<?php echo $wp_js_path;?>/json2.js"></script>
		<!--  Dialog  -->
		<script type="text/javascript" src="<?php echo $wp_js_path;?>/jquery/ui.core.js"></script>
		<script type="text/javascript" src="<?php echo $wp_js_path;?>/jquery/ui.draggable.js"></script>
		<script type="text/javascript" src="<?php echo $wp_js_path;?>/jquery/ui.resizable.js"></script>
		<script  type="text/javascript" src="<?php echo $wp_js_path;?>/jquery/ui.dialog.js"></script>
		<!--  Gigya  -->
		<script  type="text/javascript" src="<?php echo "$js_path/gigya.js";?>"></script>
		<?php gigya_script_js(); ?>
	<?php 	
	}
	
	public function conf_and_params($params = array()){
		$api_key = gigya_get_option("api_key");
		$header_text = (isset($params["header_text"]) ? $params["header_text"] : __("Sign in with your Social Network:"));
		$width = $params["width"] ? $params["width"] : 345 ;
		$height = $params["height"] ? $params["height"] : 145 ;
		$enabledProviders = $params["enabledProviders"] ? $params["enabledProviders"] : '*' ;
	?>
		var conf = {
		     'APIKey': '<?php echo $api_key;?>'
		};
		
		var login_params = {
			showTermsLink:false,
			headerText:'<label><?php echo $header_text; ?></label>',
			height:<?php echo $height;?>,
			width:<?php echo $width; ?>,
			enabledProviders:'<?php echo $enabledProviders; ?>',
			context:'GigLogin',
			pendingRegistration : true,
			UIConfig:'<config><body><controls><snbuttons buttonsize=\"<?php echo $params["button_size"] ? $params["button_size"] : 42 ;?>\"></snbuttons></controls><background frame-color=\"#FFFFFF\"></background></body></config>',
			containerID:'componentDiv'
		};
	<?php 	
	}
	
	public function render_tmpl(){
	?>
		<script id="gigya-new-user-tmpl" type="text/x-jquery-tmpl">
    	<div id='gigya-new-user-wrap' class='float-left'>
			<h3 class='label'><?php echo __('Please provide your email address to join'); ?></h3>
			<p>
				<label><?php echo __('Email') ?><br>
					<input type='text' name='email' size='20' value='' class='input'>
				</label>
			</p>
			<div class='button-wrap'>
				<input id='gigya-new-user-button' style='width:auto;' type='button' value='<?php echo __('Register'); ?>' class='button-primary'>
			</div>
		</div>
		</script>
	
		<script id="gigya-account-linking-tmpl" type="text/x-jquery-tmpl">
		<div id='gigya-sep-wrap'>
			<div class="sep-line"></div>
			<h3>OR</h3>
			<div class="sep-line"></div>
		</div>
    	<div id='gigya-account-linking-wrap'>
			<h3 class='label'>Yes, Please link my existing account with ${user.loginProvider} for quick and secure access</h3>
			<p>
				<label>Email<br>
					<input type='text' size='20' value='' class='input' name='email'>
				</label>
			</p>
			<p>
				<label>Password<br>
					<input type='password' size='20' value='' class='input' name='password'>
				</label>
			</p>
			<div class='button-wrap'>
				<input id='gigya-new-account-button' style='width:auto;' type='button' value='Link Accounts' class='button-primary'>
			</div>
		</div>
		</script>
	
		<script id="gigya-header-tmpl" type="text/x-jquery-tmpl">
		<div id='dialog-header'>
			<div class="ui-helper-clearfix">
			<img class='thumbnail' src='${user.thumbnailURL}'/>
			<p class='text'>
				<b>Hi ${user.firstName}</b>
					{{if $isEmailExist=true}}, The email is already used, please provide a new email or link to an existing account.{{/if}}
					{{if $isNewUser=true}}{{/if}}
			</p>
			</div>	
		</div>
		</script>
	<?php 
	} 	
	
	public function render_profile_connect(){
	?>
		
	<?php 	
	}
}


