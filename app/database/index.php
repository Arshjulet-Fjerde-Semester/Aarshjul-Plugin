<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode( '/', $uri );

// foreach( $uri as $string){
//     echo $string . "<br>";
// }

header("Content-Type: application/json");
echo json_encode($uri);