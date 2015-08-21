<?php 

/**
 * remove junk from head
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head' );
remove_action('wp_head', 'print_emoji_detection_script', 7 );
remove_action('admin_print_scripts', 'print_emoji_detection_script' );
remove_action('wp_print_styles', 'print_emoji_styles' );
remove_action('admin_print_styles', 'print_emoji_styles' );


/**
 * theme support
 */
add_theme_support( 'post-thumbnails' );
add_theme_support('menus');
add_theme_support('html5');



/**
 * add Image size
 */
add_image_size('thumbnail', '220', '220', true);


/**
 * remove element from worpdress menu
 */

add_action( 'admin_menu', 'remove_menus', 9999 );
function remove_menus(){
	remove_menu_page( 'edit-comments.php' );  // comments
	remove_menu_page( 'edit.php' ); // post
	remove_menu_page( 'plugins.php' );    //Plugins
	remove_menu_page('ot-settings'); //option tree
	remove_submenu_page( 'themes.php', 'themes.php' );
	remove_submenu_page( 'themes.php', 'theme-editor.php' );
	remove_submenu_page( 'themes.php', 'customize.php' );

	//remove_menu_page( 'options-general.php' );
	remove_submenu_page( 'options-general.php', 'options-writing.php' );
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	remove_submenu_page( 'options-general.php', 'options-reading.php' );
	remove_submenu_page( 'options-general.php', 'options-media.php' );
	remove_submenu_page( 'options-general.php', 'breadcrumb-navxt' );
	remove_submenu_page( 'options-general.php', 'wp-paginate.php' );
	remove_submenu_page( 'options-general.php', 'options-permalink.php' );

	//remove_menu_page( 'tools.php' );
	remove_submenu_page( 'tools.php', 'tools.php' );
	remove_submenu_page( 'tools.php', 'import.php' );
	remove_submenu_page( 'tools.php', 'export.php' );  
	remove_submenu_page( 'tools.php', 'ra_export' );  

}


/**
 * get the id of the attachement by it's URL
 * @param  String $image_src 
 * @return int
 */
function get_attachment_id_from_src ($image_src) {

  global $wpdb;
  $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
  $id = $wpdb->get_var($query);
  return $id;

}

/**
 * remove wordpress item from the admin bar and menu
 */
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node('comments');
	//$wp_admin_bar->remove_node('new-content');
}

/*
 * execute wordpress cron job 
*/
add_action( 'wp', 'prefix_setup_schedule' );
add_action( 'prefix_twicedaily_event', 'function_to_execute' );
function function_to_execute(){
	// put your code here
}
function prefix_setup_schedule() {
    if ( ! wp_next_scheduled( 'prefix_twicedaily_event' ) ) {
        wp_schedule_event( time(), 'twicedaily', 'prefix_twicedaily_event');
    }
}

/**
 * auto generate post thumbnail
 * source : http://www.fredzone.org/wordpress-generer-automatiquement-les-images-a-la-une-de-ses-articles-441#HFZfE7Dyyx4hBKkZ.99
 */
add_action('save_post', 'autothumb', 10, 2); 
function autothumb( $post_id, $post ){ 
	if(!current_user_can('upload_files')) 
		return false; 
	if(!has_post_thumbnail($post_id)){ 
		$attached_image = get_children( array( 
			'post_parent' => $post_id, 
			'post_type' => 'attachment', 
			'post_mime_type' => 'image', 
			'numberposts' => 1 
		)); 
		if(!count($attached_image)) 
			return false; 
		$attached_image = array_keys( $attached_image ); 
		set_post_thumbnail($post_id, $attached_image[0]); 
	}
}



?>