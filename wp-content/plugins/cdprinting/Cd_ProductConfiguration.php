<?php
	class Cd_ProductConfiguration{
		
		public static function productConfigurationForm(){
			global $product;
			$sku		=	$product->get_sku();			
				try {
					$apiData		=	self::loadConfiguration();
				}
				catch(Exception $e){
					echo 'Exception: ',  $e->getMessage(), "\n";
					die;
				}
				$elementArray		=	array();
				$elementArray		=	array_fill_keys(array_keys($apiData[0]['_id']),array());
				$USP_FIELD_OPTIONS	=	array_keys($elementArray);
				//unset($elementArray['id']);
				//print_r($elementArray);die;
				foreach($apiData as $key=>$singleItem){
					foreach($singleItem['_id'] as $singleKey=> $val){
						if(array_key_exists($singleKey,$elementArray)){
							if(!in_array($val,$elementArray[$singleKey])){
								$elementArray[$singleKey][]	=	$val;
							}
						}
					}
					
				}
				wp_enqueue_script( 'cd_linq', plugins_url( 'linq.js', __FILE__ ), array('jquery'));
				wp_enqueue_script( 'cd_simulate', plugins_url( 'simulate.js', __FILE__ ), array('jquery'));
				wp_enqueue_script( 'cd_jquery_simulate', plugins_url( 'jquery.simulate.js', __FILE__ ), array('jquery'));
				wp_register_script( 'cd_product_configuration', plugins_url( 'product-configuration.js', __FILE__ ), array('jquery','cd_linq') );

				// Localize the script with new data
				$api_configuration_data = array(
					'config' => $apiData,
					'keys'	=> $USP_FIELD_OPTIONS,
				);
				wp_localize_script( 'cd_product_configuration', 'USP_PRODUCT', $api_configuration_data );

				// Enqueued script with localized data.
				wp_enqueue_script( 'cd_product_configuration' );				 
				 
				echo '<div class="USP-loading-aim hidden"></div><div class="cd-product-form-heading">Configure & Price</div><div class="cd-product-field-wrapper">';
				
				$fid		=	0;
				foreach($elementArray as $key=>$singleItem){
						$fid++;
					?>
						
						<div class="box-items">
							<label><?php echo $key; ?> :</label>
							<select id="option_id-<?php echo $key; ?>" name="option_id-<?php echo $key; ?>"  class="us-printing-common-fields" data-serial="<?php echo $fid; ?>" data-key=<?php echo $key;?> <?php echo ($fid>1)?'disabled':'';?>>
											<option value=""></option>
								<?php
									asort($singleItem,1);
									foreach($singleItem as $val){
										?>
											<option value='<?php echo $val;?>'><?php echo $val;?></option>
										<?php
									}
								?>
							</select>
						</div>
					<?php
				}
				//add_action( 'woocommerce_before_single_product_summary',  array( WC_Variation_Description::get_instance()->frontend, 'add_variation_description' ), 25 );
			?>
			<input type="hidden" id="usp-sku" name="product_sku" value="<?php echo $sku; ?>" />
			<div class="codedrill-additional-fields">
			</div>
			<div id="catalogs_upload">
					<div class="box-items">
						<label>Upload Front Side:</label> <!--<input class="catalog_image" id="catalog_front_image" name="option_id-front_image" onchange="" type="file">-->
					<input id="fileupload" type="file" name="files[]">
					<input type="hidden" id="front_image_id" name="option_id-front_image" value="" />	
					<!-- The global progress bar -->
					<div id="progress" class="progress">
					   <div class="progress-bar progress-bar-success"></div>
					<div class="fill"></div>	
					</div>
					<div id="files" class="files"></div>	
					</div>
					<div class="box-items">
						<label>Upload Back Side:</label> <!--<input class="catalog_image" id="catalog_back_image" name="option_id-back_image" onchange="" type="file">-->
					<input id="fileupload-2" type="file" name="files[]">
					<input type="hidden" id="back_image_id" name="option_id-back_image" value="" />	
					<!-- The global progress bar -->
					<div id="progress2" class="progress">
					   <div class="progress-bar progress-bar-success"></div>
					</div>
					<div id="files2" class="files"></div>	
						
					</div>
					<div class="box-items">
						<label>Proof Options:</label> <select name="option_id-proof">
							<option value="">
								-- Please Select --
							</option>
							<option value="Agfa Proof">
								Agfa Proof (hard copy)
							</option>
							<option value="No Proof Needed">
								No Proof Needed
							</option>
							<option value="Pdf Proof">
								Pdf Proof (email)
							</option>
						</select>
					</div>
					
					<script>
					/*jslint unparam: true */
					/*global window, $ */
					jQuery(function ($) {
					    'use strict';
					    // Change this to the location of your server-side upload handler:
					    
					    $('#fileupload').fileupload({
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
						dataType: 'json',
							formData: {action: 'ajaxfileupload'},
						done: function (e, data) {
						    $('#front_image_id').val(data.result); 		
						    $.each(data.result.files, function (index, file) {
							console.log(data);
							
							$('<p/>').text(file.name).appendTo('#files');
						    });
						},
						progressall: function (e, data) {
						    var progress = parseInt(data.loaded / data.total * 100, 10);
						    $('#progress .fill').css(
							'width',
							progress + '%'
						    );
						}
					    }).prop('disabled', !$.support.fileInput)
						.parent().addClass($.support.fileInput ? undefined : 'disabled');

					    $('#fileupload-2').fileupload({
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
						dataType: 'json',
							formData: {action: 'ajaxfileupload'},
						done: function (e, data) {
						    $('#back_image_id').val(data.result);
						    console.log(data);	 		
						    $.each(data.result.files, function (index, file) {
							console.log(data);
							
							$('<p/>').text(file.name).appendTo('#files2');
						    });
						},
						progressall: function (e, data) {
						    var progress = parseInt(data.loaded / data.total * 100, 10);
						    $('#progress2 .progress-bar').css(
							'width',
							progress + '%'
						    );
						}
					    }).prop('disabled', !$.support.fileInput)
						.parent().addClass($.support.fileInput ? undefined : 'disabled');	
					});
				</script>
				</div>				
			<?php
		}	
		public static function productConfigurationFormCloseDiv(){
			echo '<div class="USP-product-apionly-price"></div><div class="USP-product-price"></div></div> <!-- cd-product-field-wrapper ends here-->';
		}
		public static function loadConfiguration(){
			global $product;
			$sku		=	$product->get_sku();
			if($sku==''){
				$sku		=	'USAP-101';
			}
			$response = wp_remote_post(FETCH_PRINTING_INFO,array('body'=>array('sku'=>$sku)));
			if(!$response || !is_array($response) || !$body=json_decode($response['body'],true)){
				throw new Exception('Not Available! or Some Technical Issue');
				return;
			}
			$tempArray			=	array();
			$tempMainArray		=	array();
			foreach($body['result'] as $key=>$val){
				foreach($val['_id'] as $subkey=>$subval){
					if($subval=='null' || $subval===0 || $subval=="N/A"){						
						//unset($body['result'][$key]['_id'][$subkey]);
					}else{
						$tempArray['_id'][$subkey]		=	$subval;
					}
				}
				$tempMainArray[$key]	=	$tempArray;
			}
			return $tempMainArray;
		}
		public static function getCalculatePrice($queryString){
			//send api request and get new price http://138.68.1.67:3000/api/productsflats/findOne?
			
			$response		=	wp_remote_get('http://138.68.1.67:3000/api/productsflats/findOne?'.$queryString);
			if(!$response || !is_array($response) || !$body=json_decode($response['body'],true)){
				throw new Exception('Product Not Available! or Some Technical Issue');
				return;
			}
			return $body;
		}

		public static function getCalculatedWeight($queryString){
			//send api request and get new price http://138.68.1.67:3000/api/productsflats/findOne?
			
			$response		=	wp_remote_post('http://138.68.1.67:3000/api/productsflats/getTotalWeight',array('body'=>$queryString));
			if(!$response || !is_array($response) || !$body=json_decode($response['body'],true)){
				throw new Exception('Product Not Available! or Some Technical Issue');
				return;
			}
			return $body;
		}

		public static function save_cart_item_extra_meta( $cart_item_data, $product_id ) {
			if( isset( $_REQUEST ) ) {
			$posted_data = array();
			foreach($_REQUEST as $key => $value){
			  if(preg_match('/option_id-/',$key))
			  {
				$posted_data[$key] = $value;
			  }
			 
			}

			$img_id = $posted_data['option_id-front_image'];	
			if($img_id){ 
				$img_url = wp_get_attachment_url( $img_id );
				$html_tag = '<a href="'.$img_url.'" target="_blank">Attached File</a>';
				$posted_data['option_id-front_image'] = $html_tag;
			}
			$img_id = $posted_data['option_id-back_image'];
			if($img_id){ 
				$img_url = wp_get_attachment_url( $img_id );
				$html_tag = '<a href="'.$img_url.'" target="_blank">Attached File</a>';
				$posted_data['option_id-back_image'] = $html_tag;
			}		
			//$posted_data['option_id-front_image'] = $_REQUEST['option_id-cd_front_image'];
			$posted_data['sku']	=	$_REQUEST['product_sku'];
			//print_r($posted_data); die;
			/*foreach($_FILES as $key => $file){
				$plugindir = plugin_dir_path( __FILE__ );
				$plugindir = $plugindir.'images/'.$_FILES[$key]['name']; 
				$raw_file_name = $_FILES[$key]['tmp_name'];
				$wp_upload_dir = wp_upload_dir();
				$wp_upload_name = $wp_upload_dir['path'].'/'.$_FILES[$key]['name'];
				//$file = wp_upload_bits( $_FILES[$key]['name'], null, @file_get_contents( $raw_file_name ) );
				$name = rand().$_FILES[$key]['name'];
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$mime_types = array(
					'pdf' => 'application/pdf',
					'jpg|jpeg' => 'image/jpeg',
					'gif' => 'image/gif',
					'png' => 'image/png',
					'svg' => 'image/svg+xml',
					'psd' => 'image/vnd.adobe.photoshop',
					'xml' => 'application/atom+xml',
					'7z' => 'application/x-7z-compressed',
					'rar' => 'package/rar',
					'tar' => 'package/x-tar',
					'tgz' => 'application/x-tar-gz',
					'zip' => 'package/zip',
					'img|iso' => 'package/img',
					'gz|gzip' => 'package/x-gzip'
				);
				$upload_file = array(
					'name' => $name,
					'type'=> $_FILES[$key]['type'],
					'tmp_name' => $_FILES[$key]['tmp_name'],
					'error' =>$_FILES[$key]['error'],
					'size'=>$_FILES[$key]['size']
				);

				$upload_overrides = array( 'test_form' => false,'mimes' => $mime_types );
				$upload_handler  = wp_handle_upload($upload_file,$upload_overrides);
				if (array_key_exists('error',$upload_handler)){
					$posted_data[$key] = $upload_handler['error'];
				}else{
					$attachment = array(
					 'post_mime_type' => $_FILES[$key]['type'],
					 'post_title' => $name,
					 'post_content' => 'Image for '.$_FILES[$key]['name'],
					 'post_status' => 'inherit'
					);

					$img_id  = wp_insert_attachment( $attachment, $upload_handler['file'], $product_id);
					$img_url = wp_get_attachment_url( $img_id );
					$html_tag = '<a href = "'.$img_url.'"  target="_blank">Attached File</a>';
					$posted_data[$key] = $html_tag;
				}
			}*/
			foreach($posted_data as $key => $data)
			{
			$cart_item_data[ $key ] = $data;
			}
			$cart_item_data['unique_key'] = md5( microtime().rand() );
			}	
			return $cart_item_data;
		}

		
		public static function render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
			$posted_data = array();
			foreach($cart_item as $key => $value)
			{
			if(preg_match('/option_id-/',$key))
			{
				$posted_data[$key] = $value;
			}
			}
			$custom_items = array();
			foreach($posted_data as $key => $data)
			{
			$custom_items[] = array( "name" => str_replace('option_id-','',$key), "value" => $data );	
			}
			$custom_items[]	=	array("name" => "Weight", "value"=>$cart_item['data']->CD_weight);
			return $custom_items; 
		}
		
		public static function product_order_meta_handler_url( $item_id, $values, $cart_item_key ) {
		$posted_data = array();
		foreach($values as $key => $value)
		{
		if(preg_match('/option_id-/',$key))
		{
			$posted_data[$key] = $value;
		}
		}
		foreach($posted_data as $key => $data)
		{
		wc_add_order_item_meta( $item_id, $key, $data );   
		}
}
public static function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}
		public static function configured_price( $cart_object ) {
			global $woocommerce;
		   $custom_price = 100; //Custom price you want to add
		   foreach($cart_object->cart_contents as $key=>$value){
			   $keyCounter		=	0;
			   $queryString		=	'';




			if(isset($value['variation_id']) && $value['variation_id']!=0){
				$wcproduct = new WC_Product_Variation($value['variation_id']);
				$regular_price = $wcproduct->regular_price;
			}
			   foreach($value as $subkey=>$sub_value){
				   if (preg_match('/(option_id-*)/',$subkey)){
					   if($subkey=='option_id-front_image' || $subkey=='option_id-back_image' || $subkey=='option_id-proof' || $subkey=='option_id-cd_front_image'){
						     continue;      
						}
					$queryString		.=	"filter[where][".str_replace('option_id-','',$subkey)."]=".rawurlencode(stripslashes($sub_value)).'&';
					$keyCounter++;   
				   }
			   }
			   $queryString.="filter[where][sku]=".$value['sku']; 
			   rtrim($queryString,'&');

			   if($keyCounter>0){
				   $queryString		=	rtrim($queryString,'&');
				try {
					$apiData		=	self::getCalculatePrice($queryString);
					// if
					if(isset($apiData['price'])){
						//echo (int)str_replace(array('$',','),'',$apiData['price']);die;
						$value['data']->price	=	(float)str_replace(array('$',','),'',$apiData['price']);
						if(isset($regular_price)){
							$value['data']->price		+= $regular_price;
						}
						// send 
						if(isset($value['option_id-Size'])){
							$sizeForWeight			=	$value['option_id-Size'];
						}else{
							$sizeForWeight			=	1;
						}
						
						if(isset($value['option_id-Paper'])){
							$sizeForPaper			=	$value['option_id-Paper'];
						}else{
							$sizeForPaper			=	1;
						}
						
						if(isset($value['option_id-Page'])){
							$sizeForPage			=	$value['option_id-Page'];
						}else{
							$sizeForPage			=	"";
						}
						if($sizeForPage!=""){
							$weightQueryString		=	array("SizeValue"=>stripslashes($sizeForWeight),"PaperValue"=>stripslashes($sizeForPaper),"PagesValue"=>stripslashes(trim($sizeForPage)));
						}else{
							
							$weightQueryString		=	array("SizeValue"=>stripslashes($sizeForWeight),"PaperValue"=>stripslashes($sizeForPaper));
						}
						
						/* Calculate Square Inch Price for Large Format Printing. If weight is not set from admin then it will calculte from api. So it is Understood that admin will add weight for Large format printing only. */
						$weightForLargeFormat		=	get_field('weight_square_inch',$value['product_id']);
						if(isset($weightForLargeFormat) && !empty($weightForLargeFormat) && $weightForLargeFormat>0){
							$lowerCasedKeysArray		=	array_change_key_case($value);  // In case we get keys with upper case.
							$value['data']->CD_weight	=	$weightForLargeFormat*$lowerCasedKeysArray['option_id-quantity']*$lowerCasedKeysArray['option_id-height']*$lowerCasedKeysArray['option_id-width'];
						}else{
							$weightApi			=	self::getCalculatedWeight($weightQueryString);
							// set default to 0, if nothing comes from api. So that we can resolve the issue.
							if(isset($weightApi['error'])){
								$value['data']->CD_weight	=	100;
							}else{
								$value['data']->CD_weight	=	$weightApi['totalWeight']['totalWeight']* ($value['option_id-quantity']/1000);
							}							
						}

						//$value['data']->CD_weight	=	(float)$apiData['weight'];
					}else{
						$value['data']->price	=	1000000;
						unset($cart_object->cart_contents);
						echo 'Aunauthorized Action Detected! This will be Reported to Authorities!';
						return $cart_object;
						break;
					}
					
				}
				catch(Exception $e){
					echo 'Exception: ',  $e->getMessage(), "\n";
					die;
				}	
				//$value['data']->price = $custom_price;
			   }else{
				   // let him enjoy
			   }
		   }
		  
		   
		}
		public static function update_total_weight_from_api($weight,$productObj){
			
			global $woocommerce;
			return $productObj->CD_weight;
		}
		
		public static function filter_wc_add_to_cart_message( $message, $product_id ) { 
			
			global $woocommerce;
			$cart_total = $woocommerce->cart->get_cart_total();
			$cart_count = $woocommerce->cart->get_cart_contents_count();
			$message = '<div class="cart-notification">'.$message .' <b>Cart subtotal</b>( '. $cart_count .' Items): '. $cart_total.'</div>';
			return $message; 
			
		}
 
		public static function custom_number_field_validation_filter( $result, $tag ) {
			$tag = new WPCF7_FormTag( $tag );
			if ( 'number-564' == $tag->name ) {
				$quantity = isset( $_POST['number-564'] ) ? trim( $_POST['number-564'] ) : '';
				if (!is_numeric($_POST['number-564']) ) {
					$result->invalidate( $tag, "Please enter a numeric value" );
				}
			}
			if ( 'number-565' == $tag->name ) {
				$quantity = isset( $_POST['number-565'] ) ? trim( $_POST['number-565'] ) : '';
				if (!is_numeric($_POST['number-565']) ) {
					$result->invalidate( $tag, "Please enter a numeric value" );
				}
			}
			if ( 'number-566' == $tag->name ) {
				$quantity = isset( $_POST['number-566'] ) ? trim( $_POST['number-566'] ) : '';
				if (!is_numeric($_POST['number-566']) ) {
					$result->invalidate( $tag, "Please enter a numeric value" );
				}
			}
		 
			return $result;
		}
		public static function change_addtocart_text(){
			global $woocommerce;
			
			if(!is_product()){
				return __( 'View', 'woocommerce' );
			}
		}
		public static function change_addtocart_link($link){
			global $woocommerce;
			
			if(!is_product()){
				global $product;
				$product_id = $product->id;
				$product_sku = $product->get_sku();
				return $link = '<a href="'.get_permalink().'" rel="nofollow" data-product_id="'.$product_id.'" data-product_sku="'.$product_sku.'" data-quantity="1" class="button product_type_simple add_to_cart_button cd_cart_view_btn">View</a>';
			}
		}
		public static function getProductInfoAPI($data){
			//lets get sku
			$sku		=	$data->get_param('sku');
			$responseArray	=	array('status'=>0,'id'=>0,'url'=>'','title'=>'','image'=>'');
			$skuArr			=	explode(',',$sku);
			$returnArray	=	array();
			foreach($skuArr as $key=>$sku){
				if($sku==''){
					$sku	=	0;
				}
				$productId = wc_get_product_id_by_sku( $sku );
				if($productId==0){
					$responseArray['status']	=	0;
					$responseArray['sku']		=	$sku;
					$responseArray['url']		=	'';
					$responseArray['title']		=	'UNKNOWN';
					$responseArray['image']		=	'UNKNOWN';
					$responseArray['id']		=	0;
				}else{
					$img						=	wp_get_attachment_image_src( get_post_thumbnail_id( $productId ) ,'thumbnail');
					$_product = wc_get_product($productId);
					$responseArray['status']	=	1;
					$responseArray['id']		=	$productId;
					$responseArray['title']		=	$_product->post->post_title;
					$responseArray['url']		=	get_permalink($productId);
					$responseArray['sku']		=	$sku;
					$responseArray['image']		=	$img[0];
				}
				$returnArray[]				=	$responseArray;
			}			
			return $returnArray;
		}
	}
//apply_filters( 'woocommerce_product_weight', apply_filters( 'woocommerce_product_get_weight', '' === $this->weight ? '' : wc_format_decimal( $this->weight ) ), $this );
	add_action( 'woocommerce_before_add_to_cart_button', array('Cd_ProductConfiguration','productConfigurationForm' ));	
	add_action( 'woocommerce_after_add_to_cart_button', array('Cd_ProductConfiguration','productConfigurationFormCloseDiv' ),30);	
	add_action( 'woocommerce_add_cart_item_data', array('Cd_ProductConfiguration','save_cart_item_extra_meta' ), 10, 2 );	
	add_filter( 'woocommerce_get_item_data', array('Cd_ProductConfiguration','render_meta_on_cart_and_checkout' ), 10, 2 );	
	add_action( 'woocommerce_add_order_item_meta', array('Cd_ProductConfiguration','product_order_meta_handler_url' ), 1, 3 );	
	add_action( 'woocommerce_before_calculate_totals', array('Cd_ProductConfiguration','configured_price') );
	add_filter('woocommerce_product_weight', array('Cd_ProductConfiguration','update_total_weight_from_api'),10,2);
	add_filter( 'wc_add_to_cart_message', array('Cd_ProductConfiguration','filter_wc_add_to_cart_message'), 99, 2 );
	add_filter( 'wpcf7_validate_number', array('Cd_ProductConfiguration','custom_number_field_validation_filter'), 20, 2 );
	
	add_filter( 'woocommerce_product_add_to_cart_text',  array('Cd_ProductConfiguration','change_addtocart_text') );
	add_filter( 'woocommerce_loop_add_to_cart_link',  array('Cd_ProductConfiguration','change_addtocart_link') );
	add_action( 'rest_api_init', function () {
			register_rest_route( 'wp/v2', '/getproductinfo/sku=(?P<sku>[a-zA-Z0-9-_,]+)', array(
					'methods'  => 'GET',
					'callback' => array('Cd_ProductConfiguration','getProductInfoAPI')
				)
			);
	});
	add_filter( 'wc_add_to_cart_message', 'custom_add_to_cart_message' );
	function custom_add_to_cart_message() {
		global $woocommerce;

			$return_to  = get_permalink(woocommerce_get_page_id('shop'));
			$message    = sprintf('<div class="must-show"><a href="%s" class="button wc-forwards">%s</a> %s <a href="/checkout" class="button wc-forwards to-checkout">Checkout.</a></div>', $return_to, __('Continue Shopping', 'woocommerce'), __('Or', 'woocommerce') );
		return $message;
	}	
	/* Add tabs to the product page */
		add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
		function woo_new_product_tab( $tabs ) {
			
			// Adds the new tab
			
			/*$tabs['cd_templates'] = array(
				'title' 	=> __( 'Templates', 'woocommerce' ),
				'priority' 	=> 11,
				'callback' 	=> 'cd_template_tab_content'
			);*/
			$tabs['cd_mailing'] = array(
							'title' 	=> __( 'Mailing', 'woocommerce' ),
							'priority' 	=> 12,
							'callback' 	=> 'cd_mailing_tab_content'
						);
			
			return $tabs;

		}
		/*function cd_template_tab_content() {
			global $product;
			$id = $product->id;
			echo $field = get_field('template', $id);
		}*/	
		function cd_mailing_tab_content() {
			global $product;
			$id = $product->id;
			echo $field = get_field('mailing', $id);
		}	


		
	?>
