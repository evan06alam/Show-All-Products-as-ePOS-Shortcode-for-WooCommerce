<?php
/*
   Plugin Name: Show All Products as ePOS Shortcode for WooCommerce
   Plugin URI: http://wordpress.org/extend/plugins/show-all-products-shortcode-woocommerce-epos/
   Version: 1.0
   Author: Md Mahbubul ALam Evan
   Description: An easy [all_products] shortcode to list all woocommerce products on one page.
   Text Domain: woocommerce-show-all-products-shortcode-epos
   License: GPLv3
*/
/*
 * Usage : [all_products] to display all products.
 */

add_shortcode('all_products', 'wc_show_all_products');

if (!function_exists('wc_show_all_products')) :

    add_filter( 'woocommerce_product_add_to_cart_text', 'woo_archive_custom_cart_button_text' );    // 2.1 +

    function woo_archive_custom_cart_button_text() {

        return __( '+', 'woocommerce' );

    }

    function wc_show_all_products($atts)
    {
        ?>
        <div class="container">
            <div class="row">
                    <div class="col-sm-2 col-md-2">
                      <?php
    // All product Categories with subcategory

          $taxonomy     = 'product_cat';
          $orderby      = 'id';
          $show_count   = 0;      // 1 for yes, 0 for no
          $pad_counts   = 0;      // 1 for yes, 0 for no
          $hierarchical = 1;      // 1 for yes, 0 for no
          $title        = '';
          $empty        = 1;

          $args = array(
              'taxonomy'     => $taxonomy,
              'orderby'      => $orderby,
              'show_count'   => $show_count,
              'pad_counts'   => $pad_counts,
              'hierarchical' => $hierarchical,
              'title_li'     => $title,
              'hide_empty'   => $empty
          );
          $all_categories = get_categories( $args );
          foreach ($all_categories as $cat) {
              if($cat->category_parent == 0) {
                  $category_id = $cat->term_id;
                  //echo '<br /><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a>';
                  echo '<br /><a href="'. get_site_url().'/food-menu/#'. $cat->slug .'">'. $cat->name .'</a>';

                  $args2 = array(
                      'taxonomy'     => $taxonomy,
                      'child_of'     => 0,
                      'parent'       => $category_id,
                      'orderby'      => $orderby,
                      'show_count'   => $show_count,
                      'pad_counts'   => $pad_counts,
                      'hierarchical' => $hierarchical,
                      'title_li'     => $title,
                      'hide_empty'   => $empty
                  );
                  $sub_cats = get_categories( $args2 );
                  if($sub_cats) {
                      foreach($sub_cats as $sub_category) {
                          //echo  '<br /><a href="'. get_term_link($sub_category->slug, 'product_cat') .'">&nbsp;&nbsp;|_'.$sub_category->name .'</a>';
                          echo '<br /><a href="'. get_site_url().'/food-menu/#'. $sub_category->slug .'">&nbsp;&nbsp;|_'. $sub_category->name .'</a>';
                      }
                  }
              }
          }

          // All product Categories with subcategory
          ?>
                  </div>
                    <div class="col-sm-5 col-md-5">
                        <?php

                        $taxonomy = 'product_cat';
                        $orderby = 'id';
                        $show_count = 0;      // 1 for yes, 0 for no
                        $pad_counts = 0;      // 1 for yes, 0 for no
                        $hierarchical = 1;      // 1 for yes, 0 for no
                        $title = '';
                        $empty = 0;

                        $args3 = array(
                            'taxonomy' => $taxonomy,
                            'orderby' => $orderby,
                            'show_count' => $show_count,
                            'pad_counts' => $pad_counts,
                            'hierarchical' => $hierarchical,
                            'title_li' => $title,
                            'hide_empty' => $empty
                        );
                        $all_categories = get_categories($args3);
                        foreach ($all_categories as $cat) {
                            if ($cat->category_parent == 0) {
                                $category_id = $cat->term_id;


                                $args4 = array(
                                    'post_type' => 'product',
                                    'posts_per_page' => -1,
                                    'product_cat' => $cat->slug,
                                    'post_status' => 'publish',
                                    'orderby' => 'rand'
                                );
                                $main_products = new WP_Query($args4);
                                //print_r($main_products);exit();

                                ob_start();
                                if ($main_products->have_posts()) {

                                    echo '<br /><h3 id="' . $cat->slug . '" style="color:red;  text-weight:bold;">' . $cat->name . '</h3>';
                                    ?>
                                    <table width="100%">


                                        <?php woocommerce_product_loop_start();
                                        ?>

                                        <?php while ($main_products->have_posts()) : $main_products->the_post();
                                            global $product; ?>

                                            <tr>
                                                <td style="font-size: 18px;"><?php the_title(); ?></td>
                                                <td style="font-size: 18px;"><?php echo $product->get_price_html(); ?> <?php //woocommerce_show_product_sale_flash( $main_products, $main_product ); ?></td>
                                                <td><?php woocommerce_template_loop_add_to_cart($main_products->post, $product); ?></td>
                                            </tr>


                                            <?php
                                        endwhile; // end of the loop. ?>

                                        <?php woocommerce_product_loop_end(); ?>
                                    </table>
                                <?php
                                }

                                $args5 = array(
                                    'taxonomy' => $taxonomy,
                                    'child_of' => 0,
                                    'parent' => $category_id,
                                    'orderby' => $orderby,
                                    'show_count' => $show_count,
                                    'pad_counts' => $pad_counts,
                                    'hierarchical' => $hierarchical,
                                    'title_li' => $title,
                                    'hide_empty' => $empty
                                );
                                $sub_cats = get_categories($args5);
                                if ($sub_cats) {
                                    foreach ($sub_cats as $sub_category) {


                                        $args6 = array(
                                            'post_type' => 'product',
                                            'posts_per_page' => -1,
                                            'product_cat' => $sub_category->slug,
                                            'post_status' => 'publish',
                                            'orderby' => 'rand'
                                        );
                                        $sub_products = new WP_Query($args6);

                                        ob_start();
                                        if ($sub_products->have_posts()) {
                                            echo '<br /><h4 id="' . $sub_category->slug . '" style="color:red; text-weight:bold;>' . $sub_category->name . '</h4>';
                                            ?>
                                            <table width="100%">


                                                <?php woocommerce_product_loop_start();
                                                ?>

                                                <?php while ($sub_products->have_posts()) : $sub_products->the_post();
                                                    global $product; ?>

                                                    <tr>
                                                        <td style="font-size: 18px;"><?php the_title(); ?></td>
                                                        <td style="font-size: 18px;"><?php echo $product->get_price_html(); ?> <?php //woocommerce_show_product_sale_flash( $sub_products, $sub_product ); ?></td>
                                                        <td><?php woocommerce_template_loop_add_to_cart($sub_products->post, $product); ?></td>
                                                    </tr>


                                                    <?php
                                                endwhile; // end of the loop. ?>

                                                <?php woocommerce_product_loop_end(); ?>
                                            </table>
                                        <?php
                                        }


                                    }
                                }
                            }
                        }

                        //


                        woocommerce_reset_loop();
                        wp_reset_postdata();
                        return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
                        ?>
                    </div>

                </div>
            </div>



    <?php

    }

endif;

?>
