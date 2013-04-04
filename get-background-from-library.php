<?php
/*
 * Plugin Name: Custom Background From Media Library
 * Plugin URI: http://trepmal.com/plugins/get-background-from-library/
 * Description: Grab an image from the Media Library to use a your custom background.
 * Version: 1.3
 * Author: Kailey Lampert
 * Author URI: http://kaileylampert.com
 * License: GPLv2 or later
 * TextDomain: bgfromlibrary
 * DomainPath:
 */

$bgfromlibrary = new bgfromlibrary();

class bgfromlibrary {
	function bgfromlibrary() {
		add_action( 'admin_init', array( &$this, 'init' ) );
	}
	function init() {
		if ( current_theme_supports( 'custom-background' ) ) {
			add_action( 'media_row_actions', array( &$this, 'add_bg_action' ), 10, 2 );
			add_action( 'admin_head-' . 'appearance_page_custom-background', array( &$this, 'handle_incoming_image' ) );
		} else {
			add_action( 'admin_notices', array( &$this, 'no_support' ) );
		}
	}
	function add_bg_action( $actions, $post ) {
		$id = $post->ID;
		$link = "<a href='themes.php?page=custom-background&from_library={$id}'>" . __( 'Use as Background', 'bgfromlibrary' ) . '</a>';
		if ( in_array( get_post_mime_type( $id ), array( 'image/jpeg', 'image/png', 'image/gif' ) ) )
			$actions['from_library'] = $link;
		return $actions;
	}
	function handle_incoming_image() {
		if ( isset( $_GET['from_library'] ) ) {
			$id = $_GET[ 'from_library' ];
 			$thumb = wp_get_attachment_image_src( $id, 'thumbnail' );
			set_theme_mod('background_image', get_the_guid( $id ) );
			set_theme_mod('background_image_thumb', $thumb['0'] );
		}
	}
	function no_support() {
		?><div class="updated"><p>
			<?php _e( 'Your current theme does not support custom backgrounds so the <strong>Custom Background From Media Library</strong> plugin will not work.', 'bgfromlibrary' ); ?>
		</p></div><?php
	}
}