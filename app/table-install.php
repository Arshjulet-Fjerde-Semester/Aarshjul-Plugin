<?php

defined( 'ABSPATH' ) || exit;

function aarshjul_table_install(){
    global $wpdb;

    $table_original = $wpdb->prefix . "aa_" . "Original";
    $table_tag = $wpdb->prefix . "aa_" . "Tag";
    $table_original_tag = $wpdb->prefix . "aa_" . "Original_Tag";
    $table_sermon = $wpdb->prefix . "aa_" . "Sermon";
    $table_level = $wpdb->prefix . "aa_" . "Level";

    $charset_collate = $wpdb->get_charset_collate();

    $sql_original = "CREATE TABLE $table_original (
        originalid mediumint(9) NOT NULL AUTO_INCREMENT,
        path nvarchar(255) NOT NULL,
        titel nvarchar(255) NOT NULL,
        color nvarchar(255) NOT NULL,
        PRIMARY KEY  (originalid)
    ) $charset_collate;";

    $sql_sermon = "CREATE TABLE $table_sermon (
        sermonid mediumint(9) NOT NULL AUTO_INCREMENT,
        path nvarchar(255) NOT NULL,
        titel nvarchar(255) NOT NULL,
        author nvarchar(255) NOT NULL,
        originalid mediumint(9) NOT NULL,
        FOREIGN KEY  (originalid) REFERENCES $table_original(originalid),
        PRIMARY KEY  (sermonid)
    ) $charset_collate;";

    $sql_tag = "CREATE TABLE $table_tag (
        tagid mediumint(9) NOT NULL AUTO_INCREMENT,
        name nvarchar(255) NOT NULL,
        PRIMARY KEY  (tagid)
    ) $charset_collate;";

    $sql_level = "CREATE TABLE $table_level (
        levelid mediumint(9) NOT NULL AUTO_INCREMENT,
        layer mediumint(9) NOT NULL,
        tagid mediumint(9) NOT NULL,
        FOREIGN KEY  (tagid) REFERENCES $table_tag(tagid),
        PRIMARY KEY  (levelid)
    ) $charset_collate;";

    $sql_original_tag = "CREATE TABLE $table_original_tag (
        original_tagid mediumint(9) NOT NULL AUTO_INCREMENT,
        originalid mediumint(9) NOT NULL,
        tagid mediumint(9) NOT NULL,
        FOREIGN KEY  (originalid) REFERENCES $table_original(originalid),
        FOREIGN KEY  (tagid) REFERENCES $table_tag(tagid),
        PRIMARY KEY  (original_tagid)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_original );
    dbDelta( $sql_tag );
    dbDelta( $sql_original_tag );
    dbdelta( $sql_sermon );
    dbdelta( $sql_level );

    add_option( "aarshjul_db_version", "1.0" );

    get_bible_data();
}

function get_bible_data(){
    
}