<?php

function manage_table($table_title, $column_array){
    ?>
		<div class=wrap>
			<div class="container">
  <div class="table-wrapper">
    <div class="table-title">
      <div class="row">
        <div class="col-sm-6">
          <h2>Manage <b><?php echo $table_title ?> </b></h2>
        </div>
        <div class="col-sm-6">
          <a href="#addOriginalModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New <?php echo $table_title ?></span></a>
          <a href="#deleteOriginalModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>
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
          <?php
            foreach($column_array as $column){
                echo "<th>"
            }
          ?>
          <th>Titel</th>
          <th>Color</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $originalarray = json_decode(getoriginal());
        foreach ($originalarray as $row){?>
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
            <a href="#editOriginalModal" class="edit" onclick="document.getElementById('id').value='<?php echo $row->originalid ?>';document.getElementById('edittitel').value='<?php echo $row->titel ?>';document.getElementById('editcolor').value='<?php echo $row->color ?>';document.getElementById('oldtitel').value='<?php echo $row->titel ?>';" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
            <a href="#deleteOriginalModal" class="delete" onclick="document.getElementById('id').value='<?php echo $row->originalid ?>';document.getElementById('filename').value='<?php echo $row->titel ?>';" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
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
<div id="addOriginalModal" class="modal fade">
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
            <select class="form-select" name="tags[]" id="tags" multiple aria-label="multiple select example">
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
          <input type="hidden" id="id" name="id" value="">
          <input type="hidden" id="oldtitel" name="oldtitel" value="">
          <div class="form-group">
            <label>Titel</label>
            <input type="text" id="edittitel" class="form-control" name=titel value="" required>
          </div>
          <div class="form-group">
            <label>Color</label>
            <input type="color" id="editcolor" name="color" class="form-control" value="" required>
          </div>
        </div>
        <div class="modal-footer">
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
          <input type="hidden" id="filename" name="filename" value="">
          <input type="hidden" id="id" name="id" value="">
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