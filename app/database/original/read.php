<?php

require_once(__ROOT__.'\app\models\original.php');

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

global $wpdb;
if(isset($_GET['getpdf'])){
    getoriginal();
}

function getoriginal(){
    global $wpdb;
    // initialize object
    $original = new Original($wpdb);
    
    // query products
    $results = $original->read();
      
    // check if more than 0 record found
    if(sizeof($results)>0){
      
        // set response code - 200 OK
        http_response_code(200);
      
        // show products data in json format
        return json_encode($results);
    }
      
    else{
      
        // set response code - 404 Not found
        http_response_code(404);
      
        // tell the user no products found
        return json_encode(array());
    }
}