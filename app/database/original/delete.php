<?php

require_once(__ROOT__.'/app/models/original.php');

global $wpdb;

if($_POST['type'] == 'delete'){
 $wpdb->delete();
}