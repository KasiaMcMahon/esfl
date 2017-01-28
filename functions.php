<?php

/********** UNCOMMENT THE FOLLOWING CODE TO DISPLAY PHP ERRORS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 **********/

add_action( 'wp_enqueue_scripts', 'esfl_enqueue_styles' );

function esfl_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

/********** UNCOMMENT THE FOLLOWING CODE TO ENQUEUE A CUSTOM JAVASCRIPT FILE

function UPDATE_NAME_HERE_enqueue_script() {
wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/UPDATE_NAME_HERE_custom.js', 'jquery');
}

add_action( 'wp_enqueue_scripts', 'UPDATE_NAME_HERE_enqueue_script' );

 **********/

add_filter( 'et_builder_post_types', 'rbs_et_builder_post_types' );

function rbs_et_builder_post_types ($post_types) {
	$custom_post_types = array (
		'tribe_events',
		// 'sfwd-courses', - add more CPT's if needed
	);

	$new_post_types = array_merge($post_types, $custom_post_types);
	return $new_post_types;
}

function ESFL_Custom_Modules(){
	if(class_exists("ET_Builder_Module")){
		include("esfl-custom-modules.php");
	}
}

function Prep_ESFL_Custom_Modules(){
	global $pagenow;

	$is_admin = is_admin();
	$action_hook = $is_admin ? 'wp_loaded' : 'wp';
	$required_admin_pages = array( 'edit.php', 'post.php', 'post-new.php', 'admin.php', 'customize.php', 'edit-tags.php', 'admin-ajax.php', 'export.php' ); // list of admin pages where we need to load builder files
	$specific_filter_pages = array( 'edit.php', 'admin.php', 'edit-tags.php' );
	$is_edit_library_page = 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'et_pb_layout' === $_GET['post_type'];
	$is_role_editor_page = 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'et_divi_role_editor' === $_GET['page'];
	$is_import_page = 'admin.php' === $pagenow && isset( $_GET['import'] ) && 'wordpress' === $_GET['import'];
	$is_edit_layout_category_page = 'edit-tags.php' === $pagenow && isset( $_GET['taxonomy'] ) && 'layout_category' === $_GET['taxonomy'];

	if ( ! $is_admin || ( $is_admin && in_array( $pagenow, $required_admin_pages ) && ( ! in_array( $pagenow, $specific_filter_pages ) || $is_edit_library_page || $is_role_editor_page || $is_edit_layout_category_page || $is_import_page ) ) ) {
		add_action($action_hook, 'ESFL_Custom_Modules', 9789);
	}
}
Prep_ESFL_Custom_Modules();