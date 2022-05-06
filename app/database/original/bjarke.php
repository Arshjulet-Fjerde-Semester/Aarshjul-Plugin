<?php
require( 'C:\xampp\htdocs\wordpress\wp-blog-header.php' );
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

global $wpdb;
$posts = $wpdb->get_results("SELECT titel FROM wp_aa_original");
//print_r($posts);
http_response_code(200);

echo $posts[0]->titel;
?>