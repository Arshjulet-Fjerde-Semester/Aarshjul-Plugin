<?php

class Sermon{
    public $sermonid;
    public $path;
    public $title;
    public $author;
    public $originalid;

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
                'path' => $this->path, 
                'titel' => $this->title, 
                'author' => $this->author,
                'originalid' => $this->originalid,
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
    
            $this->wpdb->update($this->table_name, array('path' => $data['path'], 'titel' => $data['title'], 'author' => $data['author'],), array('sermonid' => $data['id']));
        }
    
        function delete($sermonid){
    
            // execute query
            $this->wpdb->delete($this->table_name, array('sermonid' => $sermonid));
          
        }
}