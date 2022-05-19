<?php

require_once(__ROOT__.'/app/models/sermon.php');

global $wpdb;

if($_POST['type'] == 'updatesermon'){
    $tag = new Tag($wpdb);
    $tag->update($_POST);

}