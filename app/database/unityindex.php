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
    // $units = $wpdb->get_results("SELECT * FROM wp_aa_unit 
    // JOIN wp_aa_bibletext using (unitid)
    // LEFT JOIN wp_aa_sermon using (bibletextid)
    // ORDER BY unitid");

    $units = $wpdb->get_results("SELECT * FROM wp_aa_unit");

    $bibletexts = $wpdb->get_results("SELECT * FROM wp_aa_bibletext");

    $sermons = $wpdb->get_results("SELECT * FROM wp_aa_sermon");

    $newunits = array();

    // print_r($units);
    // print_r($bibletext);
    // print_r($sermons);

    foreach( $units as $unit){
        $newunit = new Unit($unit->unitid, $unit->name, $unit->color);
        foreach( $bibletexts as $bibletext){
            if($bibletext->unitid == $unit->unitid){
                $newbibletext = new BibleText2($bibletext->bibletextid, $bibletext->bookref, $bibletext->text, $bibletext->unitid);
                foreach( $sermons as $sermon){
                    if($sermon->bibletextid == $bibletext->bibletextid){
                        $newsermon = new Sermon2($sermon->sermonid, $sermon->titel, $sermon->author, $sermon->year, $sermon->path, $sermon->bibletextid);
                        $newbibletext->add_to_sermons($newsermon);
                    }
                }
                $newunit->add_to_bibletexts($newbibletext);
            }
        }
        array_push($newunits, $newunit);
    }

    //print_r($newunits);
    // $oldunitid = 0;
    // $oldunit;
    // $unitarray = array();
    // foreach($units as $unit){
    //     if($unit['unitid'] == $oldunitid){

    //     }
    //     else{
    //         $newunit = new Unit($unit['unitid'], $unit['name'], $unit['color']);
    //         $newbibletext = new Bibletext($wpdb);
    //         $newbibletext->bibletextid = $unit['bibletextid'];
    //         $newbibletext->bookref = $unit['bookref'];
    //         $newbibletext->text = $unit['text'];
    //         $newunit->add_to_bibletexts($newbibletext);
    //         array_push($unitarray, $newunit);
    //     }
    // }

    http_response_code(200);

    $results = json_encode($newunits);

    print_r($results);
}

class Unit{
    public $unitid;
    public $name;
    public $color;
    public $bibletexts = array();

    function __construct($unitid, $name, $color){
        $this->unitid = $unitid;
        $this->name = $name;
        $this->color = $color;
    }

    function add_to_bibletexts($bibletext){
        array_push($this->bibletexts, $bibletext);
    }
}
class BibleText2{
    public $bibletextid;
    public $bookref;
    public $text;
    public $sermons = array();
    public $unitid;

    function __construct($bibletextid, $bookref, $text, $unitid){
        $this->bibletextid = $bibletextid;
        $this->bookref = $bookref;
        $this->text = $text;
        $this->unitid = $unitid;
    }

    function add_to_sermons($sermon){
        array_push($this->sermons, $sermon);
    }
}
class Sermon2{
    public $sermonid;
    public $titel;
    public $author;
    public $year;
    public $path;
    public $bibletextid;

    function __construct($sermonid, $titel, $author, $year, $path, $bibletextid){
        $this->sermonid = $sermonid;
        $this->titel = $titel;
        $this->author = $author;
        $this->year = $year;
        $this->path = $path;
        $this->bibletextid = $bibletextid;
    }
}
?>