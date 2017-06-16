<?php 
/**
 * @author 		: Saravana Kumar K
 * @author url  : iamsark.com
 * @copyright	: sarkware.com
 * Class which responsible for creating and maintaining url field ( for Admin )
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class wcff_field_url extends wcff_field {
	
	function __construct() {
		$this->name 		= 'url';
		$this->label 		= "Url";
		$this->required 	= false;
		$this->valid		= true;
		$this->message	 	= "";
		$this->params 		= array(
			'placeholder'	=>	'',
			'default_value'	=>	'',
			'maxlength'		=>	'',
			'rows' 			=> ''
		);
	
		parent::__construct();
	}
	
	function render_wcff_setup_fields( $type = "wccaf" ) { ob_start(); ?>	

			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Title', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Show tooltip info', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff-field-types-meta" data-type="text" data-param="tool_tip">
						<input type="text" id="wcff-field-type-meta-tool_tip" value="<?php echo esc_attr( "Click here" ); ?>" />						
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
					<label for="post_type"><?php _e( 'Link Name', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Name of link', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff-field-types-meta" data-type="text" data-param="link_name">
						<input type="text" id="wcff-field-type-meta-link_name" value="<?php echo esc_attr( "Click here" ); ?>" />						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'View In', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Show in button or link', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff-field-types-meta" data-type="radio" data-param="view_in">
						<ul class="wcff-field-layout-horizontal">
							<li><label><input type="radio" name="wcff-field-type-meta-view_in" value="button" /> <?php _e( 'Button', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wcff-field-type-meta-view_in" value="link" checked/> <?php _e( 'Link', 'wc-fields-factory' ); ?></label></li>
						</ul>						
					</div>
				</td>
			</tr>
			<!-- 
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Link', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Redirect url', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta" data-type="text" data-param="url">
							<input type="text" id="wcff-field-type-meta-url" placeholder="http://example.com" value="" />						
						</div>
					</td>
				</tr>
			 -->
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Open in', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Open new tab or same tab', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff-field-types-meta" data-type="text" data-param="tab_open">
						<ul class="wcff-field-layout-horizontal">
							<li><label><input type="radio" name="wcff-field-type-meta-tab_open" value="_blank" checked/> <?php _e( 'New', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wcff-field-type-meta-tab_open" value="_top" /> <?php _e( 'Same', 'wc-fields-factory' ); ?></label></li>
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
						<ul class="wcff-field-layout-horizontal">
							<li><label><input type="radio" name="wcff-field-type-meta-login_user_field" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wcff-field-type-meta-login_user_field" value="no" checked /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>							
						</ul>						
					</div>
				</td>
		</tr>
		
		
		<tr>
			<td class="summary">
					<label for="post_type"><?php _e( 'Show label', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Show label in product page', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff-field-types-meta" data-type="radio" data-param="show_label">
						<ul class="wcff-field-layout-horizontal">
							<li><label><input type="radio" name="wcff-field-type-meta-show_label" value="yes" checked/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wcff-field-type-meta-show_label" value="no" /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>							
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
		
			
		<?php return ob_get_clean();
	}
	
	function render_product_field( $field ) { 
		$wccpf_options = wcff()->option->get_options();		
		if( isset( $field["login_user_field"] ) ){
			if( $field["login_user_field"] == "yes" ){
				if( !is_user_logged_in() ){
					return;
				}
			}
		}		
		$field_class 	 = isset( $field[ "field_class" ] ) ? " ".$field[ "field_class" ] : "";
		$show_login_user = isset( $field["login_user_field"] ) ? $field["login_user_field"] : "no";
		$fields_cloning  = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$show_label 	 = isset( $field["show_label"] ) ? $field["show_label"] : "yes";
		$open_tab 		 = isset( $field["tab_open"] ) ? $field["tab_open"] : "_blank";
		$linkin			 = isset( $field["view_in"] ) ? $field["view_in"] : "link";
		$name_index 	 = $fields_cloning == "yes" ? "_1" : "";	
		ob_start(); ?>
		<?php if( has_action('wccpf/before/field/rendering' ) && has_action('wccpf/after/field/rendering' ) ) : ?>		
			<?php do_action( 'wccpf/before/field/rendering', $field ); ?>
					
			<?php do_action( 'wccpf/after/field/rendering', $field ); ?>
		<?php else : ?>
			<table class="wccpf_fields_table <?php echo apply_filters( 'wccpf/fields/container/class', '' ); ?><?php echo $field_class.'-wrapper'; ?>" cellspacing="0">
				<tbody>
					<tr>
						<?php if( $show_label == "yes" ): ?>
							<td class="wccpf_label"><label class="<?php echo $field_class.'-label'; ?>" for="<?php echo esc_attr( $field["name"] . $name_index ); ?>"><?php echo esc_html( $field["label"] ); ?></label></td>
						<?php endif; ?>
						<td class="wccpf_value">
							<?php if( $linkin == "link"): ?>
								<a href="<?php echo $field["default_value"]; ?>" target="<?php echo $open_tab; ?>" title="<?php echo $field["tool_tip"]; ?>"><?php echo $field["link_name"]; ?></a>
	
							<?php else: ?>
								<button onclick="window.open('<?php echo $field["default_value"]; ?>', '<?php echo $open_tab; ?>' )"  title="<?php echo $field["tool_tip"]; ?>" class="<?php echo $field_class; ?>"><?php echo $field["link_name"]; ?></button>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>		
		<?php 
		endif;	
		return ob_get_clean();
	}
	
	function render_admin_field( $field ) { ob_start(); 	?>
		
		<p class="form-field <?php echo esc_attr( $field['name'] ); ?>_field ">
				<label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?></label>
				<input type="text" name="<?php echo esc_attr( $field['name'] ); ?>" class="wccaf-field short" id="<?php echo esc_attr( $field['name'] ); ?>" placeholder="http://example.com" wccaf-type="url" value="<?php echo esc_attr( $field['value'] ); ?>" wccaf-pattern="mandatory" wccaf-mandatory="">

		</p>
	
	<?php 
		return ob_get_clean();
	}
}

new wcff_field_url();
?>



