<?php

require_once(__ROOT__.'/app/models/sermon.php');

global $wpdb;

if($_POST['type'] == 'deletesermon'){
    $tag = new Tag($wpdb);

    $tag->delete($_POST['id']);

}