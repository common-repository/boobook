<?php
	//
	function boobook_install() {
		global $wpdb, $wp, $wp_rewrite;

		$boobook_db_version = "1.0";
		
		//
		update_option("boobook_db_version", $boobook_db_version);

		//
		$boobook_role_default = get_option("default_role");
		$boobook_role_default = (empty($boobook_role_default)) ? "subscriber" : $boobook_role_default;
		update_option("boobook_role_default", $boobook_role_default);

		//
		update_option("boobook_register_notif", 1);
		update_option("boobook_logged_redirect", '');
		update_option("boobook_logout_redirect", '');
	}

	//
	function boobook_uninstall() {
		//
		global $wpdb;

		//
		delete_option('boobook_db_version');
		delete_option('widget_wp_boobook_connect');
		delete_option('boobook_role_default');
		delete_option('boobook_register_notif');
		delete_option('boobook_logged_redirect');
		delete_option('boobook_logout_redirect');
		delete_option('boobook_fb_app_id');
		delete_option('boobook_fb_app_secret');
		delete_option('fb_scopes');
		delete_option('boobook_role_default');
		delete_option('boobook_register_notif');

		//
        $sql = "
        	DELETE FROM `".$wpdb->usermeta."`
        	WHERE
        		`meta_key` IN ('boobook_fb_user_id', 'boobook_fb_picture_imported', 'boobook_fb_picture_max', 'boobook_fb_picture_max_width', 'boobook_fb_picture_max_height')
    	";
        $wpdb->query($sql);
	}