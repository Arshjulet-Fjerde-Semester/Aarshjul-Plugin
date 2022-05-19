<?php

require_once(__ROOT__.'/app/models/sermon.php');
global $wpdb;

if($_POST['type'] == 'addsermon'){

    $target_dir = '../wp-content/plugins/Aarshjul-plugin/app/uploads/';
		$target_file = $target_dir . basename($_FILES["sermondata"]["name"]);
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
			if (move_uploaded_file($_FILES["sermondata"]["tmp_name"], $target_file)) {

                $sermon = new Sermon($wpdb);

                $sermon->title = $_FILES["sermondata"]["name"];
                $sermon->author = $_POST['author'];
				$sermon->year = $_POST['year'];
                $sermon->path = $target_dir;
                $sermon->bibletextid = $_POST['bibletextid'];

                $sermon->create();

			} else {
			echo "Sorry, there was an error uploading your file.";
			}
		}
    
}