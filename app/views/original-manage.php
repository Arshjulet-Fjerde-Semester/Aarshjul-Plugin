<?php

defined( 'ABSPATH' ) || exit;

require_once(__ROOT__.'/app/database/original/create.php');
require_once(__ROOT__.'/app/database/original/read.php');
require_once(__ROOT__.'/app/database/original/update.php');
require_once(__ROOT__.'/app/database/original/delete.php');
require_once(__ROOT__.'/app/views/components/manage-table.php');

class Original_Manage {
    
    private $wpdb;

    function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;
        //This is for the settings that manage pdf
		    add_action( 'admin_menu', array($this, 'menu_page') );
		    add_action( 'admin_init', array($this, 'pdf_upload') );
        	add_action( 'admin_init', array($this, 'pdf_delete') );
    }
	
    //https://developer.wordpress.org/reference/functions/add_settings_field/
	//https://developer.wordpress.org/reference/functions/register_setting/
	function pdf_upload(){
		add_settings_section('pdfsection', null, null, 'aarshjul-settings');
		add_settings_field('pdfdata', 'Upload PDF', array($this, 'dataHTML'), 'aarshjul-settings', 'pdfsection');
		add_settings_field('pdftags', 'Add Tags (Hold CTRL for multiple)', array($this, 'pdftagHTML'), 'aarshjul-settings', 'pdfsection');
		register_setting('aarshjuldata', 'pdfdata' );
	}

    function pdf_delete(){
        add_settings_section('showpdf', null, null, 'aarshjul-settings');
        $pdfs = $this->wpdb->get_results( "SELECT * FROM wp_pdftext" );
        foreach ($pdfs as $pdf){
            $args = array( 'label_for' => $pdf->textname, 'pdfid' => $pdf->pdfid, 'path' => $pdf->path);
            add_settings_field('pdf', $pdf->textname, array($this, 'pdflistHTML'), 'aarshjul-settings', 'showpdf', $args);          
        }
        register_setting('aarshjulpdf', 'uploadpdf' );
    }

    function pdflistHTML( array $args ){ 
        $pdfid = $args['pdfid'];
        $path = $args['path'];
        $value = $args['label_for'];
        ?>
        <input type="checkbox" id="<?php echo $pdfid ?>" name="<?php echo $pdfid ?>" value="<?php echo $value ?>">
		<?php }

function dataHTML(){ ?>
		<input type="file" name="pdfdata" accept="application/pdf">
	<?php }

	function pdftagHTML(){ ?>
		<select name="tags[]" id="tags" multiple>
			<?php
			$tags = $this->wpdb->get_results( "SELECT * FROM wp_aa_tag" );
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
		$page = add_menu_page('Aarshjul Settings', 'Aarshjul', 'manage_options', 'aarshjul-settings', array($this, 'adminHTML'));
		//action to use earlier registered styles
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

	//This is the HTML that is shown when clicking into aarshjul page
	function adminHTML(){
    //manage_table( string $table_title
    manage_table();
	}
}