<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

/**
 * Initialize theme default settings
 */
require get_template_directory() . '/inc/theme-settings.php';

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/pagination.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Comments file.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/inc/bootstrap-wp-navwalker.php';

/**
 * Load WooCommerce functions.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Load Editor functions.
 */
require get_template_directory() . '/inc/editor.php';

add_image_size( 'carldetorres-grid-image', 250, 250 );


function create_post_type() {
	
	$labels = array(
		'name' => 'Clients',
		'singular_name' => 'Client',
		'menu_name' => 'Clients',
		'add_new_item' => 'Add New Client',
		'edit_item' => 'Edit Client'		
	);


	register_post_type( 'cdgd_client',
    	array(
      		'labels' => $labels,
      		'public' => false,
      		'publicly_queryable' => true,
      		'capability_type' => 'post',
      		'show_ui' => true,
      		'has_archive' => true,
      		'menu_icon' => 'dashicons-businessman',
      		'supports' => array('title')
    	)
  	);
}
add_action( 'init', 'create_post_type' );

// remove the admin toolbar from the front-facing site
add_filter('show_admin_bar', '__return_false');
