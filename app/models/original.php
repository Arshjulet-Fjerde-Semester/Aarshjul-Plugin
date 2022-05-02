<?php

class Original{

    // database connection and table name
    private $wpdb;
    private $table_name;

    // object properties
    public $originalid;
    public $path;
    public $titel;
    public $color;

    // constructor with $db as database connection
    public function __construct($wpdb){
        $this->wpdb = $wpdb;
        $this->table_name = "wp_aa_original";
    }

    // create product
    function create(){

    $this->wpdb->insert( 
        $this->table_name, 
        array( 
            'path' => $this->path, 
            'titel' => $this->titel,
            'color' => $this->color,
        )				
    );
    }

    function read(){
  
    // select all query
    $query = "SELECT * FROM " . $this->table_name;

    // execute query
    $results = $this->wpdb->get_results($query);
  
    return $results;
    }
}

