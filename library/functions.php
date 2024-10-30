<?php
	/*----------------------------------------------------------------------*
	 | add option page for boobook
	 *----------------------------------------------------------------------*/
	function add_boobook_options() {
		add_options_page(__('Boobook settings', 'boobook'), __('Boobook', 'boobook'), 'manage_options', 'boobook-page-options', 'boobook_options');  
	}
    add_action('admin_menu', 'add_boobook_options');  

	/*----------------------------------------------------------------------*
	 | option page form for boobook
	 *----------------------------------------------------------------------*/
	function boobook_options() {  
		global $wp_roles;
?>  
	<div class="wrap">  
		<h2><?php _e('Boobook settings', 'boobook');?></h2>  
		<form method="post" action="options.php">  
			<input type="hidden" name="action" value="update" />  
    		<input type="hidden" name="page_options" value="boobook_fb_app_id, boobook_fb_app_secret, fb_scopes, boobook_role_default, boobook_register_notif, boobook_logged_redirect, boobook_logout_redirect" />  
			<?php wp_nonce_field('update-options');?>  
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="boobook_fb_app_id"><?php _e('Facebook App ID', 'boobook');?></label></th>
						<td>
							<input type="text" id="boobook_fb_app_id" name="boobook_fb_app_id" class="regular-text code" value="<?php echo get_option('boobook_fb_app_id');?>" />  
						</td>
					</tr>
					<tr>
						<th><label for="boobook_fb_app_secret"><?php _e('FB App Secret', 'boobook');?></label></th>
						<td>
							<input type="text" id="boobook_fb_app_secret" name="boobook_fb_app_secret" class="regular-text code" value="<?php echo get_option('boobook_fb_app_secret');?>" />  
						</td>
					</tr>
					<tr>
						<th><label for="boobook_role_default"><?php _e('Default user role when created', 'boobook');?></label></th>
						<td>
							<select id="boobook_role_default" name="boobook_role_default">
<?php
							$boobook_role_default = get_option('boobook_role_default');
							foreach($wp_roles->role_objects as $key=>$value) {
								$selected = ($key == $boobook_role_default) ? " selected=\"selected\"" : "";
								echo "<option value=\"".$key."\"".$selected.">";
								echo translate_user_role(ucfirst($value->name));
								echo "</option>";
							}
?>
							</select>
						</td>
					</tr>
					<tr style="display: none;"> <!-- Future dev ? -->
						<th><label for="fb_scopes"><?php _e('FB Scopes', 'boobook');?></label></th>
						<td>
<?php
							$fb_scopes = get_option('fb_scopes');
							$fb_scopes = (empty($fb_scopes)) ? 'email,user_about_me' : $fb_scopes;
?>
							<textarea id="fb_scopes" name="fb_scopes" class="large-text code" cols="50" rows="10"><?php echo $fb_scopes;?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for="boobook_register_notif"><?php _e('Receive an email when an user was created', 'boobook');?></label></th>
						<td>
							<?php $boobook_register_notif = get_option('boobook_register_notif');?>
							<select id="boobook_register_notif" name="boobook_register_notif">
								<option value="1"<?php echo ($boobook_register_notif == 1) ? " selected=\"selected\"" : "";?>>Oui</option>
								<option value="0"<?php echo ($boobook_register_notif == 0) ? " selected=\"selected\"" : "";?>>Non</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="boobook_logged_redirect"><?php _e('Redirect URL after login', 'boobook');?></label></th>
						<td>
							<input type="text" id="boobook_logged_redirect" name="boobook_logged_redirect" class="regular-text code" value="<?php echo get_option('boobook_logged_redirect');?>" />  
							<br />
 							<em><?php _e('If empty, the person will be redirected to the previous page', 'boobook');?></em>
 						</td>
					</tr>
					<tr>
						<th><label for="boobook_logout_redirect"><?php _e('Redirect URL after logout', 'boobook');?></label></th>
						<td>
							<input type="text" id="boobook_logout_redirect" name="boobook_logout_redirect" class="regular-text code" value="<?php echo get_option('boobook_logout_redirect');?>" />  
							<br />
 							<em><?php _e('If empty, the person will be redirected to the site root &laquo;/&raquo;', 'boobook');?></em>
 						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" class="button button-primary" name="submit" value="<?php _e('Save', 'boobook');?>" />
			</p>  
    	</form>  
	</div>  
<?php  
}  


	/*----------------------------------------------------------------------*
	 |
	 *----------------------------------------------------------------------*/
	function boobook_admin_enqueue_scripts() {
		wp_enqueue_script('boobook-back', WP_PLUGIN_URL.'/boobook/js/boobook-back.js');
	}
	add_action('admin_enqueue_scripts', 'boobook_admin_enqueue_scripts');


	/*----------------------------------------------------------------------*
	 |
	 *----------------------------------------------------------------------*/
	function boobook_admin_enqueue_styles() {
		wp_enqueue_style('boobook-back', WP_PLUGIN_URL.'/boobook/css/boobook-back.css');
	}
	add_action('admin_enqueue_scripts', 'boobook_admin_enqueue_styles');


	/*----------------------------------------------------------------------*
	 |
	 *----------------------------------------------------------------------*/
	function boobook_enqueue_scripts() {
		//
		do_action('boobook_hook_scripts');
		//
		wp_enqueue_script('boobook-front', WP_PLUGIN_URL.'/boobook/js/boobook-front.js', array('jquery'));
	}
	add_action('wp_enqueue_scripts', 'boobook_enqueue_scripts');


	/*----------------------------------------------------------------------*
	 |
	 *----------------------------------------------------------------------*/
	function boobook_enqueue_styles() {
		//
		do_action('boobook_hook_styles');
		//
		wp_enqueue_style('boobook-front', WP_PLUGIN_URL.'/boobook/css/boobook-front.css');
	}
	add_action('wp_enqueue_scripts', 'boobook_enqueue_styles');


	/*----------------------------------------------------------------------*
	 |
	 *----------------------------------------------------------------------*/
	function boobook_wp_head() {
		$boobook_fb_app_id = get_option('boobook_fb_app_id');
		$fb_scopes = get_option('fb_scopes');
	?>
		<script type="text/javascript">
			// plugin
			var boobook_ajax_url = '<?php echo WP_PLUGIN_URL;?>/boobook/ws/';
			var WP_PLUGIN_URL = '<?php echo WP_PLUGIN_URL;?>';

			// fb
	        var boobook_fb_app_id = '<?php echo $boobook_fb_app_id;?>';
	        var all_scope_comma = '<?php echo $fb_scopes;?>';

		</script>
	<?php
	}
	add_action('wp_head', 'boobook_wp_head');


	/*----------------------------------------------------------------------*
	 | shortcode
	 *----------------------------------------------------------------------*/
	function boobook_sc($atts) {
		global $wpdb, $post;

		$classes = array("btn", "boobook-btn", "connect-btn");

		extract(shortcode_atts(array(
			'id' => '',
			'label' => '',
			'class' => '',
		), $atts));

		$class = explode(' ', $class);
		foreach($class as $classname) {
			$classes[] = ltrim(rtrim($classname));
		}

		$id = (!empty($id)) ? "id=\"".$id."\"" : "";
		$label = (!empty($label)) ? $label : __('Login', 'boobook');;

		if (!is_user_logged_in()) {
			$content = "<button ".$id." class=\"".implode(' ', $classes)."\"><span>".$label."</span></button>";
		}
		else {
			$content = "";	
		}
		
		return $content;
	}
	add_shortcode('boobook-btn-connect', 'boobook_sc');


	/*----------------------------------------------------------------------*
	 | Template redirect
	 *----------------------------------------------------------------------*/
	// http://stackoverflow.com/questions/4647604/wp-use-file-in-plugin-directory-as-custom-page-template
	function boobook_theme_redirect() {
	    global $wp, $wp_query;

	    if (!empty($_SERVER['REQUEST_URI']) && preg_match("/^\/boobook\/connect\/\?code=([-_0-9a-zA-Z]+)/", $_SERVER['REQUEST_URI'], $matches)) {
		    //
			$wp_query->is_404 = false;		
			status_header(200);

			//
	        $template_filename = 'connect.php';
			$return_template = BOOBOOK_PLUGIN_DIR.'template/'.$template_filename;

	        include($return_template);
	    }
	}
	add_action('template_redirect', 'boobook_theme_redirect');


	/*----------------------------------------------------------------------*
	 | Boobook avatar
	 *----------------------------------------------------------------------*/
	function boobook_get_avatar($avatar, $id_or_email, $size, $default, $alt) {
		global $wpdb, $user_ID;

		if(is_numeric($id_or_email)) {
			$user_id = $id_or_email;
		}
		else if (is_string($id_or_email)) {
			$query = "SELECT `ID` FROM `$wpdb->users` WHERE `user_email` = '".$id_or_email."'";
			$user_id = $wpdb->get_var($query);
		}
		else {

		}
		if(is_numeric($size)) {
			$style = " width=\"".$size."\" height=\"".$size."\"";
			$size = array($size, $size);
		}
		else if (is_array($size)) {
			$style = " width=\"".$size[0]."\" height=\"".$size[1]."\"";
			$size = $size;
		}
		else if (is_string($size)) {
			$style = "";
			$size = $size;
		}

		$attachment_id = get_user_meta($user_id, 'boobook_fb_picture_imported', 1);
		if (!empty($attachment_id)) {
			$image = wp_get_attachment_image_src($attachment_id, $size);
			$avatar = "<img src=\"".$image[0]."\"".$style." alt=\"Boobook avatar\" class=\"avatar photo\" />";
		}

	    return $avatar;
	}
	add_filter('get_avatar', 'boobook_get_avatar', 10, 5);


	/*----------------------------------------------------------------------*
	 | Boobook logout
	 *----------------------------------------------------------------------*/
	function boobook_logout() {
		setcookie('fb_user_id', null, time()*3600*24*7);
		setcookie('fb_access_token', null, time()*3600*24*7);
	}
	add_action('wp_logout','boobook_logout');	


	/*----------------------------------------------------------------------*
	 | Boobook logout url
	 *----------------------------------------------------------------------*/
	function boobook_logout_url($logout_url, $redirect = null) {
		$boobook_logout_redirect = get_option('boobook_logout_redirect');
		if (!empty($boobook_logout_redirect)) {
			$_wpnonce = wp_create_nonce('log-out');
			$logout_url_new = home_url()."/wp-login.php?action=logout&redirect_to=".urlencode($boobook_logout_redirect)."&_wpnonce=".$_wpnonce;
			return $logout_url_new;	
		}

		return $logout_url;
	}
	add_filter('logout_url', 'boobook_logout_url');

