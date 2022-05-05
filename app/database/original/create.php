<?php

require_once(__ROOT__.'/app/models/original.php');

//Handles upload of PDF to the file explorer and saves to the database the path and filename
global $wpdb;
if(empty($_POST['type'])){
	$_POST['type'] = 'null';
}
    if($_POST['type'] == 'add'){
		$target_dir = '../wp-content/plugins/Aarshjul-plugin/app/uploads/';
		print_r($target_dir);
		$target_file = $target_dir . basename($_FILES["originaldata"]["name"]);
		print_r($target_file);
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
			if (move_uploaded_file($_FILES["originaldata"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["originaldata"]["name"])). " has been uploaded.";
				echo "We should either call media_handle_upload from wordpress core here, or we should create our own table in the database using https://codex.wordpress.org/Creating_Tables_with_Plugins<br>";
				echo "For now the files gets uploaded to the plugins own upload dir.";

                $original = new Original($wpdb);

                $original->path = $target_dir;
                $original->titel = $_FILES["originaldata"]["name"];
                $original->color = $_POST['color'];

                $original->create($_POST['tags']);

			} else {
			echo "Sorry, there was an error uploading your file.";
			}
		}
	}

    if(isset($_POST['deletepdf'])){
        
    }