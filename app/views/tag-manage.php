<?php

defined( 'ABSPATH' ) || exit;

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

global $wpdb;

if(isset($_POST['addtag'])){
    $table_name = $wpdb->prefix . 'tag';
    $wpdb->insert(
        $table_name,
        array(
            'name' => test_input($_POST['tag'])			
        )
    );
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }