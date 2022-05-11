<?php

defined( 'ABSPATH' ) || exit;

function aarshjul_table_install(){
    global $wpdb;

    $prefix = $wpdb->prefix . "aa_";
    $table_unit = $prefix . "Unit";
    $table_tag = $prefix . "Tag";
    $table_unit_tag = $prefix . "Unit_Tag";
    $table_bibeltext = $prefix . "Bibeltext";
    $table_sermon = $prefix . "Sermon";

    $charset_collate = $wpdb->get_charset_collate();

    $sql_unit = "CREATE TABLE $table_unit (
        unitid mediumint(9) NOT NULL AUTO_INCREMENT,
        name nvarchar(255) NOT NULL,
        season nvarchar(255) NOT NULL,
        color nvarchar(255) NOT NULL,
        PRIMARY KEY  (unitid)
    ) $charset_collate;";

    $sql_bibeltext = "CREATE TABLE $table_bibeltext (
        bibeltextid mediumint(9) NOT NULL AUTO_INCREMENT,
        bookref nvarchar(255) NOT NULL,
        text nvarchar(255) NOT NULL,
        unitid mediumint(9) NOT NULL,
        FOREIGN KEY  (unitid) REFERENCES $table_unit(unitid),
        PRIMARY KEY  (bibeltextid)
    ) $charset_collate;";

    $sql_sermon = "CREATE TABLE $table_sermon (
        sermonid mediumint(9) NOT NULL AUTO_INCREMENT,
        titel nvarchar(255) NOT NULL,
        author nvarchar(255) NOT NULL,
        year nvarchar(255) NOT NULL,
        path nvarchar(255) NOT NULL,
        bibeltextid mediumint(9) NOT NULL,
        FOREIGN KEY  (bibeltextid) REFERENCES $table_bibeltext(bibeltextid),
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
    dbdelta( $sql_bibeltext );
    dbdelta( $sql_sermon );
    dbDelta( $sql_tag );
    dbDelta( $sql_unit_tag );

    add_option( "aarshjul_db_version", "1.0" );
}

function setup_bible_data(){
    
}