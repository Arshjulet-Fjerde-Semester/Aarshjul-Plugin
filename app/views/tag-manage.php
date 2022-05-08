<?php

defined( 'ABSPATH' ) || exit;

require_once(__ROOT__.'/app/database/tag/create.php');
require_once(__ROOT__.'/app/database/tag/read.php');
require_once(__ROOT__.'/app/database/tag/update.php');
require_once(__ROOT__.'/app/database/tag/delete.php');
require_once(__ROOT__.'/app/views/components/manage-tables.php');

class tag_manage {
    function __construct(){
        //This is for the settings that manage tags
		add_action( 'admin_menu', array($this, 'tag_sub_menu'));
		add_action( 'admin_init', array($this, 'tag_manage') );
    }

    function tag_manage(){
		add_settings_section('tagsection', null, null, 'aarshjul-tags');
		add_settings_field('tags', 'Write your tag', array($this, 'add_tagsHTML'), 'aarshjul-tags', 'tagsection');
		register_setting('aarshjultags', 'tagdata' );
	}

	function add_tagsHTML(){ ?>
		<input type=text name=tag>
	<?php }

	//add_submenu_page( 
		//string $parent_slug, 
		//string $page_title, 
		//string $menu_title, 
		//string $capability, 
		//string $menu_slug, 
		//callable $function = '', 
		//int $position = null )
	function tag_sub_menu(){
		$page = add_submenu_page('aarshjul-settings', 'Aashjul Tags', 'Tags', 'manage_options', 'aarshjul-tags', array($this, 'manage_tagHTML'));
		add_action( "admin_print_styles-{$page}", array($this, 'aarshjul_plugin_admin_styles') );
	}

		//Use registered styles
		function aarshjul_plugin_admin_styles() {
			wp_enqueue_style( 'aarshjulStylesheet' );
			wp_enqueue_style( 'bootstrapStylesheet' );
			wp_enqueue_style( 'fontawesomeStylesheet' );
			wp_enqueue_script('bootstrapScript');
			wp_enqueue_script('jqueryScript');
		wp_enqueue_script('listScript');
		}

	//HTML when clicking into tag submenu page
	function manage_tagHTML(){
		tag_manage_table('tag', array('Name'), json_decode(aa_get_tags()));
	}
}