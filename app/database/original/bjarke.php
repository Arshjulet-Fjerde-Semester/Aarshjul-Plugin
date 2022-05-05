<?php


global $wpdb;

$posts = $wpdb->get_results("SELECT titel FROM $wpdb->posts");

// Echo the title of the first scheduled post
   echo $posts[0]->titel;
?>