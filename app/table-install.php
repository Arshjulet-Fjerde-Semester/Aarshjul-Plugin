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
        season nvarchar(255),
        color nvarchar(255),
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
        "Nytårsdag (Luk 2,21-21)",
        "søndag efter Nytår (Matt 2,1-12)",
        "Helligtrekongers søndag (Matt 2,1-12)",
        "1. søndag efter helligtrekonger (Luk 2,41-52);(Mark 10,13-16)",
        "2. søndag efter helligtrekonger (Johs 2,1-11)",
        "3. søndag efter helligtrekonger (Matt 8,1-13)",
        "4. søndag efter helligtrekonger (Matt 8,23-27)",
        "5. søndag efter helligtrekonger (Matt 13,24-30);(Matt 13,44-52)",
        "6. søndag efter helligtrekonger (Matt 17,1-9)",
        "Sidste søndag efter helligtrekonger (Matt 17,1-9)",
        "Søndag septuagesima (Matt 20,1-16)",
        "Søndag sexsagesima (Mark 4,1-20)",
        "Fastelavns søndag (Matt 3,13-17)",
        "1. søndag i fasten (Matt 4,1-11)",
        "2. søndag i fasten (Matt 15,21-28)",
        "3. søndag i fasten (Luk 11,14-28)",
        "Midfaste (Johs 6,1-15)",
        "Mariæ bebudelse (Luk 1,26-38)",
        "Palmesøndag (Matt 21,1-9)",
        "Skærtorsdag (Matt 26,17-30)",
        "Langfredag (Matt 27,31-56);(Mark 15,20-39)",
        "Påskedag (Mark 16,1-8)",
        "Anden påskedag (Luk 24,13-35)",
        "1. søndag efter påske (Johs 20,19-31)",
        "2. søndag efter påske (Johs 10,11-16)",
        "3. søndag efter påske (Johs 16,16-22)",
        "Bededag / 4. fredag efter påske (Matt 3,1-10)",
        "4. søndag efter påske (Johs 16,5-15)",
        "5. søndag efter påske (Johs 16,23b-28)", //Make if that finds . and trim all before DONE
        "Kristi himmelfart (Mark 16,14-20)",
        "6. søndag efter påske (Johs 15,26-16,4)", //Expection here too
        "Pinsedag (Johs 14,22-31)",
        "Anden pinsedag (Johs 3,16-21)",
        "Trinitatis søndag (Johs 3,1-15)",
        "1. søndag efter trinitatis (Luk 16,19-31)",
        "2. søndag efter trinitatis (Luk 14,16-24)",
        "3. søndag efter trinitatis (Luk 15,1-10)",
        "4. søndag efter trinitatis (Luk 6,36-42)",
        "5. søndag efter trinitatis (Luk 5,1-11)",
        "6. søndag efter trinitatis (Matt 5,20-26)",
        "7. søndag efter trinitatis (Luk 19,1-10)",
        "8. søndag efter trinitatis (Matt 7,15-21)",
        "9. søndag efter trinitatis (Luk 16,1-9)",
        "10. søndag efter trinitatis (Luk 19,41-48)",
        "11. søndag efter trinitatis (Luk 18,9-14)",
        "12. søndag efter trinitatis (Mark 7,31-37)",
        "13. søndag efter trinitatis (Luk 10,23-37)",
        "14. søndag efter trinitatis (Luk 17,11-19)",
        "15. søndag efter trinitatis (Matt 6,24-34)",
        "16. søndag efter trinitatis (Luk 7,11-17)",
        "17. søndag efter trinitatis (Luk 14,1-11)",
        "18. søndag efter trinitatis (Matt 22,34-46)",
        "19. søndag efter trinitatis (Mark 2,1-12)",
        "20. søndag efter trinitatis (Matt 22,1-14)",
        "21. søndag efter trinitatis (Johs 4,46-53)",
        "22. søndag efter trinitatis (Matt 18,21-35)",
        "23. søndag efter trinitatis (Matt 22,15-22 )",
        "24. søndag efter trinitatis (Matt 9,18-26)",
        "25. søndag efter trinitatis (Matt 24,15-28)",
        "26. søndag efter trinitatis (Matt 13,24-30);(Matt 13,44-52)",
        "27. søndag efter trinitatis (Matt 25,31-46)",
        "1. søndag i advent (Matt 21,1-9)",
        "2. søndag i advent (Luk 21,25-36)",
        "3. søndag i advent (Matt 11,2-10)",
        "4. søndag i advent (Johs 1,19-28)",
        "Juledag (Luk 2,1-14)",
        "2. Juledag (Matt 23,34-39)",
        "Julesøndag (Luk 2,25-40)",
        "Allehelgen (Matt 5,1-12)",
        "Sidste søndag i kirkeåret (Matt 25,31-46)",
    );
    $table_name = "wp_aa_Unit";
    
    global $wpdb;

    $books_arr = get_books();

    $bibletexts = array();
    //(
    //array ( "unit"=>"5. søndag efter helligtrekonger", ""bibleref"=>array("ref", "ref"), "texts"=>array("string", "string"))
    //)

    foreach($units as $unit){
        $name_ref = explode(' (', $unit);
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
        $name_ref = explode(' (', $unit);
        $wpdb->insert( 
            $table_name, 
            array( 
                'name' => $name_ref[0], 
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