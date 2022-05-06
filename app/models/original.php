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
    function create($tagarray){

    $this->wpdb->insert( 
        $this->table_name, 
        array( 
            'path' => $this->path, 
            'titel' => $this->titel,
            'color' => $this->color,
        )				
    );

    $originalid = $this->wpdb->insert_id;
    if(!empty($tagarray)){
        //This should add a connection between uploaded PDF and currently available tags instead
        $table_name = 'wp_aa_original_tag';
        foreach ( $tagarray as $tagid){
            $this->wpdb->insert(
                $table_name,
                array(
                    'originalid' => $originalid,
                    'tagid' => $tagid		
                )
            );
        }
    }

    }

    function read(){
  
    // select all query
    $query = "SELECT * FROM " . $this->table_name;

    // execute query
    $results = $this->wpdb->get_results($query);
  
    return $results;
    }

    function update($data){

        $this->wpdb->update($this->table_name, array('titel' => $data['titel'], 'color' => $data['color']), array('originalid' => $data['id']));
    }

    function delete($originalid){

        // execute query
        $this->wpdb->delete($this->table_name, array('originalid' => $originalid));
      
    }
}

