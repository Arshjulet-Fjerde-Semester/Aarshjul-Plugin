<?php

require_once(__ROOT__.'/app/models/tag.php');

global $wpdb;

if($_POST['type'] == 'updatetag'){
    $tag = new Tag($wpdb);
    $tag->update($_POST);

}