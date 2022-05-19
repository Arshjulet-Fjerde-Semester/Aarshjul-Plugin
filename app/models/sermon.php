<?php

class Sermon{
    public $sermonid;
    public $title;
    public $author;
    public $year;
    public $path;
    public $bibletextid;

    private $table_name;

    // constructor with $db as database connection
    public function __construct($wpdb){
        $this->wpdb = $wpdb;
        $this->table_name = "wp_aa_sermon";
    }

    function create(){

        $this->wpdb->insert( 
            $this->table_name, 
            array( 
                'titel' => $this->title, 
                'author' => $this->author,
                'year' => $this->year,
                'path' => $this->path,
                'bibletextid' => $this->bibletextid,
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
    
        function update($data){
    
            $this->wpdb->update($this->table_name, array('titel' => $data['title'], 'author' => $data['author'], 'path' => $data['path']  ), array('sermonid' => $data['id']));
        }
    
        function delete($sermonid){
    
            // execute query
            $this->wpdb->delete($this->table_name, array('sermonid' => $sermonid));
          
        }
}