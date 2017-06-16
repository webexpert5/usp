<?php
/**
 * @author 		: Saravana Kumar K
 * @author url  : iamsark.com
 * @copyright	: sarkware.com
 * Class which responsible for creating and maintaining check box field ( for both Product, Admin, as well as Check box fields's meta section )
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wcff_field_checkbox extends wcff_field {
	
	function __construct() {
		$this->name 		= 'checkbox';
		$this->label 		= "Check Box";
		$this->required 	= false;
		$this->valid		= true;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(
			'layout'		=>	'vertical',
			'choices'		=>	array(),
			'default_value'	=>	''			
		);	
		parent::__construct();
	}
	
	/**
	 * 
	 * @param string $type
	 * @return string
	 */
	function render_wcff_setup_fields( $type = "wccpf" ) { ob_start(); ?>
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Required', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Is this field Mandatory', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="required">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-required" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-required" value="no" checked/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
					</ul>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Message', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Message to display whenever the validation failed', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="text" data-param="message">
					<input type="text" id="wcff-field-type-meta-message" value="<?php echo esc_attr( $this->message ); ?>" />						
				</div>
			</td>
		</tr>		
		
		<?php if( $type == "wccaf" ) : ?>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Show on Product Page', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Whether to show this custom field on front end product page.', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="show_on_product_page">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-show_on_product_page" value="yes" /> <?php _e( 'Show in Product Page', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-show_on_product_page" value="no" checked /> <?php _e( 'Hide in Product Page', 'wc-fields-factory' ); ?></label></li>							
					</ul>						
				</div>
			</td>
		</tr>
		
		<?php endif; ?>
		
		<tr>
			<td class="summary">
				<?php if( $type == "wccpf" ) : ?>
				<label for="post_type"><?php _e( 'Visibility', 'wc-fields-factory' ); ?></label>
				<?php else: ?>
				<label for="post_type"><?php _e( 'Show on Cart & Checkout', 'wc-fields-factory' ); ?></label>
				<?php endif; ?>
				<p class="description"><?php _e( 'Whether to show this custom field on Cart & Checkout page.', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="visibility">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-visibility" value="yes" <?php echo ( $type == "wccpf" ) ? "checked" : ""; ?> /> <?php _e( 'Show in Cart & Checkout Page', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-visibility" value="no" <?php echo ( $type == "wccaf" ) ? "checked" : ""; ?> /> <?php _e( 'Hide in Cart & Checkout Page', 'wc-fields-factory' ); ?></label></li>							
					</ul>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Order Item Meta', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Whether to add this custom field to Order & Email.', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="order_meta">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-order_meta" value="yes" <?php echo ( $type == "wccpf" ) ? "checked" : ""; ?> /> <?php _e( 'Add as Order Meta', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-order_meta" value="no" <?php echo ( $type == "wccaf" ) ? "checked" : ""; ?> /> <?php _e( 'Do not add', 'wc-fields-factory' ); ?></label></li>							
					</ul>						
				</div>
			</td>
		</tr>
		
		<?php if( $type == "wccpf" ) : ?>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Options', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Enter each options on a new line like this', 'wc-fields-factory' ); ?><br/><br/>red|Red<br/>blue|Blue</p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="textarea" data-param="choices">
					<textarea rows="6" id="wcff-field-type-meta-choices"></textarea>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Default Options', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Enter each default options on a new line', 'wc-fields-factory' ); ?><br/><br/>blue|Blue</p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="textarea" data-param="default_value">
					<textarea rows="6" id="wcff-field-type-meta-default_value"></textarea>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Layout', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Row wise (or) Column wise', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="layout">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-layout" value="horizontal" checked /> <?php _e( 'Horizontal', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-layout" value="vertical" /> <?php _e( 'Vertical', 'wc-fields-factory' ); ?></label></li>
					</ul>						
				</div>
			</td>
		</tr>	
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Show login user only', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Show this field only if user has logged-in', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="login_user_field">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-login_user_field" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-login_user_field" value="no" checked /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>							
					</ul>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Editable', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Make this field editable on cart', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="cart_editable">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-cart_editable" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-cart_editable" value="no" checked /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>							
					</ul>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Field Class', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Assing to custom field class', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="text" data-param="field_class">
					<input type="text" id="wcff-field-type-meta-field_class" value="" placeholder="Class Name"/>					
				</div>
			</td>
		</tr>	
		
		<?php endif; ?>
		
		<?php if( $type == "wccaf" ) : ?>
				
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Single Or Multiple', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Admin field use single or multiple checkbox', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="check" data-param="single_or_multi">
					<ul class="wcff-field-layout-vertical">
						<li><label class="check_single_multi"><input type="checkbox" name="wcff-field-type-meta-single_or_multi" id="check_single_multi" value="true" /><span class="check_is_single"><?php _e( 'Single', 'wc-fields-factory' ); ?></span><span class="check_is_multi"><?php _e( 'Multi', 'wc-fields-factory' ); ?></span></label></li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Checkbox Value', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Value for this checkbox field.', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta wffaf-multi-container" data-type="textarea" data-param="choices">
					<textarea rows="6" id="wcff-field-type-meta-choices" placeholder="red|Red&#13;&#10;blue|Blue"></textarea>						
				</div>
				<div class="wcff-field-types-meta wffaf-single-container" data-type="text" data-param="checkbox_value">
					<input type="text" id="wcff-field-type-meta-checkbox_value" value="" />						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Value or Field', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Show value instead of field.?', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="showin_value">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-showin_value" value="yes" /> <?php _e( 'Value', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-showin_value" value="no" checked /> <?php _e( 'Field', 'wc-fields-factory' ); ?></label></li>							
					</ul>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Read Only', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Show this field as readonly on front end product page.', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="show_as_read_only">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-show_as_read_only" value="yes" /> <?php _e( 'Show as Read Only', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-show_as_read_only" value="no" checked /> <?php _e( 'Show as Normal', 'wc-fields-factory' ); ?></label></li>													
					</ul>						
				</div>
			</td>
		</tr>		
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Tips', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Whether to show tool tip icon or not', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="desc_tip">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-desc_tip" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-desc_tip" value="no" checked/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
					</ul>						
				</div>
			</td>
		</tr>
					
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Description', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Description about this field, if user clicked tool tip icon', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="textarea" data-param="description">
					<textarea rows="4" id="wcff-field-type-meta-description"></textarea>	
				</div>
			</td>
		</tr>
			
		<?php 
		endif; 
		return ob_get_clean();	
	}
	
	function render_product_field( $field ) {
		$field_class = isset( $field[ "field_class" ] ) ? " ".$field[ "field_class" ] : "";
		$show_value_front = isset( $field["showin_value"] ) ? $field["showin_value"] : "no";
		$wccpf_options = wcff()->option->get_options();
		$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$name_index = $fields_cloning == "yes" ? "_1" : "";
		if( isset( $field["login_user_field"] ) ){
			if( $field["login_user_field"] == "yes" ){
				if( !is_user_logged_in() ){
					return;
				}
			}
		}
		if( !isset( $field["is_admin_field"] ) ) {
		
		$layout = ( $field['layout'] == "horizontal" ) ? "wccpf-field-layout-horizontal" : "wccpf-field-layout-vertical"; 
		ob_start();	?>
	
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field ); ?>
			
			<ul class="<?php echo $layout; ?>">
			<?php 	
				$attr = '';
				$choices = explode( ";", $field["choices"] );
				$defaults = explode( ";", $field["default_value"] );

				foreach ( $choices as $choice ) {
					if( in_array( $choice, $defaults ) ) {
						$attr = 'checked="yes"';
					} else {
						$attr = '';
					}
					$key_val = explode( "|", $choice ); ?>
					<li><label><input type="checkbox" class="wccpf-field<?php echo $field_class; ?>" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>[]" value="<?php echo esc_attr( trim( $key_val[0] ) ) ?>" <?php echo $attr; ?> wccpf-type="checkbox" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" /> <?php echo esc_attr( trim( $key_val[1] ) ); ?></label></li>										
				<?php } ?>										
			</ul>
			<span class="wccpf-validation-message wccpf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
			
			<?php do_action( 'wccpf/after/field/rendering', $field ); ?>
		
		<?php else : ?>
	
		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?><?php echo $field_class.'-wrapper'; ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label class="<?php echo $field_class.'-label'; ?>" for="<?php echo esc_attr( $field["name"] . $name_index ); ?>"><?php echo esc_html( $field["label"] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label></td>
					<td class="wccpf_value">
						<ul class="<?php echo $layout; ?>">
						<?php 	
							$attr = '';
							$choices = explode( ";", $field["choices"] );
							$defaults = explode( ";", $field["default_value"] );

							foreach ( $choices as $choice ) {
								if( in_array( $choice, $defaults ) ) {
									$attr = 'checked="yes"';
								} else {
									$attr = '';
								}
								$key_val = explode( "|", $choice ); ?>
								<li><label><input type="checkbox" class="wccpf-field <?php echo $field_class; ?>" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>[]" value="<?php echo esc_attr( trim( $key_val[0] ) ) ?>" <?php echo $attr; ?> wccpf-type="checkbox" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" /> <?php echo esc_attr( trim( $key_val[1] ) ); ?></label></li>										
							<?php } ?>										
						</ul>
						<span class="wccpf-validation-message wccpf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
					</td>
				</tr>
			</tbody>
		</table>		
		
		<?php
		
		endif;
		
		} else {
	
		$readonly = isset( $field["show_as_read_only"] ) ? $field["show_as_read_only"] : "no";
		$readonly = ( $readonly == "yes" ) ? "disabled" : "";
		$admin_multiple_check = isset( $field["single_or_multi"] ) ? $field["single_or_multi"] : [];
		$is_multi_admin = sizeof( $admin_multiple_check ) == 0 ? false : $admin_multiple_check[0] == "true" ? true : false;		
			
		ob_start();	?>
				
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field ); ?>	
			
			<ul class="wccpf-field-layout-horizontal">						
				<?php 					
					$checked = "";
					if( isset( $field["value"] ) ){
						if( $field["value"] == $field["checkbox_value"] ) {
							$checked = "checked";
						}
					}
				?>				
				<?php if( $show_value_front == "yes" ) :
					if( $is_multi_admin ){
						$check_count = explode(";", $field["choices"] );
						$new_value = json_decode( $field["default_value"], true );
						for ( $i = 0; $i <  sizeof( $check_count ); $i++ ){
							$explode_val = explode( "|", $check_count[ $i ] );							
							if( isset( $new_value[ "wccaf_".$explode_val[0] ] ) ){
								if( $new_value[ "wccaf_".$explode_val[0] ] == $explode_val[0] ){ ?>
									<li><label><?php echo esc_attr( $explode_val[1]); ?> : <?php echo wp_kses_post( $explode_val[0]); ?></label></li>							
								<?php 
								} 
							}
						}
					} else { ?>			
						<li><label><?php echo esc_attr( $field['checkbox_value'] ); ?>  <?php echo wp_kses_post( $field['label'] ); ?></label></li>		
					<?php } ?>			
				<?php else :						
					if( $is_multi_admin ){
						$check_count = explode(";", $field["choices"] );
						$new_value = json_decode( $field["default_value"], true );
						for ( $i = 0; $i <  sizeof( $check_count ); $i++ ){
							$explode_val = explode( "|", $check_count[ $i ] );
							$checked = "";
							if( isset( $new_value[ "wccaf_".$explode_val[0] ] ) ) {
								if( $new_value[ "wccaf_".$explode_val[0] ] == $explode_val[0] ){
									$checked = "checked";
								} else {
									$checked = "";
								}
							}						 
				?>
					<li><label><input type="checkbox" class="wccpf-field" name="wccpf-field-<?php echo esc_attr( $explode_val[0]. $name_index ); ?>[]" value="<?php echo esc_attr( $explode_val[0]); ?>" <?php echo $checked; ?> wccpf-type="checkbox" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> /> <?php echo wp_kses_post( $explode_val[1]); ?></label></li>		
						<?php } ?>
					<?php } else { ?>
					<li><label><input type="checkbox" class="wccpf-field" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>[]" value="<?php echo esc_attr( $field['checkbox_value'] ); ?>" <?php echo $checked; ?> wccpf-type="checkbox" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> /> <?php echo wp_kses_post( $field['label'] ); ?></label></li>		
				<?php } endif; ?>
			</ul>			
			<?php if( $field["showin_value"] == "yes" ) : ?>
				<span class="wccpf-validation-message wccpf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
			<?php endif; ?>
			<?php do_action( 'wccpf/after/field/rendering', $field ); ?>
		
		<?php else : ?>
	
		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label for="<?php echo esc_attr( $field["name"] . $name_index ); ?>"><?php echo esc_html( $field["label"] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label></td>
					<td class="wccpf_value">
						<ul class="wccpf-field-layout-horizontal">						
							<?php 					
								$checked = "";
								if( isset( $field["value"] ) ){
									if( $field["value"] == $field["checkbox_value"] ) {
										$checked = "checked";
									}
								}
							?>		
							<?php if( $show_value_front == "yes" ) : 
							if( $is_multi_admin ){
									$check_count = explode(";", $field["choices"] );
									$new_value = json_decode( $field["default_value"], true );
									for ( $i = 0; $i <  sizeof( $check_count ); $i++ ){
										$explode_val = explode( "|", $check_count[ $i ] );
										if( isset( $new_value[ "wccaf_".$explode_val[0] ] ) ){											
											if( $new_value[ "wccaf_".$explode_val[0] ] == $explode_val[0] ){ ?>
											<li><label><?php echo esc_attr( $explode_val[1] ); ?> : <?php echo wp_kses_post( $explode_val[0] ); ?></label></li>							
										<?php }
										}
								   } 
							} else { ?>							
								<li><label><?php echo esc_attr( $field['checkbox_value'] ); ?> <?php echo wp_kses_post( $field['label'] ); ?></label></li>
							  <?php } ?>
							<?php else : 
								if( $is_multi_admin ){
									$check_count = explode(";", $field["choices"] );
									$new_value = json_decode( $field["default_value"], true );
									for ( $i = 0; $i <  sizeof( $check_count ); $i++ ){
										$explode_val = explode( "|", $check_count[ $i ] );
										$checked = "";
										if( isset( $new_value[ "wccaf_".$explode_val[0] ] ) ){											
											if( $new_value[ "wccaf_".$explode_val[0] ] == $explode_val[0] ) {
												$checked = "checked";
											} else {
												$checked = "";
											}
										}
										
							?>	
									<li><label><input type="checkbox" class="wccpf-field" name="wccpf-field-<?php echo esc_attr( $explode_val[0]. $name_index ); ?>" value="<?php echo esc_attr( $explode_val[0] ); ?>" <?php echo $checked; ?> wccpf-type="checkbox" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> /> <?php echo wp_kses_post( $explode_val[1]); ?></label></li>								
							<?php } 
							  } else { ?>		
								<li><label><input type="checkbox" class="wccpf-field" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>[]" value="<?php echo esc_attr( $field['checkbox_value'] ); ?>" <?php echo $checked; ?> wccpf-type="checkbox" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> /> <?php echo wp_kses_post( $field['label'] ); ?></label></li>								
							<?php } endif; ?>
						</ul>
						<?php if( $field["showin_value"] == "yes" ) : ?>
							<span class="wccpf-validation-message wccpf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>		
		
		<?php 
					
		endif;
		
		}		
		return ob_get_clean();	
	}
	
	function render_admin_field( $field ) { ob_start(); 
		$admin_multiple_check = isset( $field["single_or_multi"] ) ? $field["single_or_multi"] : [];
		$is_multi_admin = sizeof( $admin_multiple_check ) == 0 ? false : $admin_multiple_check[0] == "true" ? true : false;	
		$checked = "";
		if( $field["value"] == $field["checkbox_value"] ) {
			$checked = "checked";
		}		
		if( $field["location"] != "product_cat_add_form_fields" && $field["location"] != "product_cat_edit_form_fields" ) {
		
		?>
	
		<p class="form-field <?php echo esc_attr( $field['name'] ); ?>_field ">
			<label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label>
			<?php if( $is_multi_admin ) : 
				$check_count = explode(";", $field["choices"] );
				$new_value = json_decode( $field["value"], true );
				for ( $i = 0; $i <  sizeof( $check_count ); $i++ ){
					$checked = "";
					$explode_val = explode( "|", $check_count[ $i ] );					
					if( isset( $new_value[ "wccaf_".$explode_val[0] ] ) ) {
						if( $new_value[ "wccaf_".$explode_val[0] ] == $explode_val[0] ){
							$checked = "checked";
						} else {
							$checked = "";
						}
					}
			?>
			&nbsp;&nbsp;&nbsp;<?php echo $explode_val[1];?> <input type="checkbox" name="wccaf-field-<?php echo esc_attr( $explode_val[0] ); ?>" id="wccaf-field-<?php echo esc_attr( $explode_val[0] ); ?>" value="<?php echo esc_attr( $explode_val[0] ); ?>" <?php echo $checked; ?> class="wccaf-field" wccaf-type="checkbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
			<?php	}	?>				
			<?php else : ?>
				<input type="checkbox" name="<?php echo esc_attr( $field['name'] ); ?>" id="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['checkbox_value'] ); ?>" <?php echo $checked; ?> class="wccaf-field" wccaf-type="checkbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
			<?php endif; ?>
			<?php 
			if ( !empty( $field['description'] ) ) :
				if ( isset( $field['desc_tip'] ) && "no" != $field['desc_tip'] ) : ?>
					<img class="help_tip" data-tip="<?php echo wp_kses_post( $field['description'] ); ?>" src="<?php echo esc_url( wcff()->info["dir"] ); ?>/assets/images/help.png" height="16" width="16" />
				<?php else : ?>
					<span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
			<?php 
			endif;
			endif; ?>		
			<span class="wccaf-validation-message wccaf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
		</p>
		
		<?php 
	
		} else if( $field["location"] == "product_cat_add_form_fields" ) { ?>
		
		<div class="form-field">
			<label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label>
			<input type="checkbox" name="<?php echo esc_attr( $field['name'] ); ?>" id="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['checkbox_value'] ); ?>" <?php echo $checked; ?> class="wccaf-field" wccaf-type="checkbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
			<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>
			<span class="wccaf-validation-message wccaf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
		</div>	
		
		<?php 
			
		} else if( $field["location"] == "product_cat_edit_form_fields" ) { ?>
			
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label></th>
			<td>
				<input type="checkbox" name="<?php echo esc_attr( $field['name'] ); ?>" id="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['checkbox_value'] ); ?>" <?php echo $checked; ?> class="wccaf-field" wccaf-type="checkbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
				<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>
				<span class="wccaf-validation-message wccaf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
			</td>
		</tr>
			
		<?php 
			
		}
	
	return ob_get_clean();
	
	}
	
	function validate( $val ) {
		return ( empty( $val ) ) ? false : true;
	}
	
}

new wcff_field_checkbox();

?>