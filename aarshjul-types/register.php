<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function register_aarshjul_type() {

	$labels = array(
		'name' => __( 'Aarshjul', DOMAIN ),
		'singular_name' => __( 'Aarshjul', DOMAIN ),
        'add_new' => __('Nyt Aarshjul', DOMAIN),
        'add_new_item' => __('Nyt Aarshjul', DOMAIN),
        'edit_item' => __('Rediger Aarshjul', DOMAIN),
        'new_item' => __('Nyt Aarshjul', DOMAIN),
        'view_item' => __('Se Aarshjul', DOMAIN),
	); //For More Labels see: https://developer.wordpress.org/reference/functions/get_post_type_labels/

	$args = array( 
		'label'               => __( 'Animated Live Wall', 'animated-live-wall' ),
				'description'         => __( 'Custom Post Type For Animated Live Wall', 'animated-live-wall' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-layout',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
	); //For more Args see: https://developer.wordpress.org/reference/functions/register_post_type/

	register_post_type( 'aarshjul', $args );
}