<?php

// require_once "..\wp-content\plugins\Aarshjul-Plugin\app\models\original.php";
require_once "../../models\original.php";

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

global $wpdb;

// initialize object
$original = new Original($wpdb);

// query products
$results = $original->read();
$num = $results->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // products array
    $original_arr=array();
    $original_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $results->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $original_item=array(
            "originalid" => $originalid,
            "path" => $path,
            "titel" => $titel,
            "color" => $color
        );

        // public $originalid;
        // public $path;
        // public $titel;
        // public $color;
  
        array_push($products_arr["records"], $product_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($original_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}