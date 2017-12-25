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


if ( ! function_exists('write_log')) {
 function write_log ( $log )  {
    if ( is_array( $log ) || is_object( $log ) ) {
       error_log( print_r( $log, true ) );
   } else {
       error_log( $log );
   }
}
}

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


// *** Custom columns for the client post type *** //

add_filter("manage_media_columns", "set_custom_edit_client_columns");
add_filter("manage_users_columns", "set_custom_edit_client_columns");
function set_custom_edit_client_columns($columns) {
    $columns['client'] = 'Client';
    return $columns;
}

add_action( 'manage_media_custom_column' , 'custom_client_column', 10, 2 );
function custom_client_column( $column, $post_id ) {

    $client_id = get_post_meta($post_id, 'cdgd_client', true);  
    if ( ! empty( $client_id ) ) {
        echo get_the_title($client_id);
    }

}

add_filter( 'manage_users_custom_column', 'custom_client_column_for_users', 10, 3 );
function custom_client_column_for_users( $val, $column_name, $user_id ) {
    $client_id = get_the_author_meta( 'cdgd_client', $user_id );
    if ( ! empty( $client_id ) ) {
        return get_the_title($client_id);
    }

    return '';
}

add_action('admin_head', 'cdgd_custom_admin_css');
function cdgd_custom_admin_css() {
    echo '<style>
    .column-client {width: 10%}
    </style>';
}

add_filter( 'manage_upload_sortable_columns', 'sortable_client_column' );
add_filter( 'manage_users_sortable_columns', 'sortable_client_column' );
function sortable_client_column( $columns ) {
    $columns['client'] = 'client';
    return $columns;
}

add_filter( 'posts_clauses', 'client_order_by_posts_clauses', 1, 2 );
function client_order_by_posts_clauses($pieces, $query) {

  // source in $wpdb for custom queries
  global $wpdb;

  // only run in the main wp query and if orderby is present
  if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

    // Get order from the query
    $order = strtoupper( $query->get( 'order' ) );

    if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
        $order = 'ASC';
    }

    switch ( $orderby ) {
        case 'client':
        // join by the postmeta to find links between images and the id of clients
        $pieces[ 'join' ] .= " LEFT JOIN $wpdb->postmeta pm on pm.meta_key = 'cdgd_client' AND pm.post_id = {$wpdb->posts}.id ";
        // join by the posts table again to find links between the client id and its custom post
        $pieces[ 'join' ] .= " LEFT JOIN $wpdb->posts cl on pm.meta_value = cl.id ";
        // order by the joined post_title of the client post
        $pieces[ 'orderby' ] = " cl.post_title $order, " . $pieces[ 'orderby' ];

        break;
    }

}

return $pieces;

}

add_action('pre_user_query','client_pre_user_query');
function client_pre_user_query($user_search) {

    global $wpdb,$current_screen;

    if ( 'users' != $current_screen->id ) {
        return;
    }        

    $vars = $user_search->query_vars;

    switch ( $vars['orderby'] ) {

        case 'client':
            // join usermeta to get the meta row based on user id
            $user_search->query_from .= " LEFT JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='cdgd_client') "; 
            $user_search->query_from .= " LEFT JOIN $wpdb->posts cl on m1.meta_value = cl.id "; 
            $user_search->query_orderby = ' ORDER BY cl.post_title ' . $vars['order'];
        break;

    }

}


// *** ************************************** *** //