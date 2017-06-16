<?php 
/**
 * @author 		: Saravana Kumar K
 * @author url  : iamsark.com
 * @copyright	: sarkware.com
 * Class which responsible for creating and maintaining date field ( for both Product, Admin, as well as date picker fields's meta section )
 */


if ( ! defined( 'ABSPATH' ) ) { exit; }

class wcff_field_datepicker extends wcff_field {
	
	function __construct() {
		$this->name 		= 'datepicker';
		$this->label 		= "Date Picker";
		$this->required 	= false;
		$this->valid		= true;
		$this->message 		= "This field can't be Empty";
		$this->params 		= array(				
				'placeholder'	=>	'',
				'date_format'	=>	''
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
				<label for="post_type"><?php _e( 'Place Holder', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Place holder text for this Text Box', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="text" data-param="placeholder">
					<input type="text" id="wcff-field-type-meta-placeholder" value="" />
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Read Only', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Make text field read only, so it won\'t pulls up mobile key board ( on mobile browsers )', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="readonly">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-readonly" value="yes" checked/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-readonly" value="no" /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
					</ul>
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Show Time Picker', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Show time picker along with date picker', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="timepicker">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-timepicker" value="yes" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-timepicker" value="no" checked /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
					</ul>
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'localize Datepicker', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Choose the language in which the datepicker should be displayed', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="select" data-param="language">
					<select id="wcff-field-type-meta-language">
						<option value="none">Choose Language</option>
						<option value="af">Afrikaans</option>
						<option value="ar">Arabic</option>
						<option value="ar-DZ">Algerian Arabic</option>
						<option value="az">Azerbaijani</option>
						<option value="be">Belarusian</option>
						<option value="bg">Bulgarian</option>
						<option value="bs">Bosnian</option>
						<option value="ca">Catalan</option>
						<option value="cs">Czech</option>
						<option value="cy-GB">Welsh/UK</option>
						<option value="da">Danish</option>
						<option value="de">German</option>
						<option value="el">Greek</option>
						<option value="en-AU">English/Australia</option>
						<option value="en-GB">English/UK</option>
						<option value="default" selected>English/US</option>
						<option value="en-NZ">English/New Zealand</option>
						<option value="eo">Esperanto</option>
						<option value="es">Español</option>
						<option value="et">Estonian</option>
						<option value="eu">Spanish</option>
						<option value="fa">Persian</option>
						<option value="fi">Finnish</option>
						<option value="fo">Faroese</option>
						<option value="fr-CA">Canadian-French</option>
						<option value="fr-CH">Swiss-French</option>
						<option value="fr">French</option>
						<option value="gl">Galician</option>
						<option value="he">Hebrew</option>
						<option value="hi">Hindi</option>
						<option value="hr">Croatian</option>
						<option value="hu">Hungarian</option>
						<option value="hy">Armenian</option>
						<option value="id">Indonesian</option>
						<option value="is">Icelandic</option>
						<option value="it-CH">Italian - CH</option>
						<option value="it">Italian</option>
						<option value="ja">Japanese</option>
						<option value="ka">Georgian</option>
						<option value="kk">Kazakh</option>
						<option value="km">Khmer</option>
						<option value="ko">Korean</option>
						<option value="ky">Kyrgyz</option>
						<option value="lb">Luxembourgish</option>
						<option value="lt">Lithuanian</option>
						<option value="lv">Latvian</option>
						<option value="mk">Macedonian</option>
						<option value="ml">Malayalam</option>
						<option value="ms">Malaysian</option>
						<option value="nb">Norwegian - Bokmål</option>
						<option value="nl-BE">Dutch - Belgium</option>
						<option value="nl">Dutch</option>
						<option value="nn">Norwegian Nynorsk</option>
						<option value="no">Norwegian</option>
						<option value="pl">Polish</option>
						<option value="pt-BR">Brazilian</option>
						<option value="pt">Portuguese</option>
						<option value="rm">Romansh</option>
						<option value="ro">Romanian</option>
						<option value="ru">Russian</option>
						<option value="sk">Slovak</option>
						<option value="sl">Slovenian</option>
						<option value="sq">Albanian</option>
						<option value="sr-SR">Serbian - SR</option>
						<option value="sr">Serbian</option>
						<option value="sv">Swedish</option>
						<option value="ta">Tamil</option>
						<option value="th">Thai</option>
						<option value="tj">Tajiki</option>
						<option value="tr">Turkish</option>
						<option value="uk">Ukrainian</option>
						<option value="vi">Vietnamese</option>
						<option value="zh-CN">Chinese - CN</option>
						<option value="zh-HK">Chinese - HK</option>
						<option value="zh-TW">Chinese - TW</option>
					</select>					
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Month & Year Dropdown', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Display month & year in dropdown instead of static month/year header navigation', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="display_in_dropdown">
					<ul class="wcff-field-layout-vertical">
						<li><label><input type="radio" name="wcff-field-type-meta-display_in_dropdown" value="yes" /> <?php _e( 'Show Dropdown', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-display_in_dropdown" value="no" checked /> <?php _e( 'Show Default', 'wc-fields-factory' ); ?></label></li>							
					</ul>	
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Year Range', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Before and after year range. By default Year Dropdown displays only 10 years, you modify it using this option.<br/>You may use either relative ( -100:+100 ) or absolute ( 1985:2065 )', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="text" data-param="dropdown_year_range">					
					<input type="text" name="wcff-field-type-meta-dropdown_year_range" id="wcff-field-type-meta-dropdown_year_range" placeholder="-10:+10"/>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Date Format', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'The Date Format that will be used display & save the value', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="text" data-param="date_format">
					<input type="text" id="wcff-field-type-meta-date_format" value="" placeholder="dd-mm-yy"/>
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Disable Dates', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Prevent user from selecting past, present or future dates', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-field-types-meta" data-type="radio" data-param="disable_date">
					<ul class="wcff-field-layout-horizontal">
						<li><label><input type="radio" name="wcff-field-type-meta-disable_date" value="none" checked/> <?php _e( 'Show All Date', 'wc-fields-factory' ); ?></label></li>
						<li><label><input type="radio" name="wcff-field-type-meta-disable_date" value="past" /> <?php _e( 'Disable Past Dates', 'wc-fields-factory' ); ?></label></li>							
						<li><label><input type="radio" name="wcff-field-type-meta-disable_date" value="future" /> <?php _e( 'Disable Future Dates', 'wc-fields-factory' ); ?></label></li>							
					</ul>						
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="summary">
				<label for="post_type"><?php _e( 'Disable', 'wc-fields-factory' ); ?></label>
				<p class="description"><?php _e( 'Prevent user from selecting particular days', 'wc-fields-factory' ); ?></p>
			</td>
			<td>
				<div class="wcff-date-field-disable-accordian-container">
    				<div class="wcff-date-field-disable-accordian-left-panel">
    					<ul>
	    					<li class="active" data-box="#wcff-date-field-disable-days"><?php _e( 'Days', 'wc-fields-factory' ); ?></li>
	    					<li data-box="#wcff-date-field-disable-specific-dates"><?php _e( 'Specific Dates', 'wc-fields-factory' ); ?></li>
	    					<li data-box="#wcff-date-field-disable-weekends-or-weekdays"><?php _e( 'Weekends Or Weekdays', 'wc-fields-factory' ); ?></li>  
	    					<li data-box="#wcff-date-field-disable-specific-date-all-months"><?php _e( 'Specific Dates All Months', 'wc-fields-factory' ); ?></li>  
	    					<li data-box="#wcff-date-field-enable-specific-year-to-year"><?php _e( 'Show Only Years', 'wc-fields-factory' ); ?></li>  					
    					</ul>
    				</div>
    				<div class="wcff-date-field-disable-accordian-right-panel">
    					<div id="wcff-date-field-disable-days" class="wcff-date-field-disable-hide-elem">
							<div class="wcff-field-types-meta" data-type="check" data-param="disable_days">
								<ul class="wcff-field-layout-horizontal">
									<li><label><input type="checkbox" name="wcff-field-type-meta-disable_days" value="sunday"> <?php _e( 'Sunday', 'wc-fields-factory' ); ?></label></li>
									<li><label><input type="checkbox" name="wcff-field-type-meta-disable_days" value="monday"> <?php _e( 'Monday', 'wc-fields-factory' ); ?></label></li>
									<li><label><input type="checkbox" name="wcff-field-type-meta-disable_days" value="tuesday"> <?php _e( 'Tuesday', 'wc-fields-factory' ); ?></label></li>
									<li><label><input type="checkbox" name="wcff-field-type-meta-disable_days" value="wednesday"> <?php _e( 'Wednesday', 'wc-fields-factory' ); ?></label></li>
									<li><label><input type="checkbox" name="wcff-field-type-meta-disable_days" value="thursday"> <?php _e( 'Thursday', 'wc-fields-factory' ); ?></label></li>
									<li><label><input type="checkbox" name="wcff-field-type-meta-disable_days" value="friday"> <?php _e( 'Friday', 'wc-fields-factory' ); ?></label></li>
									<li><label><input type="checkbox" name="wcff-field-type-meta-disable_days" value="saturday"> <?php _e( 'Saturday', 'wc-fields-factory' ); ?></label></li>
								</ul>						
							</div>
						</div>
						<div id="wcff-date-field-disable-specific-dates" class="wcff-date-field-disable-hide-elem">
							<div class="wcff-field-types-meta" data-type="textarea" data-param="specific_dates">
								<textarea rows="3" id="wcff-field-type-meta-specific_dates" placeholder=""></textarea>									
							</div>
							<p class="description"><?php _e( 'Format: MM-DD-YYYY Example: 1-22-2017,10-7-2017', 'wc-fields-factory' ); ?></p>
						</div>
						<div id="wcff-date-field-disable-weekends-or-weekdays" class="wcff-date-field-disable-hide-elem">
							<div class="wcff-field-types-meta" data-type="radio" data-param="weekend_weekdays">
								<ul class="wcff-field-layout-horizontal">
									<li><label><input type="radio" name="wcff-field-type-meta-weekend_weekdays" value="weekends">  <?php _e( 'Weekends', 'wc-fields-factory' ); ?></label></li>
									<li><label><input type="radio" name="wcff-field-type-meta-weekend_weekdays" value="weekdays"> <?php _e( 'Weekdays', 'wc-fields-factory' ); ?></label></li>
									<a href="#" class="wcff-date-disable-radio-clear button"><?php _e( 'Clear', 'wc-fields-factory' ); ?></a>
								</ul>						
							</div>
						</div>
						<div id="wcff-date-field-disable-specific-date-all-months" class="wcff-date-field-disable-hide-elem">
							<div class="wcff-field-types-meta" data-type="textarea" data-param="specific_date_all_months">
								<textarea rows="3" id="wcff-field-type-meta-specific_date_all_months" placeholder=""></textarea>									
							</div>
							<p class="description"><?php _e( 'Example: 5,10,12', 'wc-fields-factory' ); ?></p>
						</div>
    				</div>
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
		$name_index = $fields_cloning == "yes" ? "_1" : "";
		$field_class = isset( $field[ "field_class" ] ) ? " ".$field[ "field_class" ] : "";
		$readonly = isset( $field["show_as_read_only"] ) ? $field["show_as_read_only"] : "no";
		$readonly = ( $readonly == "yes" ) ? "disabled" : "";
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
				<p><?php echo $field["default_value"]; ?></p>
			<?php else : ?>
				<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-datepicker wccpf-datepicker-<?php echo esc_attr( $field["name"] ); ?><?php echo $field_class; ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" value="" <?php echo ( $field["readonly"] == "yes" ) ? "readonly" : ""; ?> wccpf-type="text" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> />
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
						<p><?php echo  $field["default_value"]; ?></p>
					<?php else : ?>
						<input type="text" name="<?php echo esc_attr( $field["name"] . $name_index ); ?>" class="wccpf-field wccpf-datepicker wccpf-datepicker-<?php echo esc_attr( $field["name"] ); ?><?php echo $field_class; ?>" placeholder="<?php echo esc_attr( $field["placeholder"] ); ?>" value="" <?php echo ( $field["readonly"] == "yes" ) ? "readonly" : ""; ?> wccpf-type="text" wccpf-pattern="mandatory" wccpf-mandatory="<?php echo $field["required"]; ?>" <?php echo $readonly; ?> />
						<span class="wccpf-validation-message wccpf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
					<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>	
		
		<?php endif; 
		
		$this->initialize_datepicker_field( $field, "wccpf" );
		
		return ob_get_clean();
	}

	function render_admin_field( $field ) { ob_start(); 
	
		if( $field["location"] != "product_cat_add_form_fields" && $field["location"] != "product_cat_edit_form_fields" ) {
	
		?>
		
		<p class="form-field <?php echo esc_attr( $field['name'] ); ?>_field ">
			<label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label>
			<input type="text" class="wccaf-field wccaf-datepicker wccaf-datepicker-<?php echo esc_attr( $field["name"] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" id="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" wccaf-type="textbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />			
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
			<input type="text" class="wccaf-field wccaf-datepicker wccaf-datepicker-<?php echo esc_attr( $field["name"] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" id="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" wccaf-type="textbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
			<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>
			<span class="wccaf-validation-message wccaf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
		</div>			
			
		<?php 
		
		} else if( $field["location"] == "product_cat_edit_form_fields" ) { ?>
	
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?><?php echo ( isset( $field["required"] ) && $field["required"] == "yes" ) ? ' <span>*</span>' : ''; ?></label></th>
			<td>
				<input type="text" class="wccaf-field wccaf-datepicker wccaf-datepicker-<?php echo esc_attr( $field["name"] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" id="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" wccaf-type="textbox" wccaf-pattern="mandatory" wccaf-mandatory="<?php echo $field["required"]; ?>" />
				<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>
				<span class="wccaf-validation-message wccaf-is-valid-<?php echo $this->valid; ?>"><?php echo $field["message"]; ?></span>
			</td>
		</tr>
			
		<?php 
		
		}
	
		$this->initialize_datepicker_field( $field, "wccaf" );
		return ob_get_clean();
	}
	
	function initialize_datepicker_field( $field, $post_type ) { 
		
		$localize = "none";
		if( isset( $field["language"] ) && !empty( $field["language"] ) ) {
			if( $field["language"] != "default" ) {
				$localize = $field["language"];
			}			
		}	
		
		$year_range = "-10:+10";
		if( isset( $field["dropdown_year_range"] ) && !empty( $field["dropdown_year_range"] ) ) {			
			$year_range = $field["dropdown_year_range"];			
		}
		
		?>
		<script type="text/javascript">		
		(function($) {
			$( document ).ready(function() {
				<?php
				if( $localize != "none" ) { ?>					
					var options = $.extend(
					    {},                         
					    $.datepicker.regional["<?php echo $localize; ?>"],
					    { dateFormat: "d MM, y" } 
					);
					$.datepicker.setDefaults(options);
				<?php 
				} else { ?>					
					var options = $.extend(
					{},
					$.datepicker.regional["en-GB"],
					{ dateFormat: "d MM, y" }
					);
					$.datepicker.setDefaults(options);
				<?php 
				}				
				?>
				<?php $dformat = isset( $field["date_format"] ) ? 'dateFormat:'.esc_attr( $field["date_format"] ) : ''; ?>
				$( "body" ).on("focus", ".<?php echo $post_type; ?>-datepicker-<?php echo esc_attr( $field["name"] ); ?>", function(){
				<?php if( isset( $field["timepicker"] ) && $field["timepicker"] == "yes" ) : ?>
					$(this).datetimepicker( {
					<?php else : ?>
					$(this).datepicker( {
					<?php endif; ?>											
						<?php if( $field["date_format"] != "" ) {
							echo "dateFormat:'".esc_attr( $field["date_format"] )."'";
						} else {
							echo "dateFormat:'dd-mm-yy'";
						}	
						
						if( isset( $field["display_in_dropdown"] ) && !empty( $field["display_in_dropdown"] ) ) {
							if( $field["display_in_dropdown"] == "yes" ) {
								echo ",changeMonth: true";
								echo ",changeYear: true";
								echo ",yearRange:'". $year_range ."'";
							}
						}
						if( isset( $field["disable_date"] ) && !empty( $field["disable_date"] ) ) {
							if( "future" == $field["disable_date"] ) {
								echo ",maxDate: 0";
							}
							if( "past" == $field["disable_date"] ) {
								echo ",minDate: new Date()";
							}											
						}
						echo ",beforeShowDay: disableDates";					
						?>					
						,onSelect: function( dateText ) {
							
						    $( this ).next().hide();
						}								 
					});
				});	
					var daycound = [];
				function disableDates( date ){	
					<?php if( is_array( $field["disable_days"] ) && count( $field["disable_days"] ) > 0 ) { ?>
							 var disableDays = <?php echo json_encode( $field["disable_days"] ); ?>;
							 var day 	= date.getDay();
							 for ( var i = 0; i < disableDays.length; i++ ) {
									 var test = disableDays[i]
								 		 test = test == "sunday" ? 0 : test == "monday" ? 1 : test == "tuesday" ? 2 : test == "wednesday" ? 3 : test == "thursday" ? 4 : test == "friday" ? 5 : test == "saturday" ? 6 : "";
							        if ( day == test ) {									        
							            return [false];
							        }
							 }						
					<?php } ?>	
					<?php if( isset( $field["specific_date_all_months"] ) && !empty( $field["specific_date_all_months"] ) ){ ?>
					 		var disableDateAll = <?php echo '"'.$field["specific_date_all_months"].'"'; ?>;
					 			disableDateAll = disableDateAll.split(",");
					 		for( var i = 0; i < disableDateAll.length; i++ ){
								if( parseInt( disableDateAll[ i ] ) == date.getDate() ){
									return [false];
								}					
					 		}
					<?php } ?>						
					<?php if( isset( $field["specific_dates"] ) && !empty( $field["specific_dates"] ) ){ ?>
								var disableDates = <?php echo "'".$field["specific_dates"]."'"; ?>;
									disableDates = disableDates.split(",");
								var m = date.getMonth();
								var d = date.getDate();
								var y = date.getFullYear();
								var currentdate = ( m + 1 ) + '-' + d + '-' + y ;
								for (var i = 0; i < disableDates.length; i++) {									
									if ( $.inArray( currentdate, disableDates ) != -1 ) {
										return [false];
									}
								}
					<?php } ?>					
					<?php if( isset( $field["weekend_weekdays"] ) && !empty( $field["display_in_dropdown"] ) ) { ?>
							<?php if( $field["weekend_weekdays"] == "weekdays" ){ ?>
								//weekdays disable callback
								var weekenddate = $.datepicker.noWeekends(date);
								var disableweek = [!weekenddate[0]]; 
								return disableweek;
							<?php } else if( $field["weekend_weekdays"] == "weekends" ){ ?>
								//weekend disable callback
								var weekenddate = $.datepicker.noWeekends(date);
								return weekenddate; 
							<?php } ?>							
					<?php }  ?>					
					return [true];
				}
							
			});
		})(jQuery);
		</script>
	<?php 
	}

	function validate( $val ) {
		return ( isset( $val ) && !empty( $val ) ) ? true : false;
	}
}

new wcff_field_datepicker();

?>