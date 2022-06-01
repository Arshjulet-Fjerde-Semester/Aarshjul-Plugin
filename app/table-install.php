<?php

defined( 'ABSPATH' ) || exit;

function aarshjul_table_install(){

    global $wpdb;

    $prefix = $wpdb->prefix . "aa_";
    $table_unit = $prefix . "Unit";
    $table_tag = $prefix . "Tag";
    $table_unit_tag = $prefix . "Unit_Tag";
    $table_bibletext = $prefix . "Bibletext";
    $table_sermon = $prefix . "Sermon";

    $charset_collate = $wpdb->get_charset_collate();

    $sql_unit = "CREATE TABLE $table_unit (
        unitid mediumint(9) NOT NULL AUTO_INCREMENT,
        name nvarchar(255) NOT NULL,
        season nvarchar(255) NOT NULL,
        color nvarchar(255) NOT NULL,
        PRIMARY KEY  (unitid)
    ) $charset_collate;";

    $sql_bibletext = "CREATE TABLE $table_bibletext (
        bibletextid mediumint(9) NOT NULL AUTO_INCREMENT,
        bookref nvarchar(255) NOT NULL,
        text longtext NOT NULL,
        unitid mediumint(9) NOT NULL,
        FOREIGN KEY  (unitid) REFERENCES $table_unit(unitid),
        PRIMARY KEY  (bibletextid)
    ) $charset_collate;";

    $sql_sermon = "CREATE TABLE $table_sermon (
        sermonid mediumint(9) NOT NULL AUTO_INCREMENT,
        titel nvarchar(255) NOT NULL,
        author nvarchar(255) NOT NULL,
        year nvarchar(255) NOT NULL,
        path nvarchar(255) NOT NULL,
        bibletextid mediumint(9) NOT NULL,
        FOREIGN KEY  (bibletextid) REFERENCES $table_bibletext(bibletextid),
        PRIMARY KEY  (sermonid)
    ) $charset_collate;";

    $sql_tag = "CREATE TABLE $table_tag (
        tagid mediumint(9) NOT NULL AUTO_INCREMENT,
        name nvarchar(255) NOT NULL,
        PRIMARY KEY  (tagid)
    ) $charset_collate;";

    $sql_unit_tag = "CREATE TABLE $table_unit_tag (
        unit_tagid mediumint(9) NOT NULL AUTO_INCREMENT,
        unitid mediumint(9) NOT NULL,
        tagid mediumint(9) NOT NULL,
        FOREIGN KEY  (unitid) REFERENCES $table_unit(unitid),
        FOREIGN KEY  (tagid) REFERENCES $table_tag(tagid),
        PRIMARY KEY  (unit_tagid)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_unit );
    dbdelta( $sql_bibletext );
    dbdelta( $sql_sermon );
    dbDelta( $sql_tag );
    dbDelta( $sql_unit_tag );

    add_option( "aarshjul_db_version", "1.0" );

    add_units_to_database();
}

function add_units_to_database(){
    $units = array(
        array("name"=>"Nytårsdag (Luk 2,21-21)","season"=>"Fest (jul)","color"=>"#F4EFE4"),
        array("name"=>"Søndag efter Nytår (Matt 2,1-12)","season"=>"Fest (jul)","color"=>"#F4EFE4"),
        array("name"=>"Helligtrekongers søndag (Matt 2,1-12)","season"=>"Fest (jul)","color"=>"#F4EFE4"),
        array("name"=>"1. søndag efter helligtrekonger (Luk 2,41-52);(Mark 10,13-16)","season"=>"Efterfest (jul)","color"=>"#B1D682"),
        array("name"=>"2. søndag efter helligtrekonger (Johs 2,1-11)","season"=>"Efterfest (jul)","color"=>"#B1D682"),
        array("name"=>"3. søndag efter helligtrekonger (Matt 8,1-13)","season"=>"Efterfest (jul)","color"=>"#B1D682"),
        array("name"=>"4. søndag efter helligtrekonger (Matt 8,23-27)","season"=>"Efterfest (jul)","color"=>"#B1D682"),
        array("name"=>"5. søndag efter helligtrekonger (Matt 13,24-30);(Matt 13,44-52)","season"=>"Efterfest (jul)","color"=>"#B1D682"),
        array("name"=>"6. søndag efter helligtrekonger (Matt 17,1-9)","season"=>"Efterfest (jul)","color"=>"#B1D682"),
        array("name"=>"Sidste søndag efter helligtrekonger (Matt 17,1-9)","season"=>"Efterfest (jul)","color"=>"#B1D682"),
        array("name"=>"Søndag septuagesima (Matt 20,1-16)","season"=>"Forfest (påske)","color"=>"#7834E0"),
        array("name"=>"Søndag sexsagesima (Mark 4,1-20)","season"=>"Forfest (påske)","color"=>"#7834E0"),
        array("name"=>"Fastelavns søndag (Matt 3,13-17)","season"=>"Forfest (påske)","color"=>"#7834E0"),
        array("name"=>"1. søndag i fasten (Matt 4,1-11)","season"=>"Forfest (påske)","color"=>"#7834E0"),
        array("name"=>"2. søndag i fasten (Matt 15,21-28)","season"=>"Forfest (påske)","color"=>"#7834E0"),
        array("name"=>"3. søndag i fasten (Luk 11,14-28)","season"=>"Forfest (påske)","color"=>"#7834E0"),
        array("name"=>"Midfaste (Johs 6,1-15)","season"=>"Forfest (påske)","color"=>"#7834E0"),
        array("name"=>"Mariæ bebudelse (Luk 1,26-38)","season"=>"Forfest (påske)","color"=>"#F4EFE4"),
        array("name"=>"Palmesøndag (Matt 21,1-9)","season"=>"Fest (påske)","color"=>"#7834E0"),
        array("name"=>"Skærtorsdag (Matt 26,17-30)","season"=>"Fest (påske)","color"=>"#F4EFE4"),
        array("name"=>"Langfredag (Matt 27,31-56);(Mark 15,20-39)","season"=>"Fest (påske)","color"=>"#010033"),
        array("name"=>"Påskedag (Mark 16,1-8)","season"=>"Fest (påske)","color"=>"#F4EFE4"),
        array("name"=>"Anden påskedag (Luk 24,13-35)","season"=>"Fest (påske)","color"=>"#F4EFE4"),
        array("name"=>"1. søndag efter påske (Johs 20,19-31)","season"=>"Fest (påske)","color"=>"#F4EFE4"),
        array("name"=>"2. søndag efter påske (Johs 10,11-16)","season"=>"Efterfest (påske)","color"=>"#F4EFE4"),
        array("name"=>"3. søndag efter påske (Johs 16,16-22)","season"=>"Efterfest (påske)","color"=>"#F4EFE4"),
        array("name"=>"Bededag / 4. fredag efter påske (Matt 3,1-10)","season"=>"Efterfest (påske)","color"=>"#7834E0"),
        array("name"=>"4. søndag efter påske (Johs 16,5-15)","season"=>"Forfest (pinse)","color"=>"#F4EFE4"),
        array("name"=>"5. søndag efter påske (Johs 16,23b-28)","season"=>"Forfest (pinse)","color"=>"#F4EFE4"), //Make if that finds . and trim all before DONE
        array("name"=>"Kristi himmelfart (Mark 16,14-20)","season"=>"Fest (pinse)","color"=>"#F4EFE4"),
        array("name"=>"6. søndag efter påske (Johs 15,26-16,4)","season"=>"Fest (pinse)","color"=>"#F4EFE4"),  //Expection here too
        array("name"=>"Pinsedag (Johs 14,22-31)","season"=>"Fest (pinse)","color"=>"#FA1000"),
        array("name"=>"Anden pinsedag (Johs 3,16-21)","season"=>"Fest (pinse)","color"=>"#FA1000"),
        array("name"=>"Trinitatis søndag (Johs 3,1-15)","season"=>"Efterfest (pinse)","color"=>"#F4EFE4"),
        array("name"=>"1. søndag efter trinitatis (Luk 16,19-31)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"2. søndag efter trinitatis (Luk 14,16-24)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"3. søndag efter trinitatis (Luk 15,1-10)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"4. søndag efter trinitatis (Luk 6,36-42)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"5. søndag efter trinitatis (Luk 5,1-11)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"6. søndag efter trinitatis (Matt 5,20-26)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"7. søndag efter trinitatis (Luk 19,1-10)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"8. søndag efter trinitatis (Matt 7,15-21)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"9. søndag efter trinitatis (Luk 16,1-9)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"10. søndag efter trinitatis (Luk 19,41-48)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"11. søndag efter trinitatis (Luk 18,9-14)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"12. søndag efter trinitatis (Mark 7,31-37)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"13. søndag efter trinitatis (Luk 10,23-37)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"14. søndag efter trinitatis (Luk 17,11-19)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"15. søndag efter trinitatis (Matt 6,24-34)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"16. søndag efter trinitatis (Luk 7,11-17)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"17. søndag efter trinitatis (Luk 14,1-11)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"18. søndag efter trinitatis (Matt 22,34-46)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"19. søndag efter trinitatis (Mark 2,1-12)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"20. søndag efter trinitatis (Matt 22,1-14)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"21. søndag efter trinitatis (Johs 4,46-53)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"22. søndag efter trinitatis (Matt 18,21-35)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"23. søndag efter trinitatis (Matt 22,15-22 )","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"24. søndag efter trinitatis (Matt 9,18-26)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"25. søndag efter trinitatis (Matt 24,15-28)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"26. søndag efter trinitatis (Matt 13,24-30);(Matt 13,44-52)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"27. søndag efter trinitatis (Matt 25,31-46)","season"=>"Trinitatis","color"=>"#B1D682"),
        array("name"=>"1. søndag i advent (Matt 21,1-9)","season"=>"Forfest (jul)","color"=>"#F5E8CB"),
        array("name"=>"2. søndag i advent (Luk 21,25-36)","season"=>"Forfest (jul)","color"=>"#7834E0"),
        array("name"=>"3. søndag i advent (Matt 11,2-10)","season"=>"Forfest (jul)","color"=>"#7834E0"),
        array("name"=>"4. søndag i advent (Johs 1,19-28)","season"=>"Forfest (jul)","color"=>"#7834E0"),
        array("name"=>"Juledag (Luk 2,1-14)","season"=>"Fest (jul)","color"=>"#F4EFE4"),
        array("name"=>"2. Juledag (Matt 23,34-39)","season"=>"Fest (jul)","color"=>"#FA1000"),
        array("name"=>"Julesøndag (Luk 2,25-40)","season"=>"Fest (jul)","color"=>"#F4EFE4"),
        array("name"=>"Allehelgen (Matt 5,1-12)","season"=>"Trinitatis","color"=>"#F4EFE4"),
        array("name"=>"Sidste søndag i kirkeåret (Matt 25,31-46)","season"=>"Trinitatis","color"=>"#B1D682"), 
    );
    $table_name = "wp_aa_Unit";
    
    global $wpdb;

    $books_arr = get_books();

    $bibletexts = array();
    //(
    //array ( "unit"=>"5. søndag efter helligtrekonger", ""bibleref"=>array("ref", "ref"), "texts"=>array("string", "string"))
    //)

    foreach($units as $unit){
        $name_ref = explode(' (', $unit['name']);
        $bibleref = trim($name_ref[1], ")");
        $bibleref = str_replace(");(", ";", $bibleref);
        $bibleref_split = preg_split("/[-\s;,]/", $bibleref);
        $multiple_ref = array();
        if(count($bibleref_split) > 6){
            $first_ref = array($bibleref_split[0], $bibleref_split[1], $bibleref_split[2], $bibleref_split[3]);
            $second_ref = array($bibleref_split[4], $bibleref_split[5], $bibleref_split[6], $bibleref_split[7]);
            $multiple_ref = array($first_ref, $second_ref);
        }
        else{
            $multiple_ref = array($bibleref_split);
        }
        $texts = array();
        foreach($multiple_ref as $bible_ref){
            array_push($texts, Get_x_text($bible_ref, $books_arr));
        }
        $bibleref = explode(';', $bibleref);
        $bibletext_arr = array("unit"=>$name_ref[0], "bibleref"=>$bibleref, "texts"=>$texts);
        array_push($bibletexts, $bibletext_arr);
    }

    $lastid = 0;

    foreach($units as $unit){
        $name_ref = explode(' (', $unit['name']);
        $wpdb->insert( 
            $table_name, 
            array( 
                'name' => $name_ref[0],
                'season' => $unit['season'],
                'color' => $unit['color'],
            )
        );
        $lastid = $wpdb->insert_id;
        add_bibletext_to_database($lastid, $bibletexts[$lastid-1]);
    }
}

function add_bibletext_to_database($unitid, $texts){
    //$books = array("Matthew", "Luke", "John", "Mark" );

    global $wpdb;

    $table_name = "wp_aa_Bibletext";

    for( $i = 0; count($texts['bibleref']) > $i; $i++){      
        $wpdb->insert( 
            $table_name, 
            array( 
                'bookref' => $texts['bibleref'][$i],
                'text' => $texts['texts'][$i],
                'unitid' => $unitid
            )
        );
    }
}

function get_books(){
    $books = array('Matthew', 'Luke', 'John', 'Mark');
    $books_decoded = array();
    foreach($books as $book){
        $url = "https://getbible.net/json?passage=" . $book . "&version=danish";
        $data = file_get_contents_curl($url);
        $decoded = json_decode($data);
        array_push($books_decoded, $decoded);
    }
    return $books_decoded;
}

function get_x_text($bibleref, $books_arr){
    $return_text = "";
    if(count($bibleref) != 5){
        $book = $bibleref[0];
        switch($book)
        {
            case "Matt";
                $book = $books_arr[0];
                break;
            case "Luk";
                $book = $books_arr[1];
                break;
            case "Johs";
                $book = $books_arr[2];
                break;
            case "Mark";
                $book = $books_arr[3];
                break;
        }
        $is_split_exception_done = true;
        if(str_contains($bibleref[2], "b")){
            $is_split_exception_done = false;
            $bibleref[2] = trim($bibleref[2], "b");
        }
        for( $i = $bibleref[2]; $i <= $bibleref[3]; $i++){
            if($is_split_exception_done){
                $return_text .= $book->book->{$bibleref[1]}->chapter->{strval($i)}->verse;
            }
            else{
                $text = $book->book->{$bibleref[1]}->chapter->{strval($i)}->verse;
                $return_text .= substr($text, strpos($text, '.') + 1);
                $is_split_exception_done = true;
            }
        }
    }
    else{
        $return_text = johs_special_treatment($bibleref, $books_arr);
    }
    return $return_text;
}

function johs_special_treatment($bibleref, $books_arr){
    $book = $bibleref[0];
    switch($book)
    {
        case "Matt";
            $book = $books_arr[0];
            break;
        case "Luk";
            $book = $books_arr[1];
            break;
        case "Johs";
            $book = $books_arr[2];
            break;
        case "Mark";
            $book = $books_arr[3];
            break;
    }
    $return_text = "";
    for( $i = $bibleref[2]; $i <= 27; $i++){
        $return_text .= $book->book->{$bibleref[1]}->chapter->{strval($i)}->verse;
    }
    for( $i = 1; $i <= $bibleref[4]; $i++){
        $return_text .= $book->book->{$bibleref[3]}->chapter->{strval($i)}->verse;
    }
    return $return_text;
}

function file_get_contents_curl($url) {
    $agent = 'Chromium 90.0.4';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Accept: application/json"
    )); 
    $data = curl_exec($ch);
    curl_close($ch);

    $data = trim($data, "();");

    return $data;
}