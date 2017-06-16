<?php

/**
 * @author 		: Saravana Kumar K
* @author url  	: iamsark.com
* @copyright	: sarkware.com
* @todo			: upadte cart value on session.
*
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wccpf_cart_updater {
	function __construct() {				
		add_filter( "wcff/update/item/cart", array( $this, "wcff_cart_field_render" ), 99, 1 );
		add_filter( "wcff/update/item/cart/session_item", array( $this, "wcff_update_session_cart_data" ), 99, 1);
	}
	
	function wcff_cart_field_render( $datas ) {	
		$all_fields 	= apply_filters( 'wcff/load/all_fields', $datas["product_id"], 'wccpf' );
		$admin_fields 	= apply_filters( 'wcff/load/all_fields', $datas['product_id'], 'wccaf', 'any' );
		$field 			= null;
		$a_field 		= null;
		$fieldc  		= null;
		foreach ( $all_fields as $value ){
			if( isset( $value[ "wccpf_".$datas[ "data" ][ "name" ] ] ) ){
				$field = $value[ "wccpf_".$datas[ "data" ][ "name" ] ];
			}
		}
		foreach ( $admin_fields as $avalue ){
			if( isset( $avalue[ "wccaf_".$datas[ "data" ][ "name" ] ] ) ){
				$a_field = $avalue[ "wccaf_".$datas[ "data" ][ "name" ] ];
			}
		}
		if( $field != null ){
			$fieldc = $field;
		} else if( $a_field != null ){
			$fieldc = $a_field;
		} else {
			return false;
		}	
		$field_editable = isset( $fieldc[ "cart_editable" ] ) ? $fieldc[ "cart_editable" ] : "no";
		if( $field_editable == "no" ){			
			return false;
		} else{		
			if( isset( $datas[ "check_edit" ] ) ){
				if( $datas[ "check_edit" ] == true  ){
					return true;
				}
			} 
			if( $fieldc["type"] == "colorpicker" ){
				$field_html 	= $this->render_field_for_current_item( $fieldc, $datas['product_id'], $datas['data'][ 'value' ] );
			} else {
				$field_html 	= $this->render_field_for_current_item( $fieldc, $datas['product_id'] );
			}		
			return $field_html;			
		}
	}
	
	/*
	 * field html rendering
	 * $field 		: field name from font-end
	 * $productid	: product id
	 * $value		: for only color picker
	 */
	
	function render_field_for_current_item( $field, $productid, $value = null ){
		$html 	= "";
		$script = "";
		$is_colorpicker_there = false;
		$is_datepicker_there = false;
		$color_pick_show = false;
		$html .= apply_filters( 'wcff/render/product/field/type='.$field["type"], $field );
		if( has_filter( 'wccpf/before/fields/rendering' ) ) {
			$html .= apply_filters( 'wccpf/before/fields/rendering', $field, $html );
		}		
		do_action( 'wccpf/before/field/start', $field );
		do_action( 'wccpf/after/field/end', $field );
		if( $field["type"] == "datepicker" ) {
			$is_datepicker_there = true;
			$script = "<script type='text/javascript' data-type='wpff-datepicker-script' src='http://localhost:8081/wp-test/wp-includes/js/jquery/ui/datepicker.min.js?ver=1.11.4'></script>";
		}
		if( $field["type"] == "colorpicker" ) {
			$is_colorpicker_there = true;
		}
		if( $is_colorpicker_there ){		
			if( $field[ "hex_color_show_in" ] == "yes" ){
				$color_pick_show = true;
			}
			$script = $this->wccpf_inject_color_picker_script( $productid, $value );
		}
		return array( "field_type" => $field["type"], "html" => $html, "script" => $script, "color_showin" => $color_pick_show ); 		
	}

	/*
	 * update cart sessiona data
	 * $datas : onject of product field data
	 */
	
	function wcff_update_session_cart_data( $datas ){
		$validate = $this->validate_wccpf( $datas[ "product_id" ], $datas[ "data" ][ "name" ], $datas["data"]["value"] );
		$message = "";
		$return_value = "";
		$saveval = $datas["data"]["value"];
		if( isset( $datas[ "data" ][ "color_showin" ] ) ){
			if( $datas[ "data" ][ "color_showin" ] ){
				$saveval = urldecode( $datas["data"]["value"] );
			}		
		}
		if( !$validate[ "status" ] ){
			return array( "status" => false, "message" => $validate[ "msg" ] );
		} else {
			global $woocommerce;
			if( $validate[ "is_admin" ] ){
				if( isset( $datas[ "data" ][ "field_type" ] ) )
				if( $datas[ "data" ][ "field_type" ] != "file" ){
					$woocommerce->cart->cart_contents[ $datas['product_cart_id'] ][ "wccaf_".$datas["data"][ "name" ] ] = $saveval;
					$return_value = $woocommerce->cart->cart_contents[ $datas['product_cart_id'] ][ "wccaf_".$datas["data"][ "name" ] ];
				} else{
					
				}
			} else {
				if( $datas[ "data" ][ "field_type" ] != "file" ){
					$woocommerce->cart->cart_contents[ $datas['product_cart_id'] ][ "wccpf_".$datas["data"][ "name" ] ] = $saveval;
					$return_value = $woocommerce->cart->cart_contents[ $datas['product_cart_id'] ][ "wccpf_".$datas["data"][ "name" ] ];
				} else {
					
				}
			}
			$woocommerce->cart->set_session();
			return array( "status" => true, "value" => $return_value, "field_type" => $datas["data"]["field_type"] );
		}
	}
	
	
	/**
	 * @param 	INT	 	$prod_id
	 * @param 	STRING	$field_name
	 * @param 	STRING 	$value
	 *
	 */
	function validate_wccpf( $prod_id, $field_name, $value ) {
		$is_passed = true;
		$is_admin  = false; 
		$wccpf_options = wcff()->option->get_options ();		
		$all_fields = apply_filters ( 'wcff/load/all_fields', $prod_id, 'wccpf' );
		$admin_fields = apply_filters ( 'wcff/load/all_fields', $prod_id, 'wccaf', 'any' );
		$a_field 		= null;
		$fieldc  		= null;
		$fieldac		= null;
		$msg			= "";
		foreach ( $all_fields as $val ){
			if( isset( $val[ "wccpf_".$field_name ] ) ){
				$fieldc = $val[ "wccpf_".$field_name ];
			}
		}
		foreach ( $admin_fields as $avalue ){
			if( isset( $avalue[ "wccaf_".$field_name ]) ){
				$fieldac = $avalue[ "wccaf_".$field_name ];
			}
		}
		if( $fieldc != null ){
			$field = $fieldc;
			$res = true;
			$res_size_val = true;				
			$field ["required"] = isset ( $field ["required"] ) ? $field ["required"] : "no";
			if ($field ["required"] == "yes" || $field ["type"] == "file") {
				if ($field ["type"] != "file") {
					$res = apply_filters( 'wccpf/validate/type='.$field["type"], $value );
				} else {
					
				}
			}
			if ( ! $res || ! $res_size_val ) {
				$is_passed = false;
				$msg = ! $res ? $field ["message"] : "Upload size limit exceed, Allow size is " . $field ["max_file_size"] . "kb.!";
			}
		}
		if ( $fieldac != null ) {
			$is_admin = true;
			$afield = $fieldac;
			$res = true;
			$afield ["show_on_product_page"] = isset ( $afield ["show_on_product_page"] ) ? $afield ["show_on_product_page"] : "no";
			if ($afield ["show_on_product_page"] == "yes" && $afield ["required"] == "yes") {		
				$res = apply_filters( 'wccpf/validate/type='.$field["type"], $value );			
			}
			if (! $res) {
				$is_passed = false;
				$msg = $afield ["message"];
			}
		}
		return array( "status" => $is_passed, "is_admin" => $is_admin, "msg" => $msg );
	}	
	
	function wccpf_inject_color_picker_script( $product_id, $color_code ) {
		Global $product;
		$productid = null;
		if( $product_id == null ){
			$productid = $this->get_product_id( $product );
		} else{
			$productid = $product_id;
		}
		$all_fields   = apply_filters( 'wcff/load/all_fields', $productid, 'wccpf' );
		$admin_fields = apply_filters( 'wcff/load/all_fields', $productid, 'wccaf', 'any' );
		$script = '<script type="text/javascript">
				var $ = jQuery;
				function wccpf_init_color_pickers() {';
	
		foreach ( $all_fields as $title => $fields ) {
			foreach ( $fields as $key => $field ) {
				if( $field["type"] == "colorpicker" ) {
					$palettes = null;
					$colorformat = isset( $field["color_format"] ) ? $field["color_format"] : "hex";
					$defaultcolor = isset( $field["default_value"] ) ? $field["default_value"] : "#000";
					$defaultcolor = $color_code != null ? $color_code : $defaultcolor;
					if( isset( $field["palettes"] ) && $field["palettes"] != "" ) {
						$palettes = explode( ";", $field["palettes"] );
					} ?>
												
						<?php 
							$script .= '$( ".wccpf-color-'. esc_attr( $field["name"] ).'").spectrum({
							color: " '.$defaultcolor.'", 
							preferredFormat: "'.$colorformat.'",'; 
						?>					
							<?php 
							$comma = "";
							$indexX = 0;
							$indexY = 0;
							if( is_array( $palettes ) && count( $palettes ) > 0 ) {
								if( $field["show_palette_only"] == "yes" ) {
									$script .= "showPaletteOnly: true,";
								}
								$script .= "showPalette: true,";
								$script .= "palette : [";						
								foreach ( $palettes as $palette ) {		
									$indexX = 0;								
									$comma = ( $indexY == 0 ) ? "" : ",";
									$script .= $comma."[";
									$colors = explode( ",", $palette );
								 	foreach ( $colors as $color ) {							 		
								 		$comma = ( $indexX == 0 ) ? "" : ","; 
								 		$script .= $comma ."'". $color ."'";	
								 		$indexX++;
									}
									$script .= "]";
									$indexY++;
								} 
								$script .= "]";						
							}
						
						$script .= "});";	
					}
				}
			}				
			if( count( $admin_fields ) > 0 ) {
				foreach ( $admin_fields as $title => $afields ) {
					if( count( $afields ) > 0 ) {
						foreach ( $afields as $key => $afield ) {
							if( $afield["type"] == "colorpicker" ) {
								$palettes = null;
								$colorformat = isset( $afield["color_format"] ) ? $afield["color_format"] : "hex";
								$defaultcolor = isset( $afield["default_value"] ) ? $afield["default_value"] : "#000";
								
								$mval = get_post_meta( $productid, "wccaf_". $afield["name"], true );
								
								if( !$mval || $mval == "" ) {								
									$mval = $defaultcolor;								
								}
								$mval = $color_code != null ? $color_code : $mval;
								if( isset( $afield["palettes"] ) && $afield["palettes"] != "" ) {
									$palettes = explode( ";", $afield["palettes"] );
								} ?>
								
							<?php  $script .= '$( ".wccpf-color-'.esc_attr( $afield["name"] ).'").spectrum({
									 color: "'.$mval.'", 
									 preferredFormat: "'.$colorformat.'",';					
							?>		
							<?php 
									$comma = "";
									$indexX = 0;
									$indexY = 0;
									if( is_array( $palettes ) && count( $palettes ) > 0 ) {
										if( $afield["show_palette_only"] == "yes" ) {
											$script .= "showPaletteOnly: true,";
										}
										$script .= "showPalette: true,";
										$script .= "palette : [";						
										foreach ( $palettes as $palette ) {		
											$indexX = 0;								
											$comma = ( $indexY == 0 ) ? "" : ",";
											$script .= $comma."[";
											$colors = explode( ",", $palette );
										 	foreach ( $colors as $color ) {							 		
										 		$comma = ( $indexX == 0 ) ? "" : ","; 
										 		$script .= $comma ."'". $color ."'";	
										 		$indexX++;
											}
											$script .= "]";
											$indexY++;
										} 
										$script .= "]";						
									}
									
								$script .= "});";	
							
							}
						}	
					}				
				}
			}
			
			$script .= '}				
				$( document ).ready(function() {			
					wccpf_init_color_pickers();
				});
			</script>';
			if( $product_id == null ){
				echo $script;
			} else {			
				return $script;
			}
		}	
		
		/*
		 * product id
		 */
		
		function get_product_id( $product ){
			return method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;
		}
}


new wccpf_cart_updater();
?>