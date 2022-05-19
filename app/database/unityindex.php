<?php

require( __DIR__ . '/../../../../../wp-blog-header.php' );
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

if($_REQUEST['method'] == 'geteverything'){
    $units = $wpdb->get_results("SELECT * FROM wp_aa_unit 
                                JOIN wp_aa_bibletext using (unitid)
                                LEFT JOIN wp_aa_sermon using (bibletextid)
                                ORDER BY unitid");
    //print_r($posts);
    http_response_code(200);

    $results = json_encode($units);

    print_r($results);
}
?>