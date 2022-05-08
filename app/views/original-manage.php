<?php

defined( 'ABSPATH' ) || exit;

require_once(__ROOT__.'/app/database/original/create.php');
require_once(__ROOT__.'/app/database/original/read.php');
require_once(__ROOT__.'/app/database/original/update.php');
require_once(__ROOT__.'/app/database/original/delete.php');
require_once(__ROOT__.'/app/views/components/manage-tables.php');

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
	function adminHTML(){ ?>
<div class=wrap>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Manage <b>Original</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a href="#addTagModal" class="btn btn-success" data-toggle="modal"><i
                                class="material-icons">&#xE147;</i> <span>Add New Original</span></a>
                        <a href="#deleteOriginalModal" class="btn btn-danger" data-toggle="modal"><i
                                class="material-icons">&#xE15C;</i> <span>Delete</span></a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="selectAll">
                                <label for="selectAll"></label>
                            </span>
                        </th>
                        <th>Title</th>
                        <th>Color</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (json_decode(getoriginal()) as $row){ print_r($row) ?>
                    <tr>
                        <td>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                <label for="checkbox1"></label>
                            </span>
                        </td>
                        <td><?php echo $row->titel ?></td>
                        <td><?php echo $row->color ?></td>
                        <td>
                            <a href="#editOriginalModal" class="edit"
                                onclick="document.getElementById('editid').value='<?php echo $row->originalid ?>';document.getElementById('edittitle').value='<?php echo $row->titel ?>';document.getElementById('editcolor').value='<?php echo $row->color ?>';document.getElementById('oldtitle').value='<?php echo $row->titel ?>';"
                                data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                    title="Edit">&#xE254;</i></a>
                            <a href="#deleteOriginalModal" class="delete"
                                onclick="document.getElementById('deleteid').value='<?php echo $row->originalid ?>';document.getElementById('filename').value='<?php echo $row->titel ?>';"
                                data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
                                    title="Delete">&#xE872;</i></a>
                        </td>
                    </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
            <div class="clearfix">
                <div class="hint-text">Showing <b>1</b> out of <b>1</b> entries</div>
                <ul class="pagination">
                    <li class="page-item disabled"><a href="#">Previous</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item active"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                    <li class="page-item"><a href="#" class="page-link">Next</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- add Modal HTML -->
    <div id="addTagModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Original</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="originaldata" accept="application/pdf" required>
                        </div>
                        <div class="form-group">
                            <select class="form-select" name="tags[]" id="tags" multiple
                                aria-label="multiple select example">
                                <?php
                                $tags = $this->wpdb->get_results( "SELECT * FROM wp_aa_tag" );
                                foreach ($tags as $tag){
                                echo "<option value='" . $tag->tagid . "'>" . $tag->name . "</options>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Color</label>
                            <input type="color" name="color" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" name="type" class="btn btn-success" value="add">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="editOriginalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Original</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Titel</label>
                            <input type="text" id="edittitle" class="form-control" name=title value="" required>
                        </div>
                        <div class="form-group">
                            <label>Color</label>
                            <input type="color" id="editcolor" name="color" class="form-control" value="" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="editid" name="id" value="">
                        <input type="hidden" id="oldtitle" name="oldtitle" value="">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" name="type" class="btn btn-info" value="update">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal HTML -->
    <div id="deleteOriginalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Original</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete these Records?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="deleteid" name="id" value="">
                        <input type="hidden" id="filename" name="filename" value="">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" name="type" class="btn btn-danger" value="delete">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
	}
}