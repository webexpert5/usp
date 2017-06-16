<?php
/**
 * WF_Shipping_UPS class.
 *
 * @extends WC_Shipping_Method
 */
class WF_Shipping_UPS extends WC_Shipping_Method {

	private $endpoint = 'https://wwwcie.ups.com/ups.app/xml/Rate';

	private $pickup_code = array(
		'01' => "Daily Pickup",
		'03' => "Customer Counter",
		'06' => "One Time Pickup",
		'07' => "On Call Air",
		'19' => "Letter Center",
		'20' => "Air Service Center",
	);
    
    private $customer_classification_code = array(
        'NA' => "Default",
		'00' => "Rates Associated with Shipper Number",
		'01' => "Daily Rates",
		'04' => "Retail Rates",
		'53' => "Standard List Rates",
	);

	private $services = array(
		// Domestic
		"12" => "3 Day Select",
		"03" => "Ground",
		"02" => "2nd Day Air",
		"59" => "2nd Day Air AM",
		"01" => "Next Day Air",
		"13" => "Next Day Air Saver",
		"14" => "Next Day Air Early AM",

		// International
		"11" => "Standard",
		"07" => "Worldwide Express",
		"54" => "Worldwide Express Plus",
		"08" => "Worldwide Expedited",
		"65" => "Saver",
		
		// SurePost
		"92" =>	"SurePost Less than 1 lb",
		"93" =>	"SurePost 1 lb or Greater",
		"94" =>	"SurePost BPM",
		"95" =>	"SurePost Media",
	);

	private $eu_array = array('BE','BG','CZ','DK','DE','EE','IE','GR','ES','FR','HR','IT','CY','LV','LT','LU','HU','MT','NL','AT','PT','RO','SI','SK','FI','GB');
    
    private $no_postcode_country_array = array('AE','AF','AG','AI','AL','AN','AO','AW','BB','BF','BH','BI','BJ','BM','BO','BS','BT','BW','BZ','CD','CF','CG','CI','CK','CL','CM','CO','CR','CV','DJ','DM','DO','EC','EG','ER','ET','FJ','FK','GA','GD','GH','GI','GM','GN','GQ','GT','GW','GY','HK','HN','HT','IE','IQ','IR','JM','JO','KE','KH','KI','KM','KN','KP','KW','KY','LA','LB','LC','LK','LR','LS','LY','ML','MM','MO','MR','MS','MT','MU','MW','MZ','NA','NE','NG','NI','NP','NR','NU','OM','PA','PE','PF','PY','QA','RW','SA','SB','SC','SD','SL','SN','SO','SR','SS','ST','SV','SY','TC','TD','TG','TL','TO','TT','TV','TZ','UG','UY','VC','VE','VG','VN','VU','WS','XA','XB','XC','XE','XL','XM','XN','XS','YE','ZM','ZW');
	
	// Shipments Originating in the European Union
	private $euservices = array(
		"07" => "UPS Express",
		"08" => "UPS ExpeditedSM",
		"11" => "UPS Standard",
		"54" => "UPS Express PlusSM",
		"65" => "UPS Saver",
	);

	private $polandservices = array(
		"07" => "UPS Express",
		"08" => "UPS ExpeditedSM",
		"11" => "UPS Standard",
		"54" => "UPS Express PlusSM",
		"65" => "UPS Saver",
		"82" => "UPS Today Standard",
		"83" => "UPS Today Dedicated Courier",
		"84" => "UPS Today Intercity",
		"85" => "UPS Today Express",
		"86" => "UPS Today Express Saver",
	);

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->id                 = WF_UPS_ID;
		$this->method_title       = __( 'UPS (BASIC)', 'ups-woocommerce-shipping' );
		$this->method_description = __( 'The <strong>UPS</strong> extension obtains rates dynamically from the UPS API during cart/checkout.', 'ups-woocommerce-shipping' );
		
		// WF: Load UPS Settings.
		$ups_settings 		= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		$api_mode      		= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
		if( "Live" == $api_mode ) {
			$this->endpoint = 'https://www.ups.com/ups.app/xml/Rate';
		}
		else {
			$this->endpoint = 'https://wwwcie.ups.com/ups.app/xml/Rate';
		}
		
		$this->init();
	}

	/**
	 * Output a message or error
	 * @param  string $message
	 * @param  string $type
	 */
    public function debug( $message, $type = 'notice' ) {
        // Hard coding to 'notice' as recently noticed 'error' is breaking with wc_add_notice.
        $type = 'notice';
    	if ( $this->debug && !is_admin() ) { //WF: do not call wc_add_notice from admin.
    		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
    			wc_add_notice( $message, $type );
    		} else {
    			global $woocommerce;
    			$woocommerce->add_message( $message );
    		}
		}
    }

    /**
     * init function.
     *
     * @access public
     * @return void
     */
    private function init() {
		global $woocommerce;
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->enabled				= isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : $this->enabled;
		$this->title				= isset( $this->settings['title'] ) ? $this->settings['title'] : $this->method_title;
		$this->availability    		= isset( $this->settings['availability'] ) ? $this->settings['availability'] : 'all';
		$this->countries       		= isset( $this->settings['countries'] ) ? $this->settings['countries'] : array();
		$this->ups_user_name        	= isset( $this->settings['ups_user_name'] ) ? $this->settings['ups_user_name'] : '';

		$this->user_id         		= isset( $this->settings['user_id'] ) ? $this->settings['user_id'] : '';
		$this->password        		= isset( $this->settings['password'] ) ? $this->settings['password'] : '';
		$this->access_key      		= isset( $this->settings['access_key'] ) ? $this->settings['access_key'] : '';
		$this->shipper_number  		= isset( $this->settings['shipper_number'] ) ? $this->settings['shipper_number'] : '';
		$this->negotiated      		= isset( $this->settings['negotiated'] ) && $this->settings['negotiated'] == 'yes' ? true : false;
		$this->origin_postcode 		= isset( $this->settings['origin_postcode'] ) ? $this->settings['origin_postcode'] : '';
		$this->origin_country_state = isset( $this->settings['origin_country_state'] ) ? $this->settings['origin_country_state'] : '';
		$this->debug      			= isset( $this->settings['debug'] ) && $this->settings['debug'] == 'yes' ? true : false;
		$this->api_mode      		= isset( $this->settings['api_mode'] ) ? $this->settings['api_mode'] : 'Test';
		
		// Pickup and Destination
		$this->pickup			= isset( $this->settings['pickup'] ) ? $this->settings['pickup'] : '01';
        $this->customer_classification = isset( $this->settings['customer_classification'] ) ? $this->settings['customer_classification'] : '99';
		$this->residential		= isset( $this->settings['residential'] ) && $this->settings['residential'] == 'yes' ? true : false;

		// Services and Packaging
		$this->offer_rates     	= isset( $this->settings['offer_rates'] ) ? $this->settings['offer_rates'] : 'all';
        $this->fallback		   	= ! empty( $this->settings['fallback'] ) ? $this->settings['fallback'] : '';
		$this->conversion_rate		   	= ! empty( $this->settings['conversion_rate'] ) ? $this->settings['conversion_rate'] : '';
		$this->packing_method  	= isset( $this->settings['packing_method'] ) ? $this->settings['packing_method'] : 'per_item';
		$this->custom_services  = isset( $this->settings['services'] ) ? $this->settings['services'] : array();
		$this->insuredvalue 	= isset( $this->settings['insuredvalue'] ) && $this->settings['insuredvalue'] == 'yes' ? true : false;

		// Units
		$this->units			= isset( $this->settings['units'] ) ? $this->settings['units'] : 'imperial';

		if ( $this->units == 'metric' ) {
			$this->weight_unit = 'KGS';
			$this->dim_unit    = 'CM';
		} else {
			$this->weight_unit = 'LBS';
			$this->dim_unit    = 'IN';
		}

		if (strstr($this->origin_country_state, ':')) :
			// WF: Following strict php standards.
			$origin_country_state_array = explode(':',$this->origin_country_state);
    		$this->origin_country = current($origin_country_state_array);
			$origin_country_state_array = explode(':',$this->origin_country_state);
    		$this->origin_state   = end($origin_country_state_array);
    	else :
    		$this->origin_country = $this->origin_country_state;
    		$this->origin_state   = '';
    	endif;
		$this->origin_addressline = isset($this->settings['origin_addressline']) ? $this->settings['origin_addressline'] : '';
		$this->origin_city = isset($this->settings['origin_city']) ? $this->settings['origin_city'] : '';
        $this->origin_custom_state   = (isset( $this->settings['origin_custom_state'] )&& !empty($this->settings['origin_custom_state'])) ? $this->settings['origin_custom_state'] : $this->origin_state;
		
		// COD selected
		$this->cod=false;
		$this->cod_total=0;

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'clear_transients' ) );

	}

	/**
	 * environment_check function.
	 *
	 * @access public
	 * @return void
	 */
	private function environment_check() {
		global $woocommerce;

		$error_message = '';

		// WF: Print Label - Start
		// Check for UPS User Name
		if ( ! $this->ups_user_name && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but Your Name has not been set.', 'ups-woocommerce-shipping' ) .'</p>';
		}
		// WF: Print Label - End
		
		// Check for UPS User ID
		if ( ! $this->user_id && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but the UPS User ID has not been set.', 'ups-woocommerce-shipping' ) . '</p>';
		}

		// Check for UPS Password
		if ( ! $this->password && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but the UPS Password has not been set.', 'ups-woocommerce-shipping' ) . '</p>';
		}

		// Check for UPS Access Key
		if ( ! $this->access_key && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but the UPS Access Key has not been set.', 'ups-woocommerce-shipping' ) . '</p>';
		}

		// Check for UPS Shipper Number
		if ( ! $this->shipper_number && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but the UPS Shipper Number has not been set.', 'ups-woocommerce-shipping' ) . '</p>';
		}

		// Check for Origin Postcode
		if ( ! $this->origin_postcode && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but the origin postcode has not been set.', 'ups-woocommerce-shipping' ) . '</p>';
		}

		// Check for Origin country
		if ( ! $this->origin_country_state && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but the origin country/state has not been set.', 'ups-woocommerce-shipping' ) . '</p>';
		}

		// If user has selected to pack into boxes,
		// Check if at least one UPS packaging is chosen, or a custom box is defined
		if ( ( $this->packing_method == 'box_packing' ) && ( $this->enabled == 'yes' ) ) {
			if ( empty( $this->ups_packaging )  && empty( $this->boxes ) ){
				$error_message .= '<p>' . __( 'UPS is enabled, and Parcel Packing Method is set to \'Pack into boxes\', but no UPS Packaging is selected and there are no custom boxes defined. Items will be packed individually.', 'ups-woocommerce-shipping' ) . '</p>';
			}
		}

		// Check for at least one service enabled
		$ctr=0;
		if ( isset($this->custom_services ) && is_array( $this->custom_services ) ){
			foreach ( $this->custom_services as $key => $values ){
				if ( $values['enabled'] == 1)
					$ctr++;
			}
		}
		if ( ( $ctr == 0 ) && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'UPS is enabled, but there are no services enabled.', 'ups-woocommerce-shipping' ) . '</p>';
		}


		if ( ! $error_message == '' ) {
			echo '<div class="error">';
			echo $error_message;
			echo '</div>';
		}
	}

	/**
	 * admin_options function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_options() {
		// Check users environment supports this method
		$this->environment_check();
		
		?>
		<div class="wf-banner updated below-h2">
  			<p class="main">
			<ul>
				<li style='color:red;'><strong>Your Business is precious! Go Premium!</li></strong>
				<li><strong>WooForce UPS Premium version for WooCommerce streamlines your complete shipping process and saves time.</strong></li>
				<li><strong>- Timely compatibility updates and bug fixes.</strong ></li>
				<li><strong>- Premium Support:</strong> Faster and time bound response for support requests.</li>
				<li><strong>- Option to enable Daily Rates, which gives same rates as UPS calculator.</strong></li>
				<li><strong>- More Features:</strong> Label Printing with Postage, Automatic Shipment Tracking, Box Packing, Weight based Packing and Many More..</li>
			</ul>
			</p>
			<p><a href="https://www.xadapter.com/product/woocommerce-ups-shipping-plugin-with-print-label/" target="_blank" class="button button-primary">Upgrade to Premium Version</a> <a href="http://upswoodemo.wooforce.com/wp-admin/admin.php?page=wc-settings&tab=shipping&section=wf_shipping_ups/" target="_blank" class="button">Live Demo</a></p>
		</div>
		<style>
		.wf-banner img {
			float: right;
			margin-left: 1em;
			padding: 15px 0
		}
		</style>
		<?php 

		// Show settings
		parent::admin_options();
	}

	/**
	 *
	 * generate_single_select_country_html function
	 *
	 * @access public
	 * @return void
	 */
	function generate_single_select_country_html() {
		global $woocommerce;

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="origin_country"><?php _e( 'Origin Country', 'ups-woocommerce-shipping' ); ?></label>
			</th>
            <td class="forminp"><select name="woocommerce_ups_origin_country_state" id="woocommerce_ups_origin_country_state" style="width: 250px;" data-placeholder="<?php _e('Choose a country&hellip;', 'woocommerce'); ?>" title="Country" class="chosen_select">
	        	<?php echo $woocommerce->countries->country_dropdown_options( $this->origin_country, $this->origin_state ? $this->origin_state : '*' ); ?>
	        </select>
       		</td>
       	</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * generate_services_html function.
	 *
	 * @access public
	 * @return void
	 */
	function generate_services_html() {
		ob_start();
		?>
		<tr valign="top" id="service_options">
			<td class="forminp" colspan="2" style="padding-left:0px">
				<table class="ups_services widefat">
					<thead>
						<th class="sort">&nbsp;</th>
						<th><?php _e( 'Service(s)', 'ups-woocommerce-shipping' ); ?></th>
					</thead>					
					<tbody>
						<?php
							$sort = 0;
							$this->ordered_services = array();

							if ( $this->origin_country == 'PL' ) {
								$use_services = $this->polandservices;
							} elseif ( in_array( $this->origin_country, $this->eu_array ) ) {
								$use_services = $this->euservices;
							} else {
								$use_services = $this->services;
							}

							foreach ( $use_services as $code => $name ) {

								if ( isset( $this->custom_services[ $code ]['order'] ) ) {
									$sort = $this->custom_services[ $code ]['order'];
								}

								while ( isset( $this->ordered_services[ $sort ] ) )
									$sort++;

								$this->ordered_services[ $sort ] = array( $code, $name );

								$sort++;
							}

							ksort( $this->ordered_services );

							foreach ( $this->ordered_services as $value ) {
								$code = $value[0];
								$name = $value[1];
								?>
								<tr>
									<td class="sort"><input type="hidden" class="order" name="ups_service[<?php echo $code; ?>][order]" value="<?php echo isset( $this->custom_services[ $code ]['order'] ) ? $this->custom_services[ $code ]['order'] : ''; ?>" /></td>
									<td><input type="checkbox" name="ups_service[<?php echo $code; ?>][enabled]" <?php checked( ( ! isset( $this->custom_services[ $code ]['enabled'] ) || ! empty( $this->custom_services[ $code ]['enabled'] ) ), true ); ?> /><label><?php echo $name; ?></label></td>
								</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<style type="text/css">
					.ups_services{
						width: 51.5%;
					}
					.ups_services td {
						vertical-align: middle;
						//padding: 4px 1px;
					}
					.ups_services th {
						padding: 9px 7px;
					}
					.ups_services th.sort {
						width: 16px;
						padding: 0 16px;
					}
					.ups_services td.sort {
						cursor: move;
						width: 16px;
						//padding: 0 16px;
						cursor: move;
						background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAHUlEQVQYV2O8f//+fwY8gJGgAny6QXKETRgEVgAAXxAVsa5Xr3QAAAAASUVORK5CYII=) no-repeat center;					}
				</style>
				<script type="text/javascript">

					jQuery(window).load(function(){

						// Ordering
						jQuery('.ups_services tbody').sortable({
							items:'tr',
							cursor:'move',
							axis:'y',
							handle: '.sort',
							scrollSensitivity:40,
							forcePlaceholderSize: true,
							helper: 'clone',
							opacity: 0.65,
							placeholder: 'wc-metabox-sortable-placeholder',
							start:function(event,ui){
								ui.item.css('baclbsround-color','#f6f6f6');
							},
							stop:function(event,ui){
								ui.item.removeAttr('style');
								ups_services_row_indexes();
							}
						});

						function ups_services_row_indexes() {
							jQuery('.ups_services tbody tr').each(function(index, el){
								jQuery('input.order', el).val( parseInt( jQuery(el).index('.ups_services tr') ) );
							});
						};

					});

				</script>
		<?php
		return ob_get_clean();
	}

	/**
	 * validate_single_select_country_field function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function validate_single_select_country_field( $key ) {

		if ( isset( $_POST['woocommerce_ups_origin_country_state'] ) )
			return $_POST['woocommerce_ups_origin_country_state'];
		return '';
	}

	/**
	 * validate_services_field function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function validate_services_field( $key ) {
		$services         = array();
		$posted_services  = $_POST['ups_service'];

		foreach ( $posted_services as $code => $settings ) {

			$services[ $code ] = array(
				'order'              => woocommerce_clean( $settings['order'] ),
				'enabled'            => isset( $settings['enabled'] ) ? true : false,
			);

		}

		return $services;
	}

	/**
	 * clear_transients function.
	 *
	 * @access public
	 * @return void
	 */
	public function clear_transients() {
		global $wpdb;

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_ups_quote_%') OR `option_name` LIKE ('_transient_timeout_ups_quote_%')" );
	}

    /**
     * init_form_fields function.
     *
     * @access public
     * @return void
     */
    public function init_form_fields() {
	    global $woocommerce;

		if ( WF_UPS_ADV_DEBUG_MODE == "on" ) { // Test mode is only for development purpose.
            $api_mode_options = array(
                'Test'           => __( 'Test', 'ups-woocommerce-shipping' ),
            );
        }
        else {
            $api_mode_options = array(
                'Live'           => __( 'Live', 'ups-woocommerce-shipping' ),
                'Test'           => __( 'Test', 'ups-woocommerce-shipping' ),
            );
        }
		
    	$this->form_fields  = array(
			'enabled'                => array(
				'title'              => __( 'Realtime Rates', 'ups-woocommerce-shipping' ),
				'type'               => 'checkbox',
				'label'              => __( 'Enable', 'ups-woocommerce-shipping' ),
				'default'            => 'no',
                'description'        => __( 'Enable realtime rates on Cart/Checkout page.', 'ups-woocommerce-shipping' ),
                'desc_tip'           => true
			),
			'title'                  => array(
				'title'              => __( 'UPS Method Title', 'ups-woocommerce-shipping' ),
				'type'               => 'text',
				'description'        => __( 'This controls the title which the user sees during checkout.', 'ups-woocommerce-shipping' ),
				'default'            => __( 'UPS', 'ups-woocommerce-shipping' ),
                'desc_tip'           => true
			),
		    'availability'           => array(
				'title'              => __( 'Method Availability', 'ups-woocommerce-shipping' ),
				'type'               => 'select',
				'default'            => 'all',
				'class'              => 'availability',
				'options'            => array(
					'all'            => __( 'All Countries', 'ups-woocommerce-shipping' ),
					'specific'       => __( 'Specific Countries', 'ups-woocommerce-shipping' ),
				),
			),
			'countries'              => array(
				'title'              => __( 'Specific Countries', 'ups-woocommerce-shipping' ),
				'type'               => 'multiselect',
				'class'              => 'chosen_select',
				'css'                => 'width: 450px;',
				'default'            => '',
				'options'            => $woocommerce->countries->get_allowed_countries(),
			),
		    'debug'                  => array(
				'title'              => __( 'Debug Mode', 'ups-woocommerce-shipping' ),
				'label'              => __( 'Enable', 'ups-woocommerce-shipping' ),
				'type'               => 'checkbox',
				'default'            => 'no',
				'description'        => __( 'Enable debug mode to show debugging information on your cart/checkout.', 'ups-woocommerce-shipping' ),
                'desc_tip'           => true
			),
		    'api'                    => array(
				'title'              => __( 'Generic API Settings', 'ups-woocommerce-shipping' ),
				'type'               => 'title',
				'description'        => __( 'Obtain UPS account credentials by registering on UPS website.', 'ups-woocommerce-shipping' )
		    ),
			'api_mode' 			     => array(
				'title'              => __( 'API Mode', 'ups-woocommerce-shipping' ),
				'type'               => 'select',
				'default'            => 'yes',
				'options'            => $api_mode_options,
				'description'        => __( 'Set as Test to switch to UPS api test servers. Transaction will be treated as sample transactions by UPS.', 'ups-woocommerce-shipping' ),
                'desc_tip'           => true
			),
			'ups_user_name'       => array(
				'title'           => __( 'Your Name', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Enter your name', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
			'ups_display_name'    => array(
				'title'           => __( 'Attention Name', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Your business/attention name.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
		    'user_id'             => array(
				'title'           => __( 'UPS User ID', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Obtained from UPS after getting an account.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
		    'password'            => array(
				'title'           => __( 'UPS Password', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Obtained from UPS after getting an account.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
		    'access_key'          => array(
				'title'           => __( 'UPS Access Key', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Obtained from UPS after getting an account.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
		    'shipper_number'      => array(
				'title'           => __( 'UPS Account Number', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Obtained from UPS after getting an account.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
            'units'               => array(
				'title'           => __( 'Weight/Dimension Units', 'ups-woocommerce-shipping' ),
				'type'            => 'select',
				'description'     => __( 'Switch this to metric units, if you see "This measurement system is not valid for the selected country" errors.', 'ups-woocommerce-shipping' ),
				'default'         => 'imperial',
				'options'         => array(
				    'imperial'    => __( 'LB / IN', 'ups-woocommerce-shipping' ),
				    'metric'      => __( 'KG / CM', 'ups-woocommerce-shipping' ),
				),
                'desc_tip'        => true
		    ),
		    'negotiated'          => array(
				'title'           => __( 'Negotiated Rates', 'ups-woocommerce-shipping' ),
				'label'           => __( 'Enable', 'ups-woocommerce-shipping' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'description'     => __( 'Enable this if this shipping account has negotiated rates available.', 'ups-woocommerce-shipping' ),
                'desc_tip'        => true
			),
		    'insuredvalue'        => array(
				'title'           => __( 'Insurance Option', 'ups-woocommerce-shipping' ),
				'label'           => __( 'Enable', 'ups-woocommerce-shipping' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'description'     => __( 'Request Insurance to be included.', 'ups-woocommerce-shipping' ),
                'desc_tip'        => true
			),
		    'origin_city'      	  => array(
				'title'           => __( 'Origin City', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Origin City (Ship From City)', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
		    'origin_country_state'    => array(
				'type'                => 'single_select_country',
			),
            'origin_custom_state'        => array(
				'title'           => __( 'Origin State Code', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Specify shipper state province code if state not listed with Origin Country.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
		    'origin_postcode'     => array(
				'title'           => __( 'Origin Postcode', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'Ship From Zip/postcode.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
		    ),
			'services'            => array(
				'type'            => 'services'
			),
			'offer_rates'         => array(
				'title'           => __( 'Offer Rates', 'ups-woocommerce-shipping' ),
				'type'            => 'select',
				'description'     => '',
				'default'         => 'all',
				'options'         => array(
				    'all'         => __( 'Offer the customer all returned rates', 'ups-woocommerce-shipping' ),
				    'cheapest'    => __( 'Offer the customer the cheapest rate only', 'ups-woocommerce-shipping' ),
				),
		    ),
		    'fallback'            => array(
				'title'           => __( 'Fallback', 'ups-woocommerce-shipping' ),
				'type'            => 'text',
				'description'     => __( 'If UPS returns no matching rates, offer this amount for shipping so that the user can still checkout. Leave blank to disable.', 'ups-woocommerce-shipping' ),
				'default'         => '',
                'desc_tip'        => true
			),
        );   
    }   

    /**
     * calculate_shipping function.
     *
     * @access public
     * @param mixed $package
     * @return void
     */
    public function calculate_shipping( $package=array() ) {
    	global $woocommerce;

    	$rates            = array();
    	$ups_responses	  = array();
    	libxml_use_internal_errors( true );

		// Only return rates if the package has a destination including country, postcode
        if ( '' == $package['destination']['country'] ) {
            $this->debug( __('UPS: Country not yet supplied. Rates not requested.', 'ups-woocommerce-shipping') );
			return; 
		}
        
        if( in_array( $package['destination']['country'] , $this->no_postcode_country_array ) ) {
            if ( empty( $package['destination']['city'] ) ) {
                $this->debug( __('UPS: City not yet supplied. Rates not requested.', 'ups-woocommerce-shipping') );
                return;
            }
        }
        else if( ''== $package['destination']['postcode'] ) {
            $this->debug( __('UPS: Zip not yet supplied. Rates not requested.', 'ups-woocommerce-shipping') );
            return;
        }

    	$package_requests = $this->get_package_requests( $package );
		
    	if ( $package_requests ) {

			$rate_requests = $this->get_rate_requests( $package_requests, $package );

			if ( ! $rate_requests ) {
				$this->debug( __('UPS: No Services are enabled in admin panel.', 'ups-woocommerce-shipping') );
			}

			// get live or cached result for each rate
			foreach ( $rate_requests as $code => $request ) {

				$send_request           = str_replace( array( "\n", "\r" ), '', $request );
				$transient              = 'ups_quote_' . md5( $request );
				$cached_response        = get_transient( $transient );
				$ups_responses[ $code ] = false;

				if ( $cached_response === false ) {
					$response = wp_remote_post( $this->endpoint,
			    		array(
							'timeout'   => 70,
							'sslverify' => 0,
							'body'      => $send_request
					    )
					);

                    if ( is_wp_error( $response ) ) {
                        $error_string = $response->get_error_message();
                        $this->debug( 'UPS REQUEST FAILED: <pre>' . print_r( htmlspecialchars( $error_string ), true ) . '</pre>' );
                    }
					else if ( ! empty( $response['body'] ) ) {
						$ups_responses[ $code ] = $response['body'];
						set_transient( $transient, $response['body'], YEAR_IN_SECONDS );
					}

				} else {
					$ups_responses[ $code ] = $cached_response;
					$this->debug( __( 'UPS: Using cached response.', 'ups-woocommerce-shipping' ) );
				}

				$this->debug( 'UPS REQUEST: <pre>' . print_r( htmlspecialchars( $request ), true ) . '</pre>' );
				$this->debug( 'UPS RESPONSE: <pre>' . print_r( htmlspecialchars( $ups_responses[ $code ] ), true ) . '</pre>' );

			} // foreach ( $rate_requests )

			// parse the results
			foreach ( $ups_responses as $code => $response ) {

				$xml = simplexml_load_string( preg_replace('/<\?xml.*\?>/','', $response ) );

				if ( $this->debug ) {
					if ( ! $xml ) {
						$this->debug( __( 'Failed loading XML', 'ups-woocommerce-shipping' ), 'error' );
					}
				}

				if ( $xml->Response->ResponseStatusCode == 1 ) {

					$service_name = $this->services[ $code ];

					if ( $this->negotiated && isset( $xml->RatedShipment->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue ) )
						$rate_cost = (float) $xml->RatedShipment->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue;
					else
						$rate_cost = (float) $xml->RatedShipment->TotalCharges->MonetaryValue;

					$rate_id     = $this->id . ':' . $code;
					$rate_name   = $service_name . ' (' . $this->title . ')';

					// Name adjustment
					if ( ! empty( $this->custom_services[ $code ]['name'] ) )
						$rate_name = $this->custom_services[ $code ]['name'];

					// Cost adjustment %
					if ( ! empty( $this->custom_services[ $code ]['adjustment_percent'] ) )
						$rate_cost = $rate_cost + ( $rate_cost * ( floatval( $this->custom_services[ $code ]['adjustment_percent'] ) / 100 ) );
					// Cost adjustment
					if ( ! empty( $this->custom_services[ $code ]['adjustment'] ) )
						$rate_cost = $rate_cost + floatval( $this->custom_services[ $code ]['adjustment'] );

					// Sort
					if ( isset( $this->custom_services[ $code ]['order'] ) ) {
						$sort = $this->custom_services[ $code ]['order'];
					} else {
						$sort = 999;
					}

					$rates[ $rate_id ] = array(
						'id' 	=> $rate_id,
						'label' => $rate_name,
						'cost' 	=> $rate_cost,
						'sort'  => $sort
					);

				} else {
					// Either there was an error on this rate, or the rate is not valid (i.e. it is a domestic rate, but shipping international)
					$this->debug( sprintf( __( '[UPS] No rate returned for service code %s, %s (UPS code: %s)', 'ups-woocommerce-shipping' ),
					$code,
					$xml->Response->Error->ErrorDescription,
					$xml->Response->Error->ErrorCode ), 'error' );
				}

			} // foreach ( $ups_responses )

		} // foreach ( $package_requests )

		// Add rates
		if ( $rates ) {
            
            if( $this->conversion_rate ) {
                foreach ( $rates as $key => $rate ) {
					$rates[ $key ][ 'cost' ] = $rate[ 'cost' ] * $this->conversion_rate;
				}
            }

			if ( $this->offer_rates == 'all' ) {

				uasort( $rates, array( $this, 'sort_rates' ) );
				foreach ( $rates as $key => $rate ) {
					$this->add_rate( $rate );
				}

			} else {

				$cheapest_rate = '';

				foreach ( $rates as $key => $rate ) {
					if ( ! $cheapest_rate || $cheapest_rate['cost'] > $rate['cost'] )
						$cheapest_rate = $rate;
				}

				$cheapest_rate['label'] = $this->title;

				$this->add_rate( $cheapest_rate );

			}
		// Fallback
		} elseif ( $this->fallback ) {
			$this->add_rate( array(
				'id' 	=> $this->id . '_fallback',
				'label' => $this->title,
				'cost' 	=> $this->fallback,
				'sort'  => 0
			) );
			$this->debug( __('UPS: Using Fallback setting.', 'ups-woocommerce-shipping') );
		}
    }

    /**
     * sort_rates function.
     *
     * @access public
     * @param mixed $a
     * @param mixed $b
     * @return void
     */
    public function sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) return 0;
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
    }

    /**
     * get_package_requests
	 *
	 *
     *
     * @access private
     * @return void
     */
    private function get_package_requests( $package,$params=array()) {

	    // Choose selected packing
    	switch ( $this->packing_method ) {
	    	
	    	case 'per_item' :
	    	default :
	    		$requests = $this->per_item_shipping( $package,$params);
	    	break;
    	}

    	return $requests;
    }

	/**
	 * get_rate_requests
	 *
	 * Get rate requests for all
	 * @access private
	 * @return array of strings - XML
	 *
	 */
	private function get_rate_requests( $package_requests, $package ) {
		global $woocommerce;

		$customer = $woocommerce->customer;

		$rate_requests = array();

		foreach ( $this->custom_services as $code => $params ) {
			if ( 1 == $params['enabled'] ) {
				if($code==92){
					$package_requests_to_append = $this->get_package_requests( $package,array('service_code'=>$code));
				}
				else{
					$package_requests_to_append	= $package_requests;
				}
			
			// Security Header
			$request  = "<?xml version=\"1.0\" ?>" . "\n";
			$request .= "<AccessRequest xml:lang='en-US'>" . "\n";
			$request .= "	<AccessLicenseNumber>" . $this->access_key . "</AccessLicenseNumber>" . "\n";
			$request .= "	<UserId>" . $this->user_id . "</UserId>" . "\n";
			// Ampersand will break XML doc, so replace with encoded version.
			$valid_pass = str_replace( '&', '&amp;', $this->password );
			$request .= "	<Password>" . $valid_pass . "</Password>" . "\n";
			$request .= "</AccessRequest>" . "\n";
	    		$request .= "<?xml version=\"1.0\" ?>" . "\n";
	    		$request .= "<RatingServiceSelectionRequest>" . "\n";
	    		$request .= "	<Request>" . "\n";
	    		$request .= "	<TransactionReference>" . "\n";
	    		$request .= "		<CustomerContext>Rating and Service</CustomerContext>" . "\n";
	    		$request .= "		<XpciVersion>1.0</XpciVersion>" . "\n";
	    		$request .= "	</TransactionReference>" . "\n";
	    		$request .= "	<RequestAction>Rate</RequestAction>" . "\n";
	    		$request .= "	<RequestOption>Rate</RequestOption>" . "\n";
	    		$request .= "	</Request>" . "\n";
	    		$request .= "	<PickupType>" . "\n";
	    		$request .= "		<Code>" . $this->pickup . "</Code>" . "\n";
	    		$request .= "		<Description>" . $this->pickup_code[$this->pickup] . "</Description>" . "\n";
	    		$request .= "	</PickupType>" . "\n";
                
                if ( 'US' == $this->origin_country ) {
                    if ( $this->negotiated ) {
                        $request .= "	<CustomerClassification>" . "\n";
                        $request .= "		<Code>" . "00" . "</Code>" . "\n";
                        $request .= "	</CustomerClassification>" . "\n";   
                    }
                    elseif ( !empty( $this->customer_classification ) && $this->customer_classification != 'NA' ) {
                        $request .= "	<CustomerClassification>" . "\n";
                        $request .= "		<Code>" . $this->customer_classification . "</Code>" . "\n";
                        $request .= "	</CustomerClassification>" . "\n";   
                    }
                }

				// Shipment information
	    		$request .= "	<Shipment>" . "\n";
	    		$request .= "		<Description>WooCommerce Rate Request</Description>" . "\n";
	    		$request .= "		<Shipper>" . "\n";
	    		$request .= "			<ShipperNumber>" . $this->shipper_number . "</ShipperNumber>" . "\n";
	    		$request .= "			<Address>" . "\n";
	    		$request .= "				<AddressLine>" . $this->origin_addressline . "</AddressLine>" . "\n";
                $request .= $this->wf_get_postcode_city( $this->origin_country, $this->origin_city, $this->origin_postcode );
	    		$request .= "				<CountryCode>" . $this->origin_country . "</CountryCode>" . "\n";
	    		$request .= "			</Address>" . "\n";
	    		$request .= "		</Shipper>" . "\n";
	    		$request .= "		<ShipTo>" . "\n";
	    		$request .= "			<Address>" . "\n";
	    		$request .= "				<StateProvinceCode>" . $package['destination']['state'] . "</StateProvinceCode>" . "\n";
                
                $destination_city = strtoupper( $package['destination']['city'] );
                $destination_country = "";
                if ( ( "PR" == $package['destination']['state'] ) && ( "US" == $package['destination']['country'] ) ) {		
                        $destination_country = "PR";
                } else {
                        $destination_country = $package['destination']['country'];
                }
                $request .= $this->wf_get_postcode_city( $destination_country, $destination_city, $package['destination']['postcode'] );
                $request .= "				<CountryCode>" . $destination_country . "</CountryCode>" . "\n";
                
	    		if ( $this->residential ) {
	    		$request .= "				<ResidentialAddressIndicator></ResidentialAddressIndicator>" . "\n";
	    		}
	    		$request .= "			</Address>" . "\n";
	    		$request .= "		</ShipTo>" . "\n";
	    		$request .= "		<ShipFrom>" . "\n";
	    		$request .= "			<Address>" . "\n";
	    		$request .= "				<AddressLine>" . $this->origin_addressline . "</AddressLine>" . "\n";
                $request .= $this->wf_get_postcode_city( $this->origin_country, $this->origin_city, $this->origin_postcode );
	    		$request .= "				<CountryCode>" . $this->origin_country . "</CountryCode>" . "\n";
	    		if ( $this->negotiated && $this->origin_custom_state ) {
	    		$request .= "				<StateProvinceCode>" . $this->origin_custom_state . "</StateProvinceCode>" . "\n";
	    		}
	    		$request .= "			</Address>" . "\n";
	    		$request .= "		</ShipFrom>" . "\n";
	    		$request .= "		<Service>" . "\n";
	    		$request .= "			<Code>" . $code . "</Code>" . "\n";
	    		$request .= "		</Service>" . "\n";
				// packages
	    		foreach ( $package_requests_to_append as $key => $package_request ) {
	    			$request .= $package_request;
	    		}
				// negotiated rates flag
	    		if ( $this->negotiated ) {
	    		$request .= "		<RateInformation>" . "\n";
	    		$request .= "			<NegotiatedRatesIndicator />" . "\n";
	    		$request .= "		</RateInformation>" . "\n";
				}
	    		$request .= "	</Shipment>" . "\n";
	    		$request .= "</RatingServiceSelectionRequest>" . "\n";

				$rate_requests[$code] = $request;

			} // if (enabled)
		} // foreach()

		return $rate_requests;
	}

    private function wf_get_postcode_city($country, $city, $postcode){
        $request_part = "";
		if( in_array( $country, $this->no_postcode_country_array ) && !empty( $city ) ) {
            $request_part = "<City>" . $city . "</City>" . "\n";
        }
        else if ( empty( $city ) ) {
            $request_part = "<PostalCode>" . $postcode . "</PostalCode>" . "\n";
        }
        else {
            $request_part = " <City>" . $city . "</City>" . "\n";
            $request_part .= "<PostalCode>" . $postcode. "</PostalCode>" . "\n";
        }
        
        return $request_part;
	}

    /**
     * per_item_shipping function.
     *
     * @access private
     * @param mixed $package
     * @return mixed $requests - an array of XML strings
     */
    private function per_item_shipping( $package, $params=array() ) {
	    global $woocommerce;

	    $requests = array();

		$ctr=0;
		$this->cod=sizeof($package['contents'])>1?false:$this->cod; // For multiple packages COD is turned off
    	foreach ( $package['contents'] as $item_id => $values ) {
    		$ctr++;

    		if ( !( $values['quantity'] > 0 && $values['data']->needs_shipping() ) ) {
    			$this->debug( sprintf( __( 'Product #%d is virtual. Skipping.', 'ups-woocommerce-shipping' ), $ctr ) );
    			continue;
    		}

    		if ( ! $values['data']->get_weight() ) {
	    		$this->debug( sprintf( __( 'Product #%d is missing weight. Aborting.', 'ups-woocommerce-shipping' ), $ctr ), 'error' );
	    		return;
    		}

			// get package weight
    		$weight = woocommerce_get_weight( $values['data']->get_weight(), $this->weight_unit );

			// get package dimensions
    		if ( $values['data']->length && $values['data']->height && $values['data']->width ) {

				$dimensions = array( number_format( woocommerce_get_dimension( $values['data']->length, $this->dim_unit ), 2, '.', ''),
									 number_format( woocommerce_get_dimension( $values['data']->height, $this->dim_unit ), 2, '.', ''),
									 number_format( woocommerce_get_dimension( $values['data']->width, $this->dim_unit ), 2, '.', '') );
				sort( $dimensions );

			}

			// get quantity in cart
			$cart_item_qty = $values['quantity'];

			$request  = '<Package>' . "\n";
			$request .= '	<PackagingType>' . "\n";
			$request .= '		<Code>02</Code>' . "\n";
			$request .= '		<Description>Package/customer supplied</Description>' . "\n";
			$request .= '	</PackagingType>' . "\n";
			$request .= '	<Description>Rate</Description>' . "\n";

			if ( $values['data']->length && $values['data']->height && $values['data']->width ) {
				$request .= '	<Dimensions>' . "\n";
				$request .= '		<UnitOfMeasurement>' . "\n";
				$request .= '	 		<Code>' . $this->dim_unit . '</Code>' . "\n";
				$request .= '		</UnitOfMeasurement>' . "\n";
				$request .= '		<Length>' . $dimensions[2] . '</Length>' . "\n";
				$request .= '		<Width>' . $dimensions[1] . '</Width>' . "\n";
				$request .= '		<Height>' . $dimensions[0] . '</Height>' . "\n";
				$request .= '	</Dimensions>' . "\n";
			}
			if((isset($params['service_code'])&&$params['service_code']==92))// Surepost Less Than 1LBS
			{
				if($this->weight_unit=='LBS'){ // make sure weight in pounds
					$weight_ozs=$weight*16;
				}else{
					$weight_ozs=$weight*35.274; // From KG
				}
				$request .= '	<PackageWeight>' . "\n";
				$request .= '		<UnitOfMeasurement>' . "\n";
				$request .= '			<Code>OZS</Code>' . "\n";
				$request .= '		</UnitOfMeasurement>' . "\n";
				$request .= '		<Weight>' . $weight_ozs . '</Weight>' . "\n";
				$request .= '	</PackageWeight>' . "\n";
			}else{
				$request .= '	<PackageWeight>' . "\n";
				$request .= '		<UnitOfMeasurement>' . "\n";
				$request .= '			<Code>' . $this->weight_unit . '</Code>' . "\n";
				$request .= '		</UnitOfMeasurement>' . "\n";
				$request .= '		<Weight>' . $weight . '</Weight>' . "\n";
				$request .= '	</PackageWeight>' . "\n";
			}

			
			if( $this->insuredvalue || $this->cod ) {
				$request .= '	<PackageServiceOptions>' . "\n";
				// InsuredValue
				if( $this->insuredvalue ) {
			
					$request .= '		<InsuredValue>' . "\n";
					$request .= '			<CurrencyCode>' . get_woocommerce_currency() . '</CurrencyCode>' . "\n";
					// WF: Calculating monetary value of cart item for insurance.
					$request .= '			<MonetaryValue>' . (string) ( $values['data']->get_price() ). '</MonetaryValue>' . "\n";
					$request .= '		</InsuredValue>' . "\n";
				}
				//Code
				if($this->cod){
					
					$cod_value=$this->cod_total;
					
					$request.='<COD>'."\n";
					$request.=	'<CODCode>3</CODCode>'."\n";
					$request.=	'<CODFundsCode>0</CODFundsCode>'."\n";
					$request.=	'<CODAmount>'."\n";
					$request.=		'<CurrencyCode>'.get_woocommerce_currency().'</CurrencyCode>'."\n";
					$request.=		'<MonetaryValue>'.$cod_value.'</MonetaryValue>'."\n";
					$request.=	'</CODAmount>'."\n";
					$request.='</COD>'."\n";
				}
				$request .= '	</PackageServiceOptions>' . "\n";
			}
			$request .= '</Package>' . "\n";

			for ( $i=0; $i < $cart_item_qty ; $i++)
				$requests[] = $request;
    	}

		return $requests;
    }

    
    /**
     * wf_get_api_rate_box_data function.
     *
     * @access public
     * @return requests
     */
    public function wf_get_api_rate_box_data( $package, $packing_method ) {
	    $this->packing_method	= $packing_method;
		$requests 				= $this->get_package_requests($package);

		return $requests;
    }
	
	public function wf_set_cod_details($order){
		if($order->id){
			$this->cod=get_post_meta($order->id,'_wf_ups_cod',true);
			$this->cod_total=$order->get_total();
		}
	}

}
