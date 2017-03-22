<?php get_header(); ?>

<h2>Add products</h2>

<?php
    require_once( 'classes/InsertProduct.php' );
    require_once( 'classes/ProductCategory.php' );
    require_once( 'classes/ProductImage.php' );
    require_once( 'classes/GetFile.php' );

    $new_product = new InsertProduct();
    $category_product = new ProductCategory();
    $product_image = new ProductImage();
    $price_file = new GetFile();

    $file_for_open = 'price.txt';
    $input_price = $price_file->open_file_to_read($file_for_open);

$i = 1;
    if ($input_price):
        while(($get_string = fgetcsv($input_price, 2000, ';')) !== FALSE):



            $product_cat_id = $category_product->send_category( $get_string[0] );

            $product = array(
                'name'  =>  $get_string[1],
                'short_desc'    =>  '',
                'content'   =>  $get_string[5],
                'sku'       =>  $get_string[3],
                'price'     =>  $get_string[4],
                'stock'     =>  $get_string[2]
            );

            $product_result = $new_product->pass_product( $product_cat_id, $product );

            //if product exist - add or update product image
            if ($product_result['product_id']):
                $product_image->pass_image( $get_string[6], $product_result['product_id'] );
            endif;

            echo '<script>document.getElementById("result").innerHTML = "product '. $i++ . '"</script>';

        endwhile;
    endif;

?>

<?php get_footer(); ?>
