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

    //"SELECT bibletextid, unitid, name, season, color, bookref, group_concat( text ), sermonid, titel, author, year, path FROM wp_aa_unit 
    //JOIN wp_aa_bibletext using (unitid)
    //LEFT JOIN wp_aa_sermon using (bibletextid)
    //GROUP BY unitid"

    print_r($units);
    $oldunitid = 0;
    $oldunit;
    $unitarray = array();
    foreach($units as $unit){
        if($unit['unitid'] == $oldunitid){

        }
        else{
            $newunit = new Unit($unit['unitid'], $unit['name'], $unit['color']);
            $newbibletext = new Bibletext();
            $newunit->add_to_bibletexts();
            array_push($unitarray, $newunit);
        }
    }

    http_response_code(200);

    // $results = json_encode($units);

    // print_r($results);
}

class Unit{
    public $unitid;
    public $name;
    public $color;
    public $bibletexts = array();
    public $sermons = array();

    function __construct($unitid, $name, $color){
        $this->unitid = $unitid;
        $this->name = $name;
        $this->color = $color;
    }

    function add_to_bibletexts($bibletext){
        array_push($bibletexts, $bibletext);
    }
}
?>