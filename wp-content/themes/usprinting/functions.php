<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',        get_stylesheet_directory_uri() . '/style.css',        array('parent-style')    );
    wp_enqueue_style( 'font-awesome',        get_stylesheet_directory_uri() . '/css/font-awesome.min.css');
	wp_enqueue_style( 'custom-child-style',        get_stylesheet_directory_uri() . '/css/custom-style.css');
  	wp_enqueue_script( 'usprinting-theme-js',        get_stylesheet_directory_uri() . '/js/custom.js',array('jquery'));

}

add_action( 'widgets_init', 'usprinting_widgets_init' );
function usprinting_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar Top', 'usprinting' ),
		'id'            => 'sidebar-top',
		'description'   => __( 'Add widgets here to appear in your top sidebar.', 'usprinting' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer 3', 'usprinting' ),
		'id'            => 'footer-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'usprinting' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 4', 'usprinting' ),
		'id'            => 'footer-4',
		'description'   => __( 'Add widgets here to appear in your footer.', 'usprinting' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

	remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_title',5);
	remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_price',10);
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	add_action('woocommerce_before_single_product_summary','woocommerce_template_single_title',15);
	
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_additional_tab', 98 );
		function wcs_woo_remove_additional_tab($tabs) {
		unset($tabs['additional_information']);
		return $tabs;
		}
	/* add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
		function wcs_woo_remove_reviews_tab($tabs) {
		unset($tabs['reviews']);
		return $tabs;
	} */
	add_action( 'after_setup_theme', 'woocommerce_support' );
	function woocommerce_support() {
		add_theme_support( 'woocommerce' );
	}
	function wc_remove_related_products( $args ) {
		return array();
	}
	add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);

	/** Product page, wrap title and product in same div **/
	add_action( 'cd_before_single_product_title', 'cd_before_single_product_title' );
	add_action( 'cd_after_single_product_image', 'cd_after_single_product_image' );
	function cd_before_single_product_title() {
		echo '<div class="cd-product-image-wrapper">';
	// Your code
	}
	function cd_after_single_product_image() {
		echo '</div>';
	// Your code
	}
	add_action('woocommerce_before_single_product_summary','cd_before_single_product_title',1);
	add_action('woocommerce_before_single_product_summary','cd_after_single_product_image',200);


	add_filter( 'login_url', 'my_login_page', 10, 3 );
    function my_login_page( $login_url, $redirect, $force_reauth ) {
    return home_url( '/login' );
    }

    function get_child_categories($atts){
	    $args = array(
       'hierarchical' => 1,
       'show_option_none' => '',
       'hide_empty' => 0,
       'parent' => $atts['id'],
       'taxonomy' => 'product_cat'
       );
      $subcats = get_categories($args);
      echo '<div class="subcategory_our_products">';
      foreach ($subcats as $sc) {
          $link = get_term_link( $sc->slug, $sc->taxonomy );
          $category_thumbnail = get_woocommerce_term_meta($sc->term_id, 'thumbnail_id', true);
          $image = wp_get_attachment_image_src($category_thumbnail,$size = 'full');
          echo '<div class="subcategory_inner">';
		  echo '<a href="'. $link .'"><img src="'.$image['0'].'"></a>';
		  echo '<a href="'. $link .'">'.$sc->name.'</a>';
          echo '<p>'.wp_trim_words($sc->category_description, 35,'...').'</p>';
          echo '</div>';
      }
    echo '</div>';
	}
	add_shortcode('get_child_categories','get_child_categories');


	//add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );

	/**
	 * woo_custom_product_searchform
	 *
	 * @access      public
	 * @since       1.0
	 * @return      void
	*/
	/*function woo_custom_product_searchform( $form ) {

		$form = '
		 <div class="search-bar">
		 <div id="sb-search" class="sb-search">
		 <form role="search" method="get"  action="' . esc_url( home_url( '/'  ) ) . '">
			    <input type="text" value="" name="s" class="sb-search-input" placeholder="' . __( 'Enter your search term...', 'woocommerce' ) . '" />
				<input type="submit" class="sb-search-input" value="" />
				<input type="hidden" name="post_type" value="product" />
				<span class="sb-icon-search"></span>
		</form>
		</div>
		</div>';

		return $form;

	}*/
	
	
	/** Add woocommerce Tabs **/
		/* Add this to your theme's functions.php to move the variation description display to a different location
		 * on the product page.
		 */
		 
		 add_action('plugins_loaded', 'move_variation_description', 50);
		 
		function move_variation_description(){ 
		global $woocommerce;
		remove_action( 'woocommerce_single_product_summary', array($woocommerce->frontend,'add_variation_description'), 25 );
		  // Remove the hook from the original location
		  //remove_action( 'woocommerce_single_product_summary', array( WC_Variation_Description::get_instance()->frontend, 'add_variation_description' ), 25 );
		  // Re-add the hook to where you want it to be
		  //add_action( 'woocommerce_before_single_product_summary',  array( WC_Variation_Description::get_instance()->frontend, 'add_variation_description' ), 25 );
		}