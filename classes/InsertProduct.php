<?php

class InsertProduct
{

    public $taxonomy = 'product_cat';
    public $post_status = 'publish';
    public $post_type = 'product';
    public $product_type_name = 'simple';
    public $product_type = 'product_type';

    function __construct()
    {

    }

    function pass_product( $product_cat_id, $product ){

        $product_id = $this->is_exist_product( $product );

        if( $product_id ):
            //Update product
            $product_id = $this->insert_product( $product, $product_id );
        else:
            //Insert product
            $product_id = $this->insert_product( $product );
        endif;

        if( $product_id ) :
            $this->set_productmeta( $product_id, $product, $product_cat_id );
        else:
            return [ 'error' => 'Error insert/update product' ];//Error insert/update product
        endif;

        return [ 'product_id' => $product_id ];
    }

    /**
     * Check exist product(by SKU)
     *
     * @param $product
     * @return result
     *
     */
    function is_exist_product( $product ){
        global $wpdb;
        $sql_find = "SELECT post_id FROM wp_postmeta WHERE meta_key='_sku' AND meta_value='{$product['sku']}'";
        $result = $wpdb->get_var( $sql_find );
        return $result;
    }

    /**
     * @param $product >> array
     * @return $post_id
     */
    function insert_product( $product, $product_id = 0 ){
        $product_args = array(
            'post_title'    =>  $product['name'],
            'post_content'  =>  $product['content'],
            'post_excerpt'  =>  $product['short_desc'],
            'post_status'   =>  $this->post_status,
            'post_type'     =>  $this->post_type
        );

        if( $product_id ){
            $product_args['ID'] = $product_id;
        }

        $post_id = wp_insert_post( $product_args );
        return $post_id;
    }

    /**
     * Update product data
     * @param $product
     * @param $product_cat_id
     * @param $product_id
     */
//    function update_product( $product, $product_cat_id, $product_id ){
//        wp_update_post( $my_post )
//    }

    /**
     * @param $post_id
     * @param $product
     */
    function set_productmeta( $post_id, $product, $product_cat_id = 0 ){
        wp_set_object_terms($post_id, $this->product_type_name, $this->product_type);
        wp_set_object_terms($post_id,(integer) $product_cat_id, $this->taxonomy);

        update_post_meta($post_id, '_visibility', 'visible');
        update_post_meta($post_id, '_stock_status', 'instock');
        update_post_meta($post_id, 'total_sales', '0');
        update_post_meta($post_id, '_downloadable', 'no');
        update_post_meta($post_id, '_virtual', 'no');
        update_post_meta($post_id, '_regular_price', $product['price']);
        update_post_meta($post_id, '_sale_price', '');
        update_post_meta($post_id, '_purchase_note', '');
        update_post_meta($post_id, '_featured', 'no');
        update_post_meta($post_id, '_weight', '');
        update_post_meta($post_id, '_length', '');
        update_post_meta($post_id, '_width', '');
        update_post_meta($post_id, '_height', '');
        update_post_meta($post_id, '_sku', $product['sku']);
        update_post_meta($post_id, '_product_attributes', array());
        update_post_meta($post_id, '_sale_price_dates_from', '');
        update_post_meta($post_id, '_sale_price_dates_to', '');
        update_post_meta($post_id, '_price', $product['price']);
        update_post_meta($post_id, '_sold_individually', '');
        update_post_meta($post_id, '_manage_stock', 'yes');
        update_post_meta($post_id, '_backorders', 'no');
        update_post_meta($post_id, '_stock', $product['stock']);

    }

}