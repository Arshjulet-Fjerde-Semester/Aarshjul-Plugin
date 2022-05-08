<?php

defined( 'ABSPATH' ) || exit;

require_once(__ROOT__.'/app/database/tag/create.php');
require_once(__ROOT__.'/app/database/tag/read.php');
require_once(__ROOT__.'/app/database/tag/update.php');
require_once(__ROOT__.'/app/database/tag/delete.php');

class Tag_Manage {
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
	function manage_tagHTML(){ ?>
	<div class=wrap>
		<div class="container">
			<div class="table-wrapper">
				<div class="table-title">
					<div class="row">
						<div class="col-sm-6">
							<h2>Manage <b>Tags </b></h2>
						</div>
						<div class="col-sm-6">
							<a href="#addTagModal" class="btn btn-success" data-toggle="modal"><i
									class="material-icons">&#xE147;</i> <span>Add New Tag</span></a>
							<a href="#deleteTagModal" class="btn btn-danger" data-toggle="modal"><i
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
							<th>Name</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach (json_decode(aa_get_tags()) as $row){ ?>
						<tr>
							<td>
								<span class="custom-checkbox">
									<input type="checkbox" id="checkbox1" name="options[]" value="1">
									<label for="checkbox1"></label>
								</span>
							</td>
							<td><?php echo $row->name ?></td>
							<td>
								<a href="#editTagModal" class="edit"
									onclick="document.getElementById('editid').value='<?php echo $row->tagid ?>';document.getElementById('edittag').value='<?php echo $row->name ?>';document.getElementById('oldtag').value='<?php echo $row->name ?>';"
									data-toggle="modal"><i class="material-icons" data-toggle="tooltip"
										title="Edit">&#xE254;</i></a>
								<a href="#deleteTagModal" class="delete"
									onclick="document.getElementById('deleteid').value='<?php echo $row->tagid ?>';"
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
					<form action="" method="post">
						<div class="modal-header">
							<h4 class="modal-title">Add Tag</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label>Tag</label>
								<input type="text" name="name" id="tagname" required>
							</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name="type" class="btn btn-success" value="addtag">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Edit Modal HTML -->
		<div id="editTagModal" class="modal fade">
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
								<input type="text" id="edittag" class="form-control" name=tag value="" required>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" id="editid" name="id" value="">
							<input type="hidden" id="oldtag" name="oldtag" value="">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name="type" class="btn btn-info" value="updatetag">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Delete Modal HTML -->
		<div id="deleteTagModal" class="modal fade">
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
							<input type="submit" name="type" class="btn btn-danger" value="deletetag">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
}