<?php

/**
 * Plugin Name:       Aarshjul
 * Plugin URI:        null
 * Description:       A simple plugin for creating custom post types and taxonomies related to a aarshjul directory.
 * Version:           1.0.0
 * Author:            Simon Hansen
 * Author URI:        null
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Aarshjul Post Types
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'VERSION', '1.0.0' );
define( 'DOMAIN', 'lil-post-types' );
define( 'PATH', plugin_dir_path( __FILE__ ) );

require_once( PATH . '/aarshjul-types/register.php' );

add_action( 'init', 'register_aarshjul_type' );