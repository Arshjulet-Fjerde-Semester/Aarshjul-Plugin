<?php

class Bibletext{

    // database connection and table name
    private $wpdb;
    private $table_name;

    // object properties
    public $bibletextid;
    public $bookref;
    public $text;
    public $unitid;

    // constructor with $db as database connection
    public function __construct($wpdb){
        $this->wpdb = $wpdb;
        $this->table_name = "wp_aa_bibletext";
    }

    // create product
    function create(){

    $this->wpdb->insert( 
        $this->table_name, 
        array( 
            'bookref' => $this->bookref, 
            'text' => $this->text,
            'unitid' => $this->unitid,
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

    function read_one($id){

        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE bibletextid='" . $id . "'";

        // execute query
        $results = $this->wpdb->get_row($query);
        
        return $results;
    }

    function update($data){

        $this->wpdb->update($this->table_name, array('bookref' => $data['bookref'], 'text' => $data['text']), array('bibletextid' => $data['id']));
    }

    function delete($bibletextid){

        $this->wpdb->delete($this->table_name, array('bibletextid' => $bibletextid));   
    }
    function add_to_sermons($sermon){
        array_push($sermons, $sermon);
    }
}