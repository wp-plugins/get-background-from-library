<?php
/*
Plugin Name: Custom Background From Media Library
Plugin URI: http://trepmal.com/plugins/get-background-from-library/
Description: Grab an image from the Media Library to use a your custom background.
Version: 1.2
Author: Kailey Lampert
Author URI: http://kaileylampert.com
*/
/*
    Copyright (C) 2010  Kailey Lampert

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$bgfromlibrary = new bgfromlibrary();

class bgfromlibrary {
	function bgfromlibrary() {
		add_action( 'admin_init', array( &$this, 'init' ) );
	}
	function init() {
		if ( current_theme_supports( 'custom-background' ) ) {
			add_action( 'media_row_actions', array( &$this, 'add_bg_action' ) );
			add_action( 'admin_head-' . 'appearance_page_custom-background', array( &$this, 'handle_incoming_image' ) );
		} else {
			add_action( 'admin_notices', array( &$this, 'no_support' ) );
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
 			$thumb = wp_get_attachment_image_src( $id, 'thumbnail' );
			set_theme_mod('background_image', get_the_guid( $id ) );
			set_theme_mod('background_image_thumb', $thumb['0'] );
		}
	}
	function no_support() {
		?>
		<div class="updated">
			<p>
			<?php _e( 'Your current theme does not support custom backgrounds so the <strong>Custom Background From Media Library</strong> plugin will not work.', 'bgfromlibrary' ); ?>
			</p>
		</div>
		<?php
	}
}