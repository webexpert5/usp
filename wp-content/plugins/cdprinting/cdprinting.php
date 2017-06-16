<?php
/*
Plugin Name: CodeDrill Printing
Description: Custom Printing
Version: 1.0
Author: Pankaj Sharma
License: Personal Property of USA Printing
*/
if(!class_exists('CD_Printing'))
{
	require_once('Cd_ProductConfiguration.php');
	class CD_Printing
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			
			
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate
		public static function usaPrintingForm(){
			require_once('usa-printing-template.php');
		}
		public static function display_popular_products($atts){
			 $args = array( 'post_type' => 'product', 'posts_per_page' => 15, 'product_cat' => $atts['category'] );
				$loop = new WP_Query( $args );
				ob_start();
				while ( $loop->have_posts() ) : $loop->the_post(); 
				global $product; 
			?>
				<div class="products-data">         
					<div class="products-details">
					 <div class="product-widget">
					 <a href="<?php echo get_permalink(); ?>" class="home-image-link-wrapper">
						<?php 
							$CustomThumbURL		=	get_field('popular_thumbnail', $loop->post->ID);
							if(isset($CustomThumbURL) && !empty($CustomThumbURL) && $CustomThumbURL!=''){
								?>
									<img src="<?php echo $CustomThumbURL; ?>" />
								<?php
							}else{
								echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog');
							}
						?>
							
								
								<?php //echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); ?>								
							</a>
							<a href="<?php echo get_permalink(); ?>" class="home-title-link-wrapper">
								<h2><?php echo ucfirst(strtolower(get_the_title())); ?></h2>
							</a>
							<h4>From <?php echo $product->get_price_html();?></h4>
							<!-- <p><?php // echo wp_trim_words( get_the_content(), 20, '...' );?></p> -->
						</div>
					</div>
				</div>
			<?php
			endwhile;
			wp_reset_query(); 			
			return ob_get_clean();
		}
	} // END class CD_Printing
} // END if(!class_exists('CD_Printing'))

if(class_exists('CD_Printing'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('CD_Printing', 'activate'));
	register_deactivation_hook(__FILE__, array('CD_Printing', 'deactivate'));
	define("FETCH_PRINTING_INFO",'http://138.68.1.67:3000/api/productsflats/sizes');
	define("FETCH_PRINTING_PRICE",'http://ec2-35-165-153-147.us-west-2.compute.amazonaws.com/api/omid/getprice.php');
	// instantiate the plugin class
	$wp_plugin_template = new CD_Printing();
	add_shortcode( 'USA_PRINTING_FORM', array('CD_Printing','usaPrintingForm' ));
	add_shortcode( 'POPULAR_PRODUCTS_LIST', array('CD_Printing','display_popular_products' ));

}

/* add_action( 'woocommerce_before_calculate_totals', 'add_custom_total_price',99 );
function add_custom_total_price( $cart_object ) {
    global $woocommerce;
    //echo $custom_price.'----';
	foreach ( $cart_object->cart_contents as $key => $value ) {
       if ( $value['product_id'] == 65) {
            $value['data']->price = $value['variation']['custom_price'];
        }
    }
	return $cart_object;
}
*/

function cd_usprinting_script(){

	//wp_enqueue_script('cd-image-upload-js', plugin_dir_url( __FILE__ ) . 'js/script.js', array('jquery'), '0.1.0', true);
	wp_enqueue_style('cd-image-upload-css', plugin_dir_url( __FILE__ ) . 'css/jquery.fileupload.css');
	wp_enqueue_style('cd-image-style-css', plugin_dir_url( __FILE__ ) . 'css/style.css', '1.0');
	//wp_enqueue_style( 'cd-bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css', array(), '3.3' );
	
	wp_enqueue_script( 'cd-image-uiwidget', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery.ui.widget.js', array( 'jquery' ), '20150113', true );
	wp_enqueue_script( 'cd-image-jquery-transport', plugin_dir_url( __FILE__ ) . 'js/jquery.iframe-transport.js', array( 'jquery' ), '20150113', true );	
	wp_enqueue_script( 'cd-image-upload-js', plugin_dir_url( __FILE__ ) . 'js/jquery.fileupload.js', array( 'jquery' ), '20150113', true );
	
    /*$data = array(
                'upload_url' => admin_url('async-upload.php'),
                'ajax_url'   => admin_url('admin-ajax.php'),
                'nonce'      => wp_create_nonce('media-form')
            );

wp_localize_script( 'cd-image-upload-js', 'su_config', $data );*/

}
add_action('wp_enqueue_scripts','cd_usprinting_script');


add_action( 'wp_ajax_ajaxfileupload', 'ajaxfileupload' );
add_action( 'wp_ajax_nopriv_ajaxfileupload', 'ajaxfileupload' );
function ajaxfileupload()
{
		$allowed_file_types = array(
					'image/jpeg', 
					'image/gif', 
					'image/png', 
					'image/jpg',
					'application/pdf',
					'image/png',
					'image/svg+xml',
					'image/vnd.adobe.photoshop',
					'application/atom+xml',
					'application/x-7z-compressed',
					'package/rar',
					'package/x-tar',
					'application/x-tar-gz',
					'package/zip',
					'package/img',
					'package/x-gzip'
		);
		/* wp_upload_bits will upload the file to the latest directory in wordpress and will return the path. This is basically replacement of move_uploaded_file in php.$_FILES["files"]
		 * <?php wp_upload_bits( $name, $deprecated, $bits, $time ) ?>
		 * */
		$file = wp_upload_bits( $_FILES['files']['name'][0], null, file_get_contents( $_FILES['files']['tmp_name'][0] ) );

		/* nOW WE WILL CONVERT THIS UPLOADED FILE TO ATTACHMENT */
		$filename = $file['file'];
		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $filename ), null );
		
		if (in_array($filetype['type'], $allowed_file_types)) {
			// Your file handing script here
		} else {
			return 'Please upload valid image file.';
		}		
		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		/** <?php wp_insert_attachment( $attachment, $filename, $parent_post_id ); ?>  **/
		$attach_id = wp_insert_attachment( $attachment, $filename);
		// now attchment has been done. we can add additional meta
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		echo $attach_id;die;
}

require_once('usa-widgets.php');
