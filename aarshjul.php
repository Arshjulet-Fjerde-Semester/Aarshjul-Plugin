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

//To install DB when activated
require_once 'app/table-install.php';
register_activation_hook( __FILE__, 'aarshjul_table_install' );

//This should setup the Admin Settings Page
class Aarshjul_Plugin {
	
	//This constructor contains all the hooks used for the plugin in settings
	function __construct() {
		//This is for the settings that manage pdf
		add_action( 'admin_menu', array($this, 'menu_page') );
		add_action( 'admin_init', array($this, 'pdf_upload') );
		//This is for the settings that manage tags
		add_action( 'admin_menu', array($this, 'tag_sub_menu'));
		add_action( 'admin_init', array($this, 'tag_manage') );
	}

	//https://developer.wordpress.org/reference/functions/add_settings_field/
	//https://developer.wordpress.org/reference/functions/register_setting/
	function pdf_upload(){
		add_settings_section('pdfsection', null, null, 'aarshjul-settings');
		add_settings_field('pdfdata', 'Upload PDF', array($this, 'dataHTML'), 'aarshjul-settings', 'pdfsection');
		add_settings_field('pdftags', 'Add Tags (Hold CTRL for multiple)', array($this, 'pdftagHTML'), 'aarshjul-settings', 'pdfsection');
		register_setting('aarshjuldata', 'pdfdata' );
	}

	function tag_manage(){
		add_settings_section('tagsection', null, null, 'aarshjul-tags');
		add_settings_field('tags', 'Write your tag', array($this, 'add_tagsHTML'), 'aarshjul-tags', 'tagsection');
		register_setting('aarshjultags', 'tagdata' );
	}

	function add_tagsHTML(){ ?>
		<input type=text name=tag>
	<?php }

	function dataHTML(){ ?>
		<input type="file" name="pdfdata" accept="application/pdf">
	<?php }

	function pdftagHTML(){ ?>
		<select name="tags[]" id="tags" multiple>
			<?php 
			global $wpdb;
			$tags = $wpdb->get_results( "SELECT * FROM wp_tag" );
			foreach ($tags as $tag){
				echo "<option value='" . $tag->tagid . "'>" . $tag->name . "</options>";
			}
			?>
		</select>
	<?php }

	//add_menu_page( 
		//string $page_title, 
		//string $menu_title, 
		//string $capability, 
		//string $menu_slug, 
		//callable $function = '', 
		//string $icon_url = '', 
		//int $position = null )
	function menu_page(){
		add_menu_page('Aarshjul Settings', 'Aarshjul', 'manage_options', 'aarshjul-settings', array($this, 'adminHTML'));
	}
	//This is the HTML that is shown when clicking into aarshjul page
	function adminHTML(){ ?>
		<div class=wrap>
			<h1>Aarshjul Upload PDF</h1>
			<form action="" method="post" enctype="multipart/form-data">
				<?php
					settings_fields('aarshjuldata');
					do_settings_sections('aarshjul-settings');
					submit_button('Upload PDF', 'primary', 'uploadpdf');
				?>
			</form>
			<h1>Uploaded PDF</h1>
			<ol>
				<?php 
				global $wpdb;
				$pdfs = $wpdb->get_results( "SELECT * FROM wp_pdftext" );
				foreach ($pdfs as $pdf){
					echo "<li>" . $pdf->textname . "</li>";
					submit_button('Delete PDF', 'primary', 'deletepdf');
				}
				?>
			</ol>
		</div>
		<?php
	}

	//add_submenu_page( 
		//string $parent_slug, 
		//string $page_title, 
		//string $menu_title, 
		//string $capability, 
		//string $menu_slug, 
		//callable $function = '', 
		//int $position = null )
	function tag_sub_menu(){
		add_submenu_page('aarshjul-settings', 'Aashjul Tags', 'Tags', 'manage_options', 'aarshjul-tags', array($this, 'manage_tagHTML'));
	}
	//HTML when clicking into tag submenu page
	function manage_tagHTML(){ ?>
	<div class=wrap>
			<h1>Aarshjul Manage Tags</h1>
			<form action="" method="post" enctype="multipart/form-data">
				<?php
					settings_fields('aarshjultags');
					do_settings_sections('aarshjul-tags');
					submit_button('Save Tag', 'primary', 'addtag');
				?>
			</form>
		</div>
	<?php }
}

$aarshjul_plugin = new Aarshjul_Plugin();


//Handles upload of PDF to the file explorer and saves to the database the path and filename
global $wpdb;

    if(isset($_POST['uploadpdf'])){
		$target_dir = "../wp-content/plugins/Aarshjul-plugin/app/uploads/";
		$target_file = $target_dir . basename($_FILES["pdfdata"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Check if file already exists
		if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "pdf" ) {
		echo "Sorry, pdf is the only filetype allowed.";
		$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} 
		else {
			if (move_uploaded_file($_FILES["pdfdata"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["pdfdata"]["name"])). " has been uploaded.";
				echo "We should either call media_handle_upload from wordpress core here, or we should create our own table in the database using https://codex.wordpress.org/Creating_Tables_with_Plugins<br>";
				echo "For now the files gets uploaded to the plugins own upload dir.";
				$table_name = $wpdb->prefix . 'pdftext';
				
				$wpdb->insert( 
					$table_name, 
					array( 
						'path' => $target_file, 
						'textname' => $_FILES["pdfdata"]["name"], 
					)				
				);
				$pdfid = $wpdb->insert_id;
				//This should add a connection between uploaded PDF and currently available tags instead
				$table_name = $wpdb->prefix . 'pdftext_tag';
				foreach ( $_POST['tags'] as $tagid){
					$wpdb->insert(
						$table_name,
						array(
							'pdfid' => $pdfid,
							'tagid' => $tagid		
						)
					);
				}
			} else {
			echo "Sorry, there was an error uploading your file.";
			}
		}
	}

	if(isset($_POST['addtag'])){
		$table_name = $wpdb->prefix . 'tag';
		$wpdb->insert(
			$table_name,
			array(
				'name' => test_input($_POST['tag'])			
			)
		);
	}
	if(isset($_POST['deletepdf'])){
		echo "SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS";
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	  }
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
		plugins_url( 'build/index.js', __FILE__ ),						// script file
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),		// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )		// set version as file last modified time
	);

	// Register the block editor stylesheet.
	wp_register_style(
		'podkit-editor-styles',											// label
		plugins_url( 'build/editor.css', __FILE__ ),					// CSS file
		array( 'wp-edit-blocks' ),										// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'build/editor.css' )	// set version as file last modified time
	);

	// Register the front-end stylesheet.
	wp_register_style(
		'podkit-front-end-styles',										// label
		plugins_url( 'build/style.css', __FILE__ ),						// CSS file
		array( ),														// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'build/style.css' )	// set version as file last modified time
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