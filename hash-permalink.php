<?php
/**
Plugin Name: Hash Permalink
Plugin URI: 
Description: 
Author: https://add.sh/ 
Version: 0.1
*/

new hashPermalink();

class hashPermalink {

    function __construct() {
        add_action( 'save_post', array( $this, 'slug_save_post_callback' ), 10, 3 );
        remove_filter( "post_guid", 'esc_url' );
    }

    public function slug_save_post_callback( $post_ID, $post, $update ) {
        // allow 'publish', 'draft', 'future'
        if ($post->post_type != 'post' || $post->post_status == 'auto-draft')
            return;

        // only change slug when the post is created (both dates are equal)
        if ($post->post_date_gmt != $post->post_modified_gmt) {
            return;
        }
        // use title, since $post->post_name might have unique numbers added
    #    $new_slug = sanitize_title( $post->post_title, $post_ID );
        $date    = $post->post_date_gmt;
        $url     = esc_url( home_url() );
        $author  = $post->post_author; 
        $hash_sha1 = sha1( $url . $post_ID . $date . $author );
        $hash_md5  = md5( $url . $post_ID . $date . $author );
        $hash_sha1_short = substr( $hash_sha1, 0, 5 );
        $hash_md5_short  = substr( $hash_md5, 0, 5 );
        $slug_hash = $hash_sha1_short . $hash_md5_short;
        if (empty( $slug_hash ) || strpos( $new_slug, $slug_hash ) !== false)
            return; // No subtitle or already in slug
    
        $new_slug = $slug_hash;
        if ( $new_slug == $post->post_name ) {
            return; // already set
        }

        # Short hash
        # 1/2 collision risk
        # 0 ot F = 4bit
        # 4bit * 10words = 40bit
        # (20bit / 2) -1 = 19bit
        # 19bit = 524,288
    
        // unhook this function to prevent infinite looping
        remove_action( 'save_post', array( $this, 'slug_save_post_callback' ), 10, 3 );
        // update the post slug (WP handles unique post slug)
        wp_update_post( array(
            'ID'        => $post_ID,
            'post_name' => $new_slug,
        ) );
        global $wpdb;
        $urn = 'hashpermalink:' . $hash_sha1 . '-' . $hash_md5;
        $wpdb->update( $wpdb->posts, ['guid' =>  $urn], ['ID' => $post_ID] );
        // re-hook this function
        add_action( 'save_post', array( $this, 'slug_save_post_callback' ), 10, 3 );
    }
}
