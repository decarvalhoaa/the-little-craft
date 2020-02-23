<?php
/**
 * The Little Craft functions.
 *
 */

/**
 * Retrieves post data given a post slug. See get_post() for details.
 *
 * @since  2.0.0
 *
 * @param string $post_slug 	Post slug. Required.
 * @param string $output 		The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which correspond
 *								to a WP_Post object, an associative array, or a numeric array, respectively.
 *								Optional. Default value: OBJECT.
 * @param string $filter 		How the return value should be filtered. Accepts 'raw', 'edit', 'db', 'display'.
 *								Optinal. Default value: 'raw'.
 * @param string $post_type 	Post type. Optinal. Default value: 'page'.
 *
 * @return WP_Post|array|null 	WP_Post (or array) on success, or null on failure.
 */
function tlc_get_post_by_slug( $post_slug, $output = OBJECT, $filter = 'raw', $post_type = 'page' ) {
    global $wpdb;

    $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $post_slug, $post_type ) );
    
    if ( $post )
            return get_post( $post, $output, $filter );
    return null;
}

/**
 * Retrieves page data given a page slug. See tlc_get_post_by_slug() for details.
 *
 * @since  2.0.0
 *
 * @param string $page_slug 	Page slug. Required.
 * @param string $output 		The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which correspond
 *								to a WP_Post object, an associative array, or a numeric array, respectively.
 *								Optional. Default value: OBJECT.
 * @param string $filter 		How the return value should be filtered. Accepts 'raw', 'edit', 'db', 'display'.
 *								Optinal. Default value: 'raw'.
 *
 * @return WP_Post|array|null 	WP_Post (or array) on success, or null on failure.
 */
function tlc_get_page_by_slug( $page_slug, $output = OBJECT, $filter = 'raw' ) {
	return tlc_get_post_by_slug( $page_slug, $output, $filter, 'page' );
}