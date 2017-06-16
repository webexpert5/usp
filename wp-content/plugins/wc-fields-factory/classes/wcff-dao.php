<?php 
/**
 * @author 		: Saravana Kumar K
 * @author url  : iamsark.com
 * @copyright	: sarkware.com
 * This is the core Data Access Object for the entire wccpf related CRUD operations. 
 * 
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wcff_dao {
	/* Namespace for WCFF related post meta
	 * "wccpf_" for Custom product page Fields ( Front end product page )
	 * "wccaf_" for Custom admin page fields ( for Products & Product Categories )
	 *  */
	var $wcff_key_prefix = "wccpf_";
	
	function __construct() {
				
		add_action( 'save_post', array( $this, 'save_wcff_rules' ), 1, 3 );
		add_action( 'wcff/update/post/type', array( $this, 'update_wcff_post_type' ) );
		add_filter( 'wcff/load/condition/rules', array( $this, 'load_wcff_condition_rules' ) );
		add_filter( 'wcff/load/location/rules', array( $this, 'load_wcff_location_rules' ) );
		add_filter( 'wcff/load/all/location/rules', array( $this, 'load_wcff_add_location_rules' ) );		
		
		add_filter( 'wcff/load/products', array( $this, 'load_products' ) );
		add_filter( 'wcff/load/products/cat', array( $this, 'load_products_cat' ) );
		add_filter( 'wcff/load/products/tag', array( $this, 'load_products_tag' ) );
		add_filter( 'wcff/load/products/type', array( $this, 'load_products_type' ) );
		add_filter( 'wcff/load/products/tabs', array( $this, 'load_products_tabs' ) );
		add_filter( 'wcff/load/metabox/contexts', array( $this, 'load_metabox_contexts' ) );
		add_filter( 'wcff/load/metabox/priorities', array( $this, 'load_metabox_priorities' ) );
		
		add_filter( 'wcff/load/all_fields', array( $this, 'load_all_wcff_fields' ), 10, 3 );
		add_filter( 'wcff/load/fields', array( $this, 'load_wcff_fields' ), 5, 2 );
		add_filter( 'wcff/load/field', array( $this, 'load_wcff_field' ), 5, 2 );
		add_filter( 'wcff/save/field', array( $this, 'save_wcff_field' ), 5, 3 );
		add_filter( 'wcff/update/field', array( $this, 'update_wcff_field' ), 5, 2 );
		add_filter( 'wcff/remove/field', array( $this, 'remove_wcff_field' ), 5, 2 );	
		
	}
	
	function update_wcff_post_type( $type ) {		
		$this->wcff_key_prefix = $type . "_";
	}
	
	/**
	 * @return 	ARRAY
	 * @todo	Used to load all woocommerce products
	 * 			Used in "Conditions" Widget 
	 */
	function load_products() {
		$args = array( 'post_type' => 'product', 'order' => 'ASC', 'posts_per_page' => -1 );
		$products = get_posts( $args );
		$productsList = array();
		
		if( count( $products ) > 0 ) {
			foreach( $products as $product ) {				
				$productsList[] = array( "id" => $product->ID, "title" => $product->post_title );
			}
		}
		
		return $productsList;
	}
	
	/**
	 * @return 	ARRAY
	 * @todo	Used to load all woocommerce products category
	 * 			Used in "Conditions" Widget
	 */
	function load_products_cat() {
		$product_cat = array();
		$pcat_terms = get_terms( 'product_cat', 'orderby=count&hide_empty=0' );
		
		foreach( $pcat_terms as $pterm ) {
			$product_cat[] = array( "id" => $pterm->slug, "title" => $pterm->name );
		}
		
		return $product_cat;
	}	
	
	function load_products_tag() {
		$product_tag = array();
		$ptag_terms = get_terms( 'product_tag', 'orderby=count&hide_empty=0' );
		
		foreach( $ptag_terms as $pterm ) {
			$product_tag[] = array( "id" => $pterm->slug, "title" => $pterm->name );
		}
		
		return $product_tag;
	}
	
	function load_products_type() {
		$product_type = array();
		$default_type = apply_filters( 'default_product_type', 'simple' );
		$all_types = apply_filters( 'product_type_selector', array(
			'simple'   => __( 'Simple product', 'woocommerce' ),
			'grouped'  => __( 'Grouped product', 'woocommerce' ),
			'external' => __( 'External/Affiliate product', 'woocommerce' ),
			'variable' => __( 'Variable product', 'woocommerce' )
		), $default_type );
		
		foreach ( $all_types as $key => $value ) {
			$product_type[] = array( "id" => $key, "title" => $value );
		}
		
		return $product_type;
	}
	
	/**
	 * @return 	ARRAY
	 * @todo 	Used to load location values
	 * 			Used by wccaf location rules
	 */
	function load_products_tabs() {
		return apply_filters( 'wcff/product/tabs', array (
			"General Tab" => "woocommerce_product_options_general_product_data",
			"Inventory Tab" => "woocommerce_product_options_inventory_product_data",
			"Shipping Tab" => "woocommerce_product_options_shipping",
			"Attributes Tab" => "woocommerce_product_options_attributes",
			"Related Tab" => "woocommerce_product_options_related",
			"Advanced Tab" => "woocommerce_product_options_advanced",
			"Variable Tab" => "woocommerce_product_after_variable_attributes"
		));
	}
	
	function load_metabox_contexts() {
		return apply_filters( 'wcff/metabox/contexts', array( "normal" => "Normal", "advanced" => "Advanced", "side" => "Side" ));
	}
	
	function load_metabox_priorities() {
		return apply_filters( 'wcff/metabox/priorities', array( "high" => "High", "core" => "Core", "default" => "Default", "low" => "Low" ));
	}
	
	/**
	 * 
	 * @param 	INT 		$pid	- WCFF Post Id
	 * @param   BOOLEAN 	$sort   - Whether returning fields should be sorted
	 * @param   STRING  	$type   - Type of fields ( wccpf, wccaf ... )
	 * @return 	ARRAY
	 * @todo	This function is used to load all wcff fields for a single WCFF post
	 * 			mostly used in editing wccpf fields in admin screen 
	 */
	function load_wcff_fields( $pid, $sort = true ) {
		$fields = array();
		$meta = get_post_meta( $pid );		
		foreach ( $meta as $key => $val ) {
		 	if( preg_match('/'. $this->wcff_key_prefix .'/', $key) ) {
		 		if( $key != $this->wcff_key_prefix.'condition_rules' && $key != $this->wcff_key_prefix.'location_rules' && $key != $this->wcff_key_prefix.'group_rules' ) {		 			
					$fields[ $key ] = json_decode( $val[0], true );
				}	
		 	}
		 }
		 
		 if( $sort ) {
		 	$this->usort_by_column( $fields, "order" );
		 }
		 
		 return $fields;
	}
	
	/**
	 * 
	 * @param 	INT 		$pid	- Product Id
	 * @param   STRING  	$type   - Type of fields ( wccpf, wccaf ... )
	 * @return 	ARRAY 		( Two Dimentional )
	 * @todo	This function is used to Load all WCCPF groups. which is used by "wccpf_product_form" module
	 * 			to render actual wccpf fields on the Product Page.
	 */
	function load_all_wcff_fields( $pid, $type = "wccpf", $location = "product-page" ) {	
		$fields = array();
		$all_fields = array();		
		$this->wcff_key_prefix = $type ."_";		
		$args = array( 'post_type' => $type, 'order' => 'ASC', 'posts_per_page' => -1 );
		$wcffs = get_posts( $args );	
		
		if( count( $wcffs ) > 0 ) {
			foreach ( $wcffs as $wcff ) {				
				$fields = array();		
				$crules_applicable = false;
				$lrules_applicable = true;
						
				$meta = get_post_meta( $wcff->ID );
				$condition_rules = $this->load_wcff_condition_rules( $wcff->ID );
				$condition_rules = json_decode( $condition_rules, true );
				
				if( is_array( $condition_rules ) ) {
					$crules_applicable = $this->check_wcff_for_product( $pid, $condition_rules );
				} else {
					$crules_applicable = true;
				}
				
				if( $type == "wccaf" ) {
					$location_rules = get_post_meta( $wcff->ID, $this->wcff_key_prefix.'location_rules', true );
					$location_rules = json_decode( $location_rules, true );
					
					if( is_array( $location_rules ) && $location != "any" ) {						
						$lrules_applicable = $this->check_wcff_for_location( $pid, $location_rules, $location );						
					} else {
						$lrules_applicable = true;
					}
				}
				
				if( $crules_applicable && $lrules_applicable ) {
					foreach ( $meta as $key => $val ) {						
						if( preg_match('/'. $this->wcff_key_prefix .'/', $key) ) {
							if( $key != $this->wcff_key_prefix.'condition_rules' && $key != $this->wcff_key_prefix.'location_rules' && $key != $this->wcff_key_prefix.'group_rules' ) {
								$fields[ $key ] = json_decode( $val[0], true );
							}
						}
					}
					$this->usort_by_column( $fields, "order" );
					/* Updated from V 1.3.5 - Added fields group title as a key */
					$all_fields[ $wcff->post_title ] = $fields;
				}				
			}
		}
		
		return $all_fields;
	}
	
	/**
	 * @param 	INT 		$pid	- Product Id
	 * @param 	ARRAY 		$groups
	 * @return 	boolean
	 * @todo	WCFF Condition Rules Engine, This is function used to determine whether or not to include 
	 * 			a particular wccpf group to a particular Product  	
	 */
	function check_wcff_for_product( $pid, $groups ) {
		$matches = array();
		$final_matches = array();
		foreach ( $groups as $rules ) {
			$ands = array();
			foreach ( $rules as $rule ) {
				if( $rule["context"] == "product" ) {
					if( $rule["endpoint"] == -1 ) {
						$ands[] = ( $rule["logic"] == "==" );						
					} else {
						if( $rule["logic"] == "==") {							
							$ands[] = ( $pid == $rule["endpoint"] );
						} else {
							$ands[] = ( $pid != $rule["endpoint"] );
						}	
					}				
				} else if( $rule["context"] == "product_cat" ) {
					if( $rule["endpoint"] == -1 ) {						
						$ands[] = ( $rule["logic"] == "==" );
					} else {
						if( $rule["logic"] == "==") {						
							$ands[] = has_term( $rule["endpoint"], 'product_cat', $pid );
						} else {
							$ands[] = !has_term( $rule["endpoint"], 'product_cat', $pid );
						}
					}
				}  else if( $rule["context"] == "product_tag" ) {
					if( $rule["endpoint"] == -1 ) {						
						$ands[] = ( $rule["logic"] == "==" );
					} else {
						if( $rule["logic"] == "==") {						
							$ands[] = has_term( $rule["endpoint"], 'product_tag', $pid );
						} else {
							$ands[] = !has_term( $rule["endpoint"], 'product_tag', $pid );
						}
					}
				}  else if( $rule["context"] == "product_type" ) {
					if( $rule["endpoint"] == -1 ) {
						$ands[] = ( $rule["logic"] == "==" );						
					} else {
						$ptype = wp_get_object_terms( $pid, 'product_type' );
						$ands[] = ( $ptype[0]->slug == $rule["endpoint"] );						
					}
				}
			}
			$matches[] = $ands;
		}
		
		foreach ( $matches as $match ) {
			$final_matches[] = !in_array( false, $match );
		}
		
		return in_array( true, $final_matches );
	}
	
	/**	 
	 * @param INT 		$pid
	 * @param ARRAY		$groups
	 * @param STRING	$location
	 * @todo			WCFF Location Rules Engine, This is function used to determine where doesa  particular wccaf fields group 
	 * 					to be placed. in the product view, product cat view or one of any product data sections ( Tabs )
	 * 					applicable only for wccaf post_type	  	
	 */
	function check_wcff_for_location( $pid, $groups, $location ) {		
		foreach ( $groups as $rules ) {
			foreach ( $rules as $rule ) {
				if( $rule["context"] == "location_product_data" ) {
					if( $rule["endpoint"] == $location && $rule["logic"] == "==" ) {
						return true;
					}				
				} 
				if( $rule["context"] == "location_product" && $location == "admin_head-post.php" ) {
					return true;
				}
				if( $rule["context"] == "location_product_cat" && ( $location == "product_cat_add_form_fields" || $location == "product_cat_edit_form_fields" ) )  {
					return true;
				}
			}
		}				
		return false;
	}
	
	function load_wcff_condition_rules( $pid ) {
		/* Since we have renamed 'group_rules' meta as 'condition_rules' we need to make sure it is upto date
		 * and we remove the old 'group_rules' meta as well
		 *  */
		$rules = get_post_meta( $pid, $this->wcff_key_prefix.'group_rules', true ); 
		if( $rules && $rules != "" ) {
			delete_post_meta( $pid, $this->wcff_key_prefix.'group_rules' );
			update_post_meta( $pid, $this->wcff_key_prefix.'condition_rules', $rules );
		}
	 	return get_post_meta( $pid, $this->wcff_key_prefix.'condition_rules', true );	 	
	}
	
	function load_wcff_location_rules( $pid ) {
		return get_post_meta( $pid, $this->wcff_key_prefix.'location_rules', true );
	}
	
	function load_wcff_add_location_rules() {
		$location_rules = array();
		$args = array( 'post_type' => "wccaf", 'order' => 'ASC', 'posts_per_page' => -1 );
		$wcffs = get_posts( $args );		
		if( count( $wcffs ) > 0 ) {
			foreach ( $wcffs as $wcff ) {
				$temp_rules = get_post_meta( $wcff->ID, 'wccaf_location_rules', true );
				$temp_rules = json_decode( $temp_rules, true );
				$location_rules = array_merge( $location_rules, $temp_rules );
			}
		}		
		return $location_rules;
	}

	function save_wcff_rules( $post_id, $post, $update ) {		
		if( $post->post_type != "wccpf" && $post->post_type != "wccaf" ) {
			return;
		}			
		$this->wcff_key_prefix = $post->post_type . "_";		
		if( isset( $_REQUEST["wcff_condition_rules"] ) ) {
			delete_post_meta( $post_id, $this->wcff_key_prefix.'condition_rules' );
			add_post_meta( $post_id, $this->wcff_key_prefix.'condition_rules', $_REQUEST["wcff_condition_rules"] );
		}
		if( isset( $_REQUEST["wcff_location_rules"] ) ) {
			delete_post_meta( $post_id, $this->wcff_key_prefix.'location_rules' );
			add_post_meta( $post_id, $this->wcff_key_prefix.'location_rules', $_REQUEST["wcff_location_rules"] );
		}						
		$this->update_wcff_fields_order( $post_id );
		return true;
	}
	
	function update_wcff_fields_order( $pid ) {
		$fields = $this->load_wcff_fields( $pid, false );
		foreach ( $fields as $key => $field ) {
			$field["order"] = $_REQUEST[ $key."_order" ];
			update_post_meta( $pid, $key, wp_slash( json_encode( $field ) ) );
		}
		return true;
	}
	
	function load_wcff_field( $pid, $mkey ) {
		return get_post_meta( $pid, $mkey, true );
	}
	
	function save_wcff_field( $pid, $payload ) {		
		if( !isset( $payload["name"] ) || $payload["name"] == "_" || $payload["name"] == "" ) {
			$payload["name"] = $this->url_slug( $payload["label"], array( 'delimiter' => '_' ) );
		}
		return add_post_meta( $pid, $this->wcff_key_prefix.$payload["name"], wp_slash( json_encode( $payload ) ) );
	}
	
	function update_wcff_field( $pid, $payload ) {
		if( !isset( $payload["key"] ) || $payload["name"] == "_" || $payload["key"] == "" ) {
			$payload["key"] = $this->url_slug( $payload["label"], array( 'delimiter' => '_' ) );
		}
		return update_post_meta( $pid, $payload["key"], wp_slash( json_encode( $payload ) ) );
	}
	
	function remove_wcff_field( $pid, $mkey ) {
		return delete_post_meta( $pid, $mkey );
	}

	function usort_by_column( &$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}	
		array_multisort( $sort_col, $dir, $arr);
	}
	
	/**
	 * Create a web friendly URL slug from a string.
	 * 
	 * @author Sean Murphy <sean@iamseanmurphy.com>
	 * @copyright Copyright 2012 Sean Murphy. All rights reserved.
	 * @license http://creativecommons.org/publicdomain/zero/1.0/
	 *
	 * @param string $str
	 * @param array $options
	 * @return string
	 */
	function url_slug( $str, $options = array( )) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
		$defaults = array(
				'delimiter' => '-',
				'limit' => null,
				'lowercase' => true,
				'replacements' => array(),
				'transliterate' => false,
		);
	
		// Merge options
		$options = array_merge($defaults, $options);
	
		$char_map = array(
				// Latin
				'Ã€' => 'A', 'Ã�' => 'A', 'Ã‚' => 'A', 'Ãƒ' => 'A', 'Ã„' => 'A', 'Ã…' => 'A', 'Ã†' => 'AE', 'Ã‡' => 'C',
				'Ãˆ' => 'E', 'Ã‰' => 'E', 'ÃŠ' => 'E', 'Ã‹' => 'E', 'ÃŒ' => 'I', 'Ã�' => 'I', 'ÃŽ' => 'I', 'Ã�' => 'I',
				'Ã�' => 'D', 'Ã‘' => 'N', 'Ã’' => 'O', 'Ã“' => 'O', 'Ã”' => 'O', 'Ã•' => 'O', 'Ã–' => 'O', 'Å�' => 'O',
				'Ã˜' => 'O', 'Ã™' => 'U', 'Ãš' => 'U', 'Ã›' => 'U', 'Ãœ' => 'U', 'Å°' => 'U', 'Ã�' => 'Y', 'Ãž' => 'TH',
				'ÃŸ' => 'ss',
				'Ã ' => 'a', 'Ã¡' => 'a', 'Ã¢' => 'a', 'Ã£' => 'a', 'Ã¤' => 'a', 'Ã¥' => 'a', 'Ã¦' => 'ae', 'Ã§' => 'c',
				'Ã¨' => 'e', 'Ã©' => 'e', 'Ãª' => 'e', 'Ã«' => 'e', 'Ã¬' => 'i', 'Ã­' => 'i', 'Ã®' => 'i', 'Ã¯' => 'i',
				'Ã°' => 'd', 'Ã±' => 'n', 'Ã²' => 'o', 'Ã³' => 'o', 'Ã´' => 'o', 'Ãµ' => 'o', 'Ã¶' => 'o', 'Å‘' => 'o',
				'Ã¸' => 'o', 'Ã¹' => 'u', 'Ãº' => 'u', 'Ã»' => 'u', 'Ã¼' => 'u', 'Å±' => 'u', 'Ã½' => 'y', 'Ã¾' => 'th',
				'Ã¿' => 'y',
				// Latin symbols
				'Â©' => '(c)',
				// Greek
				'Î‘' => 'A', 'Î’' => 'B', 'Î“' => 'G', 'Î”' => 'D', 'Î•' => 'E', 'Î–' => 'Z', 'Î—' => 'H', 'Î˜' => '8',
				'Î™' => 'I', 'Îš' => 'K', 'Î›' => 'L', 'Îœ' => 'M', 'Î�' => 'N', 'Îž' => '3', 'ÎŸ' => 'O', 'Î ' => 'P',
				'Î¡' => 'R', 'Î£' => 'S', 'Î¤' => 'T', 'Î¥' => 'Y', 'Î¦' => 'F', 'Î§' => 'X', 'Î¨' => 'PS', 'Î©' => 'W',
				'Î†' => 'A', 'Îˆ' => 'E', 'ÎŠ' => 'I', 'ÎŒ' => 'O', 'ÎŽ' => 'Y', 'Î‰' => 'H', 'Î�' => 'W', 'Îª' => 'I',
				'Î«' => 'Y',
				'Î±' => 'a', 'Î²' => 'b', 'Î³' => 'g', 'Î´' => 'd', 'Îµ' => 'e', 'Î¶' => 'z', 'Î·' => 'h', 'Î¸' => '8',
				'Î¹' => 'i', 'Îº' => 'k', 'Î»' => 'l', 'Î¼' => 'm', 'Î½' => 'n', 'Î¾' => '3', 'Î¿' => 'o', 'Ï€' => 'p',
				'Ï�' => 'r', 'Ïƒ' => 's', 'Ï„' => 't', 'Ï…' => 'y', 'Ï†' => 'f', 'Ï‡' => 'x', 'Ïˆ' => 'ps', 'Ï‰' => 'w',
				'Î¬' => 'a', 'Î­' => 'e', 'Î¯' => 'i', 'ÏŒ' => 'o', 'Ï�' => 'y', 'Î®' => 'h', 'ÏŽ' => 'w', 'Ï‚' => 's',
				'ÏŠ' => 'i', 'Î°' => 'y', 'Ï‹' => 'y', 'Î�' => 'i',
				// Turkish
				'Åž' => 'S', 'Ä°' => 'I', 'Ã‡' => 'C', 'Ãœ' => 'U', 'Ã–' => 'O', 'Äž' => 'G',
				'ÅŸ' => 's', 'Ä±' => 'i', 'Ã§' => 'c', 'Ã¼' => 'u', 'Ã¶' => 'o', 'ÄŸ' => 'g',
				// Russian
				'Ð�' => 'A', 'Ð‘' => 'B', 'Ð’' => 'V', 'Ð“' => 'G', 'Ð”' => 'D', 'Ð•' => 'E', 'Ð�' => 'Yo', 'Ð–' => 'Zh',
				'Ð—' => 'Z', 'Ð˜' => 'I', 'Ð™' => 'J', 'Ðš' => 'K', 'Ð›' => 'L', 'Ðœ' => 'M', 'Ð�' => 'N', 'Ðž' => 'O',
				'ÐŸ' => 'P', 'Ð ' => 'R', 'Ð¡' => 'S', 'Ð¢' => 'T', 'Ð£' => 'U', 'Ð¤' => 'F', 'Ð¥' => 'H', 'Ð¦' => 'C',
				'Ð§' => 'Ch', 'Ð¨' => 'Sh', 'Ð©' => 'Sh', 'Ðª' => '', 'Ð«' => 'Y', 'Ð¬' => '', 'Ð­' => 'E', 'Ð®' => 'Yu',
				'Ð¯' => 'Ya',
				'Ð°' => 'a', 'Ð±' => 'b', 'Ð²' => 'v', 'Ð³' => 'g', 'Ð´' => 'd', 'Ðµ' => 'e', 'Ñ‘' => 'yo', 'Ð¶' => 'zh',
				'Ð·' => 'z', 'Ð¸' => 'i', 'Ð¹' => 'j', 'Ðº' => 'k', 'Ð»' => 'l', 'Ð¼' => 'm', 'Ð½' => 'n', 'Ð¾' => 'o',
				'Ð¿' => 'p', 'Ñ€' => 'r', 'Ñ�' => 's', 'Ñ‚' => 't', 'Ñƒ' => 'u', 'Ñ„' => 'f', 'Ñ…' => 'h', 'Ñ†' => 'c',
				'Ñ‡' => 'ch', 'Ñˆ' => 'sh', 'Ñ‰' => 'sh', 'ÑŠ' => '', 'Ñ‹' => 'y', 'ÑŒ' => '', 'Ñ�' => 'e', 'ÑŽ' => 'yu',
				'Ñ�' => 'ya',
				// Ukrainian
				'Ð„' => 'Ye', 'Ð†' => 'I', 'Ð‡' => 'Yi', 'Ò�' => 'G',
				'Ñ”' => 'ye', 'Ñ–' => 'i', 'Ñ—' => 'yi', 'Ò‘' => 'g',
				// Czech
				'ÄŒ' => 'C', 'ÄŽ' => 'D', 'Äš' => 'E', 'Å‡' => 'N', 'Å˜' => 'R', 'Å ' => 'S', 'Å¤' => 'T', 'Å®' => 'U',
				'Å½' => 'Z',
				'Ä�' => 'c', 'Ä�' => 'd', 'Ä›' => 'e', 'Åˆ' => 'n', 'Å™' => 'r', 'Å¡' => 's', 'Å¥' => 't', 'Å¯' => 'u',
				'Å¾' => 'z',
				// Polish
				'Ä„' => 'A', 'Ä†' => 'C', 'Ä˜' => 'e', 'Å�' => 'L', 'Åƒ' => 'N', 'Ã“' => 'o', 'Åš' => 'S', 'Å¹' => 'Z',
				'Å»' => 'Z',
				'Ä…' => 'a', 'Ä‡' => 'c', 'Ä™' => 'e', 'Å‚' => 'l', 'Å„' => 'n', 'Ã³' => 'o', 'Å›' => 's', 'Åº' => 'z',
				'Å¼' => 'z',
				// Latvian
				'Ä€' => 'A', 'ÄŒ' => 'C', 'Ä’' => 'E', 'Ä¢' => 'G', 'Äª' => 'i', 'Ä¶' => 'k', 'Ä»' => 'L', 'Å…' => 'N',
				'Å ' => 'S', 'Åª' => 'u', 'Å½' => 'Z',
				'Ä�' => 'a', 'Ä�' => 'c', 'Ä“' => 'e', 'Ä£' => 'g', 'Ä«' => 'i', 'Ä·' => 'k', 'Ä¼' => 'l', 'Å†' => 'n',
				'Å¡' => 's', 'Å«' => 'u', 'Å¾' => 'z'
		);
	
		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}
	
		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);
	
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
}

new wcff_dao();

?>