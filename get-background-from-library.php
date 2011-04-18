<?php
/*
Plugin Name: Custom Background From Media Library
Plugin URI: 
Description: Grab an image from the Media Library to use a your custom background.
Version: 1.0
Author: Kailey Lampert
Author URI: http://kaileylampert.com
*/

$bgfromlibrary = new bgfromlibrary();

class bgfromlibrary {
	function bgfromlibrary() {
		add_action( 'admin_init', array( &$this, 'init' ) );
	}
	function init() {
		if ( current_theme_supports( 'custom-background' ) ) {
			add_action( 'media_row_actions', array( &$this, 'add_bg_action' ) );
			add_action( 'admin_head-'.'appearance_page_custom-background', array( &$this, 'handle_incoming_image' ) );
		}
	}
	function add_bg_action( $actions ) {
		global $post;
		$id = $post->ID;
		$link = '<a href="' . 'themes.php?page=custom-background' . '&from_library=' . $id . '">' . __( 'Use as Background', 'bgfromlibrary' ) . '</a>';
		$mime = get_post_mime_type( $id );
		$allowed = array( 'image/jpeg', 'image/png', 'image/gif' );
		if ( in_array( $mime, $allowed ) )
		$actions['from_library'] = $link;
		return $actions;
	}
	function handle_incoming_image() {	
		if (isset($_GET[ 'from_library' ])) {
			$id = $_GET[ 'from_library' ];
			$option = 'mods_' . get_option( 'current_theme' );
			$theme_mods = get_option( $option );
			$theme_mods['background_image'] = get_the_guid( $id );
			$thumb = wp_get_attachment_image_src( $id, 'thumbnail' );
			$theme_mods['background_image_thumb'] = $thumb['0'];
			update_option($option, $theme_mods);
		}
	}
}