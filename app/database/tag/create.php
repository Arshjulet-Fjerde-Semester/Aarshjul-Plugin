<?php

require_once(__ROOT__.'/app/models/tag.php');
global $wpdb;

if($_POST['type'] == 'addtag'){

    $tag = new Tag($wpdb);

    $tag->name = $_POST['name'];

    $tag->create();
}