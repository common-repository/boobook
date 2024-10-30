<?php
/*----------------------------------------------------------------------*
 |
 *----------------------------------------------------------------------*/
function boobook_set_thumbsize() {
	global $wpdb;
	
	//
	add_image_size('boobook-header-thumbnail', 140, 80, true);
	add_image_size('boobook-header-thumbnail-reverse', 100, 120, true);
	add_image_size('boobook-post-thumbnail', 280, 160, true);
}