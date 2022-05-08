<?php

Class Tag{
    public $tagid;
    public $name;

    private $table_name;

    // constructor with $db as database connection
    public function __construct($wpdb){
        $this->wpdb = $wpdb;
        $this->table_name = "wp_aa_tag";
    }

    function create(){

        $this->wpdb->insert( 
            $this->table_name, 
            array( 
                'name' => $this->name, 
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
    
            $this->wpdb->update($this->table_name, array('name' => $data['name']), array('tagid' => $data['id']));
        }
    
        function delete($tagid){
    
            // execute query
            $this->wpdb->delete($this->table_name, array('tagid' => $tagid));
          
        }
}

