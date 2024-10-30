<?php
// +------------------------------------------------------------------------+
// | @author		<xuxu.fr@gmail.com>
// | @version 		1.2 (2016/10/16) XN
// | @version 		1.1 (2013/12/27) XN
// | @version 		1.0 (2013/12/24) XN
// | Copyright 		(c) 2013 Xuan NGUYEN
// +------------------------------------------------------------------------+

//
require_once BOOBOOK_PLUGIN_DIR."inc/facebook-php-sdk/src/Facebook/autoload.php";

//
$fb_fields = array(
	'email',
	'gender',
	'name',
	'first_name',
	'last_name',
	'middle_name',
	'about',
	'quotes',
	'link',
);

if (!empty($_COOKIE['fb_user_id'])) { // Login!
	//
	$args = array(
		'orderby' => 'display_name',
		'order' => 'ASC',
		'blog_id'=>1,
		'meta_key'=>'boobook_fb_user_id',
		'meta_value'=>$_COOKIE['fb_user_id']
	);
	$the_query = new WP_User_Query($args);
	$users = $the_query->get_results();

	//
	$boobook_fb_app_id = get_option('boobook_fb_app_id');
	$boobook_fb_app_secret = get_option('boobook_fb_app_secret');

	//
	$fb = new Facebook\Facebook([
		'app_id'=>$boobook_fb_app_id,
		'app_secret'=>$boobook_fb_app_secret,
		'cookie'=>true,
		'default_graph_version'=>'v2.8',
	]);

	//
	$helper = $fb->getJavaScriptHelper();
	//
	try {
		$accessToken = $helper->getAccessToken();
	}
	catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	}
	catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo __('Facebook SDK returned an error: ', 'boobook') . $e->getMessage();
		exit;
	}
	//
	if (!isset($accessToken)) {
		echo __('No cookie set or no OAuth data could be obtained from cookie.', 'boobook');
		exit;
	}
	//
	try {
	// Returns a `Facebook\FacebookResponse` object
		$response = $fb->get('/me?fields='.(implode(',', $fb_fields)), $accessToken);
	}
	catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo __('Graph returned an error: ', 'boobook') . $e->getMessage();
		exit;
	}
	catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo __('Facebook SDK returned an error: ', 'boobook') . $e->getMessage();
		exit;
	}

	//
	$fb_user = $response->getDecodedBody();
	// var_dump($fb_user);
	// echo "<hr />";
	// exit;

	if(isset($fb_user) && !empty($fb_user)) {
		//
		$fb_user['email'] = (!empty($fb_user['email'])) ? $fb_user['email'] : "";
		$fb_user['gender'] = (!empty($fb_user['gender'])) ? $fb_user['gender'] : "";
		$fb_user['name'] = (!empty($fb_user['name'])) ? str_replace("'", "\'", $fb_user['name']) : "";
		$fb_user['first_name'] = (!empty($fb_user['first_name'])) ? str_replace("'", "\'", $fb_user['first_name']) : "";
		$fb_user['last_name'] = (!empty($fb_user['last_name'])) ? str_replace("'", "\'", $fb_user['last_name']) : "";
		$fb_user['middle_name'] = (!empty($fb_user['middle_name'])) ? str_replace("'", "\'", $fb_user['middle_name']) : "";
		$fb_user['about'] = (!empty($fb_user['about'])) ? str_replace("'", "\'", $fb_user['about']) : "";
		$fb_user['link'] = (!empty($fb_user['link'])) ? str_replace("/app_scoped_user_id", "", $fb_user['link']) : "";

		$fb_user_name = "boobook_".$fb_user['id'];

		do_action('boobook_fb_user', $fb_user);

		if (sizeof($users) == 0) {
			//echo "CREATION<br />";
			//
			$random_password = wp_generate_password($length = 8, $include_standard_special_chars = false);
			$user_id = wp_create_user($fb_user_name, $random_password, $fb_user['email']);

			//
			$boobook_role_default = get_option('boobook_role_default');
			$U = new WP_User($user_id);
			$U->add_role($boobook_role_default);

			//
			do_action('boobook_user_created', $user_id);

			// send mail
			$blogname = get_bloginfo('name');
			$mailadmin = get_bloginfo('admin_email');

			//
			$boobook_register_notif = get_option('boobook_register_notif');
			if (!empty($boobook_register_notif) && $boobook_register_notif > 0) {
				//$attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip');
				$attachments = "";
				$headers = 'From: '.$blogname.' <'.$mailadmin.'>'."\r\n";
				$message = "
					<strong>".__('A new user just subscribed with Boobook.', 'boobook')."</strong><br />
					<p>Email : <strong>".$fb_user['email']."</strong></p>
					<p>".__('Name', 'boobook')." : <strong>".$fb_user['name']."</strong></p>
				";
				wp_mail($mailadmin, '['.$blogname.'] '.__('New user registered', 'boobook').' : '.$name, $message, $headers, $attachments);
			}
		}
		else {
			//echo "USER EXIST<br />";
			$user_id = $users[0]->ID;
		}

		$params = array(
			"ID"=>$user_id,
			"last_name"=>$fb_user['last_name'],
			"first_name"=>$fb_user['first_name'],
			"display_name"=>$fb_user['name'],
			"description"=>$fb_user['about'],
		);

		//
		do_action('boobook_user_before_update', $user_id, $params);

		//
		wp_update_user($params);

		//
	   	update_user_meta($user_id, 'boobook_fb_user_id', $_COOKIE['fb_user_id']);

	   	// avatar manage
		$picture_path = "https://graph.facebook.com/".$_COOKIE['fb_user_id']."/picture?redirect=false&width=1024";
		$json = file_get_contents($picture_path);
		$json = json_decode($json);

		//
		$picture_basename = basename($json->data->url);
		$picture_array = explode('?', $picture_basename);
		$avatar_filename = "boobook_".$user_id."_".$picture_array[0];

		//
		$boobook_fb_picture_max = get_user_meta($user_id, 'boobook_fb_picture_max', 1);
		$boobook_fb_picture_imported = get_user_meta($user_id, 'boobook_fb_picture_imported', 1);

		//
		$upload_dir = wp_upload_dir();
		$final_url = $upload_dir['url']."/".$avatar_filename;
		$final_path = $upload_dir['path']."/".$avatar_filename;

		if (!file_exists($final_path) || ($boobook_fb_picture_max != $json->data->url)) {			
			//
			if (!empty($boobook_fb_picture_imported)) {
				wp_delete_attachment($boobook_fb_picture_imported, 1);
			}

		   	// Avatar manage
		   	$f = fopen($json->data->url, 'r');
			$contents = '';
			while (!feof($f)) { $contents .= fread($f, 8192); }
			fclose($f);

		   	$f = fopen($final_path, 'w+');
		   	fwrite($f, $contents);
		   	fclose($f);

			$wp_filetype = wp_check_filetype(basename($final_path), null);
			$attachment = array(
				'guid' => $upload_dir['url'].'/'.basename($final_path), 
				'post_author' => $user_id,
				'post_type' => 'attachment',  
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($final_path)),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$subpath = $upload_dir['subdir']."/".basename($final_path);
			$subpath = preg_replace("/^\//", "", $subpath);
			$attach_id = wp_insert_attachment($attachment, $subpath);

			// you must first include the image.php file
			// for the function wp_generate_attachment_metadata() to work
			require_once ABSPATH.'wp-admin/includes/image.php';
			$attach_data = wp_generate_attachment_metadata($attach_id, $final_path);
			wp_update_attachment_metadata($attach_id, $attach_data);

			//
		   	update_user_meta($user_id, 'boobook_fb_picture_imported', $attach_id);

		   	//
		   	update_user_meta($user_id, 'boobook_fb_picture_max', $json->data->url);
		   	update_user_meta($user_id, 'boobook_fb_picture_max_width', $json->data->width);
		   	update_user_meta($user_id, 'boobook_fb_picture_max_height', $json->data->height);

			//
			do_action('boobook_user_avatar_updated', $user_id, $attach_id);
		}

		//set cookie login
		wp_set_auth_cookie($user_id, true, false);

		//
		do_action('boobook_user_authenticated', $user_id);

		//
		$boobook_logged_redirect = get_option('boobook_logged_redirect');

		if (!empty($boobook_logged_redirect)) {
			wp_redirect($boobook_logged_redirect);
			exit;
		}
		else {
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
	}
	else {
		echo __('Error, the Facebook API does not sent any informations', 'boobook');
	}
}
else {
	echo __('Parameters missing', 'boobook');
}
exit;