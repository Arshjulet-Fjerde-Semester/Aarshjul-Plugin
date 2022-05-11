<?php
require( 'C:\xampp\htdocs\wordpress\wp-blog-header.php' );
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

global $wpdb;

//print_r($_POST);
//print_r($_GET);
//print_r($_REQUEST);

//if ($_GET['method'] == 'GetAll')
//{
//    $posts = $wpdb->get_results("SELECT titel,originalid FROM wp_aa_original");
//    http_response_code(200);

//    echo json_encode($posts);
//}
    $posts = $wpdb->get_results("SELECT titel FROM wp_aa_original WHERE originalid = " . $_GET['id']);
    //print_r($posts);
    http_response_code(200);

    echo $posts[0]->titel;

?>