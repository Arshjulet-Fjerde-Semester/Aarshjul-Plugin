<?php

require_once(__ROOT__.'/app/models/original.php');

global $wpdb;

if($_POST['type'] == 'delete'){
    print_r($_POST);
    $original = new Original($wpdb);

    $original->delete($_POST['id']);

    unlink(__ROOT__ . '/app/uploads/' .$_POST['filename']);
}