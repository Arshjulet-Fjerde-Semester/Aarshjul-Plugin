<?php

require_once(__ROOT__.'/app/models/original.php');

global $wpdb;

if($_POST['type'] == 'update'){
    $original = new Original($wpdb);
    $original->update($_POST);

    rename(__ROOT__ . '/app/uploads/' .$_POST['oldtitel'], __ROOT__ . '/app/uploads/' .$_POST['titel']);
}