<?php

/**
 * Plugin Name: Aashjul Unity Block
 * Plugin URI: https://github.com/LinkedInLearning/WPContentBlocks-Adv-5034179
 * Description: Custom block plugin for Aarshjul
 * Version: 1.0.0
 * Author: Simon Baunsgaard Hansen
 *
 * @package podkit
 */

defined( 'ABSPATH' ) || exit;

define('__ROOT__', dirname(__FILE__));

//To install DB when activated
require_once 'app/table-install.php';
require_once 'app/views/original-manage.php';
require_once 'app/views/tag-manage.php';

register_activation_hook( __FILE__, 'aarshjul_table_install' );

//This should setup the Admin Settings Page
class Aarshjul_Plugin {
	
	//This constructor contains all the hooks used for the plugin in settings
	function __construct() {
		add_action( 'admin_init', array($this, 'aarshjul_plugin_register_styles') );
		add_action( 'admin_init', array($this, 'aarshjul_plugin_register_scripts') );
		$originalmanage = new Original_Manage();
		$tagmanage = new tag_manage();
	}
	//register styles for later use
	function aarshjul_plugin_register_styles() {
		//wp_register_style( string $handle, REQ
		//string|bool $src, REQ
		//string[] $deps = array(), 
		//string|bool|null $ver = false, 
		//string $media = 'all' )
		wp_register_style( 'aarshjulStylesheet', plugins_url( 'app/css/liststyle.css', __FILE__ ) );
		wp_register_style( 'bootstrapStylesheet', plugins_url( 'app/css/bootstrap.min.css', __FILE__ ) );
		wp_register_style( 'fontawesomeStylesheet', plugins_url( 'app/css/font-awesome.min.css', __FILE__ ) );
	}
	//register scripts for later use
	function aarshjul_plugin_register_scripts(){
		//wp_register_script( string $handle, REQ
		//string|bool $src, REQ
		//string[] $deps = array(), 
		//string|bool|null $ver = false, 
		//bool $in_footer = false )
		wp_register_script('bootstrapScript', plugins_url( 'app/js/bootstrap.min.js', __FILE__ ));
		wp_register_script('jqueryScript', plugins_url( 'app/js/jquery.min.js', __FILE__ ));
	}
}

$aarshjul_plugin = new Aarshjul_Plugin();	

//The block part starts from here

/**
 * Load translations (if any) for the plugin from the /languages/ folder.
 * 
 * @link https://developer.wordpress.org/reference/functions/load_plugin_textdomain/
 */
add_action( 'init', 'podkit_load_textdomain' );

function podkit_load_textdomain() {
	load_plugin_textdomain( 'podkit', false, basename( __DIR__ ) . '/languages' );
}

/** 
 * Add custom image size for block featured image.
 * 
 * @link https://developer.wordpress.org/reference/functions/add_image_size/
 */
add_action( 'init', 'podkit_add_image_size' );

function podkit_add_image_size() {
	add_image_size( 'podkitFeatImg', 250, 250, array( 'center', 'center' ) ); 
}

/** 
 * Register custom image size with sizes list to make it available.
 * 
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/image_size_names_choose
 */
add_filter( 'image_size_names_choose', 'podkit_custom_sizes' );

function podkit_custom_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'podkitFeatImg' => __('Podkit Featured Image'),
	) );
}

/** 
 * Add custom "Podkit" block category
 * 
 * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/filters/block-filters/#managing-block-categories
 */
add_filter( 'block_categories', 'podkit_block_categories', 10, 2 );

function podkit_block_categories( $categories, $post ) {
	if ( $post->post_type !== 'post' ) {
		return $categories;
	}
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'podkit',
				'title' => __( 'Podkit', 'podkit' ),
				'icon'  => 'microphone',
			),
		)
	);
}

/**
 * Registers all block assets so that they can be enqueued through the Block Editor in
 * the corresponding context.
 *
 * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-registration/
 */
add_action( 'init', 'podkit_register_blocks' );

function podkit_register_blocks() {

	// If Block Editor is not active, bail.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// Retister the block editor script.
	wp_register_script(
		'podkit-editor-script',											// label
		plugins_url( 'block/build/index.js', __FILE__ ),						// script file
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),		// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'block/build/index.js' )		// set version as file last modified time
	);

	// Register the block editor stylesheet.
	wp_register_style(
		'podkit-editor-styles',											// label
		plugins_url( 'block/build/editor.css', __FILE__ ),					// CSS file
		array( 'wp-edit-blocks' ),										// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'block/build/editor.css' )	// set version as file last modified time
	);

	// Register the front-end stylesheet.
	wp_register_style(
		'podkit-front-end-styles',										// label
		plugins_url( 'block/build/style.css', __FILE__ ),						// CSS file
		array( ),														// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'block/build/style.css' )	// set version as file last modified time
	);

	// Array of block created in this plugin.
	$blocks = [
		'podkit/static',
		'podkit/editable'
	];
	
	// Loop through $blocks and register each block with the same script and styles.
	foreach( $blocks as $block ) {
		register_block_type( $block, array(
			'editor_script' => 'podkit-editor-script',					// Calls registered script above
			'editor_style' => 'podkit-editor-styles',					// Calls registered stylesheet above
			'style' => 'podkit-front-end-styles',						// Calls registered stylesheet above
		) );	  
	}

	if ( function_exists( 'wp_set_script_translations' ) ) {
	/**
	 * Adds internationalization support. 
	 * 
	 * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/internationalization/
	 * @link https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
	 */
	wp_set_script_translations( 'podkit-editor-script', 'podkit', plugin_dir_path( __FILE__ ) . '/languages' );
	}

}