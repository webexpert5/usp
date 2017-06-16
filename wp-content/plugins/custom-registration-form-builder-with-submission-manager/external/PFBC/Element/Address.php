<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Map
 *
 * @author CMSHelplive
 */
class Element_Address extends Element
{

    private $default_add = array (
                            'rm_field_type' => '',
                            'original' => '',
                            'st_number' => '',
                            'st_route' => '',
                            'city' => '',
                            'state' => '',
                            'zip' => '',
                            'country' => ''
                          );
    protected $_attributes = array();
    protected $jQueryOptions = "";
    private $api_key;
    

    /* public function getCSSFiles()
      {
      return array(
      );
      } */

    public function __construct($label, $name, $api_key, array $properties = null)
    {
        parent::__construct($label, $name, $properties);
        $this->api_key = $api_key;
        $this->_attributes['id'] = 'autocomplete'.$name;
    }

    public function getJSFiles()
    {
        return array(
            'script_rm_address' => RM_BASE_URL . 'public/js/script_rm_address.js',
            'google_map_api' => $this->_form->getPrefix() . "://maps.googleapis.com/maps/api/js?key=" . $this->api_key . "&libraries=places&callback=rmInitGoogleApi",
        );
    }

    public function getJSDeps()
    {
        return array(
            'script_rm_address'
        );
    }

    public function jQueryDocumentReady()
    {
        parent::jQueryDocumentReady();
    }

    public function render()
    {
        $name = $this->_attributes['name'];
        $value = wp_parse_args($this->getAttribute('value'),  $this->default_add);
        if($this->isRequired())
            $required = 'required';
        else
            $required = '';
        
        if(isset($this->_attributes['style'])){
            $style = "style='".$this->_attributes["style"]."'";
           unset($this->_attributes["style"]);
        }
        else
            $style = '';
        ?>
        <div id="locationField">
            <input type="hidden" name="<?php echo $name; ?>[rm_field_type]" value="Address">
            <input id="<?php echo $name; ?>" class="rmgoogleautocompleteapi" placeholder="<?php echo RM_UI_Strings::get('PH_ENTER_ADDR'); ?>"
                   onFocus="(new rmAutocomplete('<?php echo $name; ?>')).geolocate()" onkeydown="rm_prevent_submission(event)"<?php echo $style; ?> type="text" <?php echo $required; ?> name="<?php echo $name; ?>[original]" value="<?php echo $value['original']; ?>"></input>
        </div>

        <div id="address">
            <div class="rm_ad_container">
                <div class="label"><?php echo RM_UI_Strings::get('LABEL_ST_ADDRESS'); ?></div>
                <div class="slimField"><input class="field" id="<?php echo $name; ?>_street_number"
                                               name="<?php echo $name; ?>[st_number]" value="<?php echo $value['st_number']; ?>"></input></div>
                <div class="wideField rm-semi-field" colspan="2"><input class="field" id="<?php echo $name; ?>_route"
                                                           name="<?php echo $name; ?>[st_route]" value="<?php echo $value['st_route']; ?>"></input></div>
            </div>
            <div class="rm_ad_container">
                <div class="label"><?php echo RM_UI_Strings::get('LABEL_ADDR_CITY'); ?></div>
                <div class="wideField rm-alone" colspan="3"><input class="field" id="<?php echo $name; ?>_locality"
                                                           name="<?php echo $name; ?>[city]" value="<?php echo $value['city']; ?>"></input></div>
            </div>
            <div class="rm_ad_container">
                <div class="label"><?php echo RM_UI_Strings::get('LABEL_ADDR_STATE'); ?></div>
                <div class="slimField"><input class="field"
                                              id="<?php echo $name; ?>_administrative_area_level_1"  name="<?php echo $name; ?>[state]" value="<?php echo $value['state']; ?>"></input></div>
                <div class="label label-short"><?php echo RM_UI_Strings::get('LABEL_ADDR_ZIP'); ?></div>
                <div class="wideField rm-semi-field-with-label"><input class="field" id="<?php echo $name; ?>_postal_code"
                                               name="<?php echo $name; ?>[zip]" value="<?php echo $value['zip']; ?>"></input></div>
            </div>
            <div class="rm_ad_container">
                <div class="label"><?php echo RM_UI_Strings::get('LABEL_ADDR_COUNTRY'); ?></div>
                <div class="wideField rm-alone" colspan="3"><input class="field"
                                                          id="<?php echo $name; ?>_country"   name="<?php echo $name; ?>[country]" value="<?php echo $value['country']; ?>"></input></div>
            </div>
        </div>

        <?php
    }

}
