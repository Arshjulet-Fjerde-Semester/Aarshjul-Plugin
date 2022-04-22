<?php

defined( 'ABSPATH' ) || exit;

function aarshjul_table_install(){
    global $wpdb;

    $table_pdf = $wpdb->prefix . "PDFtext";
    $table_tag = $wpdb->prefix . "Tag";
    $table_pdftext_tag = $wpdb->prefix . "PDFtext_Tag";

    $charset_collate = $wpdb->get_charset_collate();

    $sql_pdf = "CREATE TABLE $table_pdf (
    pdfid mediumint(9) NOT NULL AUTO_INCREMENT,
    path nvarchar(255) NOT NULL,
    textname nvarchar(255) NOT NULL,
    PRIMARY KEY  (pdfid)
    ) $charset_collate;";

    $sql_tag = "CREATE TABLE $table_tag (
        tagid mediumint(9) NOT NULL AUTO_INCREMENT,
        name nvarchar(255) NOT NULL,
        PRIMARY KEY  (tagid)
    ) $charset_collate;";

    $sql_pdftext_tag = "CREATE TABLE $table_pdftext_tag (
        pdftext_tagid mediumint(9) NOT NULL AUTO_INCREMENT,
        pdfid mediumint(9) NOT NULL,
        tagid mediumint(9) NOT NULL,
        FOREIGN KEY  (pdfid) REFERENCES $table_pdf(pdfid),
        FOREIGN KEY  (tagid) REFERENCES $table_tag(tagid),
        PRIMARY KEY  (pdftext_tagid)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_pdf );
    dbDelta( $sql_tag );
    dbDelta( $sql_pdftext_tag );

    add_option( "aarshjul_db_version", "1.0" );
}