<?php
/**
 * @author 		: Saravana Kumar K
 * @author url  : iamsark.com
 * @copyright	: sarkware.com
 * Class which responsible for creating and maintaining color picker field ( for both Product, Admin, as well as color picker fields's meta section )
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wcff_field_colorpicker extends wcff_field {
	
	function __construct() {
		$this->name 		= 'colorpicker';
		$this->label 		= "Color Picker";
		$this->required 	= false;
		$this->valid		= true;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(				
				'placeholder'	=>	''
		);
	
		parent::__construct();
	}
	
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
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Color Format', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'How you want the color value', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="color_format">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-color_format" value="hex" checked /> <?php _e( 'HEX', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-color_format" value="hex3" /> <?php _e( 'HEX3', 'wc-fields-factory' ); ?></label></li>							
						<li><label><input type="radio" name="wcff-field-type-meta-color_format" value="hsl"  /> <?php _e( 'HSL', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-color_format" value="rgb" /> <?php _e( 'RGB', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-color_format" value="name" /> <?php _e( 'Name', 'wc-fields-factory' ); ?></label></li>
					</ul>						
				</div>
			</td>
		</tr>		
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Default Color', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'If customer doesn\'t choose any color then this color would be used instead.', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="text" data-param="default_value">
					<input type="color" id="wcff-field-type-meta-default_value" value="" />											
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Show Palette Only', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Want show only the palette.? or along with the color picker.?', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="show_palette_only">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-show_palette_only" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-show_palette_only" value="no" checked/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
					</ul>						
				</div>
			</td>
		</tr>	
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Palettes', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Instead of showing only the color picker, you can show them personalized palettes, where customer chooce one of the color provided by you.', 'wc-fields-factory' ); ?><br/><br/>#fff, #ccc, #555<br/>#f00, #0f0, #00f</p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="textarea" data-param="palettes">
					<textarea rows="6" id="wcff-field-type-meta-palettes"></textarea>						
				</div>
			</td>
		</tr>	
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Show Hex value in', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'hex value in color or color code', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="hex_color_show_in">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-hex_color_show_in" value="yes" /> <?php _e( 'Show in Color', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-hex_color_show_in" value="no" checked/> <?php _e( 'Show in Color Code', 'wc-fields-factory' ); ?></label></li>
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
		
		<?php if( $type == "wccaf" ) : ?>	
		
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
		
		$wccpf_options = wcff()->option->get_options();
		$show_value_front = isset( $field["showin_value"] ) ? $field["showin_value"] : "no";
		$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";		
		$field_class = isset( $field[ "field_class" ] ) ? " ".$field[ "field_class" ] : "";
		$name_index = $fields_cloning == "yes" ? "_1" : "";			
		$readonly = isset( $field["show_as_read_only"] ) ? $field["show_as_read_only"] : "no";
		$readonly = ( $readonly == "yes" ) ? "disabled" : "";
		
		$defaultcolor = isset( $field["default_value"] ) ? $field["default_value"] : "#000";
		if( isset( $field["login_user_field"] ) ){
			if( $field["login_user_field"] == "yes" ){
				if( !is_user_logged_in() ){
					return;
				}
			}
		}
		ob_start(); ?>
		
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>
		
			<?php do_action( 'wccpf/before/field/rendering', $field ); ?>
			<?php if( $show_value_front == "yes" ) : ?>	
				<?php echo $field["hex_color_show_in"] == "yes" ?  '<span class="wcff-color-picker-color-show" color-code="'.$defaultcolor.'" style="padding: 0px 15px;background-color: '.$defaultcolor.'"; ></span>' : $defaultcolor; ?>				
			<?php else : ?>	
				<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-color <?php echo $field_class; ?> wccpf-color-<?php echo esc_attr( $field["name"] ); ?>" value="<?php echo $field["hex_color_show_in"] == "yes" ?  "&lt;span class&equals;&quot;wcff-color-picker-color-show&quot; color-code&equals;&quot;$defaultcolor&quot; style&equals;&quot;padding: 0px 15px;background-color: $defaultcolor;&quot; &gt; &lt;/span&gt" : $defaultcolor; ?>" wccpf-type="text" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> />
				<span class="wccpf-validation-message wccpf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
			<?php endif; ?>	
			<?php do_action( 'wccpf/after/field/rendering', $field ); ?>
		
		<?php else : ?>
		
		<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?><?php echo $field_class."-wrapper"; ?>" cellspacing="0">
			<tbody>
				<tr>
					<td class="wccpf_label"><label class="<?php echo $field_class."-label"; ?>" for="<?php echo esc_attr( $field["name"] . $name_index ); ?>"><?php echo esc_html( $field["label"] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label></td>
					<td class="wccpf_value">
					<?php if( $show_value_front == "yes" ) : ?>	
						<?php echo $field["hex_color_show_in"] == "yes" ?  '<span class="wcff-color-picker-color-show" color-code="'.$defaultcolor.'" style="padding: 0px 15px;background-color: '.$defaultcolor.'"; ></span>' : $defaultcolor; ?>			
					<?php else : ?>	
						<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-color wccpf-color-<?php echo esc_attr( $field["name"] ); ?><?php echo $field_class; ?>" value="<?php echo $field["hex_color_show_in"] == "yes" ?  "&lt;span class&equals;&quot;wcff-color-picker-color-show&quot; color-codes&equals;&quot;$defaultcolor&quot; style&equals;&quot;padding: 0px 15px;background-color: $defaultcolor;&quot; &gt; &lt;/span&gt" : $defaultcolor; ?>" wccpf-type="text" wccpf-pattern="mandatory" hex_color_show_in_color="<?php echo $field["hex_color_show_in"]; ?>" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> />
						<span class="wccpf-validation-message wccpf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
					<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>	
		
		<?php 
		endif;		
		return ob_get_clean();
	}

	function render_admin_field( $field ) { ob_start(); 
		$wccpf_options = wcff()->option->get_options();
		$fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$name_index = $fields_cloning == "yes" ? "_1" : "";
		if( $field["location"] != "product_cat_add_form_fields" && $field["location"] != "product_cat_edit_form_fields" ) {
		
		?>
	
		<p class="form-field <?php echo esc_attr( $field['name'] ); ?>_field ">
			<label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label>
			<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccaf-field wccaf-color wccaf-color-<?php echo esc_attr( $field["name"] ); ?>" value="<?php echo $field["value"]; ?>" wccaf-type="textbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />			
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
			<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccaf-field wccaf-color wccaf-color-<?php echo esc_attr( $field["name"] ); ?>" value="<?php echo $field["value"]; ?>" wccaf-type="textbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
			<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>
			<span class="wccaf-validation-message wccaf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
		</div>		
			
		<?php 
			
		} else if( $field["location"] == "product_cat_edit_form_fields" ) { ?>
			
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label></th>
			<td>
				<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccaf-field wccaf-color wccaf-color-<?php echo esc_attr( $field["name"] ); ?>" value="<?php echo $field["value"]; ?>" wccaf-type="textbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
				<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>
				<span class="wccaf-validation-message wccaf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
			</td>
		</tr>
			
		<?php 
		
		}
		
		?>
		
		<script type="text/javascript">
			var $ = jQuery;
			$( document ).ready(function() {		

				<?php 
				$palettes = null;
				$colorformat = isset( $field["color_format"] ) ? $field["color_format"] : "hex";
				if( isset( $field["palettes"] ) && $field["palettes"] != "" ) {
					$palettes = explode( ";", $field["palettes"] );
				} ?>
												
				$( ".wccaf-color-<?php echo esc_attr( $field["name"] ); ?>").spectrum({
					 preferredFormat: "<?php echo $colorformat; ?>",					
					<?php 
					$comma = "";
					$indexX = 0;
					$indexY = 0;
					if( is_array( $palettes ) && count( $palettes ) > 0 ) {
						if( $field["show_palette_only"] == "yes" ) {
							echo "showPaletteOnly: true,";
						}
						echo "showPalette: true,";
						echo "palette : [";						
						foreach ( $palettes as $palette ) {		
							$indexX = 0;								
							$comma = ( $indexY == 0 ) ? "" : ",";
							echo $comma."[";
							$colors = explode( ",", $palette );
						 	foreach ( $colors as $color ) {							 		
						 		$comma = ( $indexX == 0 ) ? "" : ","; 
						 		echo $comma ."'". $color ."'";	
						 		$indexX++;
							}
							echo "]";
							$indexY++;
						} 
						echo "]";						
					}
					?>
				});				
				
			});
		</script>
	
	<?php return ob_get_clean();
	}
	
	function validate( $val ) {
		return ( isset( $val ) && !empty( $val ) ) ? true : false;
	}
}

new wcff_field_colorpicker();

?>