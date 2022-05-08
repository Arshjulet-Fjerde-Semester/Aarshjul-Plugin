<?php

defined( 'ABSPATH' ) || exit;

require_once(__ROOT__.'/app/database/sermon/create.php');
require_once(__ROOT__.'/app/database/sermon/read.php');
require_once(__ROOT__.'/app/database/sermon/update.php');
require_once(__ROOT__.'/app/database/sermon/delete.php');

require_once(__ROOT__.'/app/database/original/read.php');


class Sermon_Manage {

    private $wpdb;

    function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;
        //This is for the settings that manage sermons
		add_action( 'admin_menu', array($this, 'sermon_sub_menu'));
    }

	//add_submenu_page( 
		//string $parent_slug, 
		//string $page_title, 
		//string $menu_title, 
		//string $capability, 
		//string $menu_slug, 
		//callable $function = '', 
		//int $position = null )
	function sermon_sub_menu(){
		$page = add_submenu_page('aarshjul-settings', 'Aarshjul Sermons', 'Sermons', 'manage_options', 'aarshjul-sermons', array($this, 'manage_sermonHTML'));
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

	//HTML when clicking into sermon submenu page
	function manage_sermonHTML(){ ?>
	<div class=wrap>
		<div class="container">
			<div class="table-wrapper">
				<div class="table-title">
					<div class="row">
						<div class="col-sm-6">
							<h2>Manage <b>Sermons </b></h2>
						</div>
						<div class="col-sm-6">
							<a href="#addSermonModal" class="btn btn-success" data-toggle="modal"><i
									class="material-icons">&#xE147;</i> <span>Add New Sermon</span></a>
							<a href="#deleteSermonModal" class="btn btn-danger" data-toggle="modal"><i
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
                            <th>Author</th>
                            <th>Associated Original</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach (json_decode(aa_get_sermons()) as $row){ ?>
						<tr>
							<td>
								<span class="custom-checkbox">
									<input type="checkbox" id="checkbox1" name="options[]" value="1">
									<label for="checkbox1"></label>
								</span>
							</td>
							<td><?php echo $row->titel ?></td>
                            <td><?php echo $row->author ?></td>
                            <td><?php echo json_decode(get_one_original($row->originalid))->titel ?></td>
							<td>
								<a href="#editSermonModal" class="edit"
									onclick="document.getElementById('editid').value='<?php echo $row->sermonid ?>';document.getElementById('editsermon').value='<?php echo $row->name ?>';document.getElementById('oldsermon').value='<?php echo $row->name ?>';"
									data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
										title="Edit">&#xE254;</i></a>
								<a href="#deleteSermonModal" class="delete"
									onclick="document.getElementById('deleteid').value='<?php echo $row->sermonid ?>';"
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
		<div id="addSermonModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="" method="post" enctype="multipart/form-data">
						<div class="modal-header">
							<h4 class="modal-title">Add Sermon</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label>Sermon PDF</label>
								<input type="file" name="sermondata" required>
							</div>
						</div>
                        <div class="form-group">
                            <select class="form-select" name="originalid" id="originals">
                                <?php
                                $originals = $this->wpdb->get_results( "SELECT * FROM wp_aa_original" );
                                foreach ($originals as $original){
                                    print_r($original);
                                echo "<option value='" . $original->originalid . "'>" . $original->titel . "</options>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="modal-body">
							<div class="form-group">
								<label>Author</label>
								<input type="text" name="author" required>
							</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name="type" class="btn btn-success" value="addsermon">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Edit Modal HTML -->
		<div id="editSermonModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="" method="post">
						<div class="modal-header">
							<h4 class="modal-title">Edit Tag</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label>New Tag</label>
								<input type="text" id="editsermon" class="form-control" name=sermon value="" required>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" id="editid" name="id" value="">
							<input type="hidden" id="oldsermon" name="oldsermon" value="">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name="type" class="btn btn-info" value="updatesermon">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Delete Modal HTML -->
		<div id="deleteSermonModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="" method="post">
						<div class="modal-header">
							<h4 class="modal-title">Delete Tag</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to delete these Records?</p>
							<p class="text-warning"><small>This action cannot be undone.</small></p>
						</div>
						<div class="modal-footer">
							<input type="hidden" id="deleteid" name="id" value="">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name="type" class="btn btn-danger" value="deletesermon">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
}