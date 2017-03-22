<?php

class ProductImage
{

    public $upload_dir;

    function __construct()
    {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $this->upload_dir = wp_upload_dir();
    }

    function pass_image( $image, $product_id ){

        $filename = $this->upload_dir['basedir'] . $image ;
        $filetype = wp_check_filetype( basename( $filename ), null );

        $attachment = array(
            'guid'           => $this->upload_dir['url'] . $image ,
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $filename, $product_id );

        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        update_post_meta($product_id, '_thumbnail_id', $attach_id);

    }

}