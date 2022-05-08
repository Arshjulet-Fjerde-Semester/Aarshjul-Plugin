<?php

require_once(__ROOT__.'/app/models/tag.php');

global $wpdb;

if($_POST['type'] == 'deletetag'){
    $tag = new Tag($wpdb);

    $tag->delete($_POST['id']);

}