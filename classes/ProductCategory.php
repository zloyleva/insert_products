<?php
class ProductCategory
{

    public $cat_params = [];
    public $taxonomy = 'product_cat';
    public $divider = '->';

    function __construct(){
        require_once( ABSPATH . '/wp-admin/includes/taxonomy.php' );
    }

    function send_category( $cat_string ){
        if( !is_string( $cat_string ) ){ return; }
        $cat_array = explode( $this->divider, $cat_string);

        return $this->get_category_id($cat_array);
    }

    function get_category_id($cat_array){
        $parent_id = 0;
        $current_item = 0;

        for ($i = 0; $i < count($cat_array); $i++){

            $check_item = term_exists( $cat_array[$i], $this->taxonomy, $parent_id );

            if($check_item):
                // 'Category is exists<br>';
                $parent_id = $check_item['term_id'];
            else:
                // Category isn't exists. Created new.
                $this->cat_params = array(
                    'cat_name' => $cat_array[$i],           // Taxonomy name
                    'category_parent' => $parent_id,        // ID parent category
                    'taxonomy' => $this->taxonomy                 // Taxonomy type
                );

                $parent_id = wp_insert_category( $this->cat_params, true );
            endif;

            $current_item = $parent_id;
        }

        //Return ID current category
        return $current_item;
    }
}