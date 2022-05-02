<?php

defined( 'ABSPATH' ) || exit;

require_once "../wp-content/plugins/Aarshjul-Plugin/app/database/original/create.php";
require_once "../wp-content/plugins/Aarshjul-Plugin/app/database/original/read.php";
// require_once "../database/original/update.php";
// require_once "../database/original/delete.php";

class pdf_upload {
    
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
			$tags = $this->wpdb->get_results( "SELECT * FROM wp_tag" );
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
    </div>
		<?php
	}
}

// //Handles upload of PDF to the file explorer and saves to the database the path and filename
// global $wpdb;

//     if(isset($_POST['uploadpdf'])){
// 		$target_dir = "../wp-content/plugins/Aarshjul-plugin/app/uploads/";
// 		$target_file = $target_dir . basename($_FILES["pdfdata"]["name"]);
// 		$uploadOk = 1;
// 		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// 		// Check if file already exists
// 		if (file_exists($target_file)) {
// 		echo "Sorry, file already exists.";
// 		$uploadOk = 0;
// 		}

// 		// Allow certain file formats
// 		if($imageFileType != "pdf" ) {
// 		echo "Sorry, pdf is the only filetype allowed.";
// 		$uploadOk = 0;
// 		}

// 		// Check if $uploadOk is set to 0 by an error
// 		if ($uploadOk == 0) {
// 		echo "Sorry, your file was not uploaded.";
// 		// if everything is ok, try to upload file
// 		} 
// 		else {
// 			if (move_uploaded_file($_FILES["pdfdata"]["tmp_name"], $target_file)) {
// 				echo "The file ". htmlspecialchars( basename( $_FILES["pdfdata"]["name"])). " has been uploaded.";
// 				echo "We should either call media_handle_upload from wordpress core here, or we should create our own table in the database using https://codex.wordpress.org/Creating_Tables_with_Plugins<br>";
// 				echo "For now the files gets uploaded to the plugins own upload dir.";
// 				$table_name = $wpdb->prefix . 'pdftext';
				
// 				$wpdb->insert( 
// 					$table_name, 
// 					array( 
// 						'path' => $target_file, 
// 						'textname' => $_FILES["pdfdata"]["name"], 
// 					)				
// 				);
// 				$pdfid = $wpdb->insert_id;
// 				//This should add a connection between uploaded PDF and currently available tags instead
// 				$table_name = $wpdb->prefix . 'pdftext_tag';
// 				foreach ( $_POST['tags'] as $tagid){
// 					$wpdb->insert(
// 						$table_name,
// 						array(
// 							'pdfid' => $pdfid,
// 							'tagid' => $tagid		
// 						)
// 					);
// 				}
// 			} else {
// 			echo "Sorry, there was an error uploading your file.";
// 			}
// 		}
// 	}

//     if(isset($_POST['deletepdf'])){
        
//     }