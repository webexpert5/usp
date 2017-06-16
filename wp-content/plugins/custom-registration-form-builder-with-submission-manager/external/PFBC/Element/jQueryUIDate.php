<?php
class Element_jQueryUIDate extends Element_Textbox {
	protected $_attributes = array(
		"type" => "text",
		"autocomplete" => "off",
                "readonly" => true
                /*"pattern" => "^((0?[13578]|10|12)(-|\/)(([1-9])|(0[1-9])|([12])([0-9]?)|(3[01]?))(-|\/)((19)([2-9])(\d{1})|(20)([01])(\d{1})|([8901])(\d{1}))|(0?[2469]|11)(-|\/)(([1-9])|(0[1-9])|([12])([0-9]?)|(3[0]?))(-|\/)((19)([2-9])(\d{1})|(20)([01])(\d{1})|([8901])(\d{1})))$"*/
                
	);
	protected $jQueryOptions="";
        
        public function __construct($label, $name, array $properties = null)
        {
            parent::__construct($label, $name, $properties);
            
            if(!isset($properties['date_format']) || !$properties['date_format'])
                $this->_attributes['data-dateformat'] = 'mm/dd/yy';
            else
                $this->_attributes['data-dateformat'] = $properties['date_format'];
        }

        public function getCSSFiles() {
		return array(
			$this->_form->getPrefix() . "://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css"
		);
	}

	public function getJSFiles() {
		return array(
			//$this->_form->getPrefix() . "://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"
		);
	}

    public function jQueryDocumentReady() {
        parent::jQueryDocumentReady();
        echo 'jQuery("#', $this->_attributes["id"], '").datepicker({dateFormat:"'.$this->_attributes["data-dateformat"].'",changeMonth:true,changeYear:true,yearRange: \'1900:+50\'});';
    }

    public function render() {
        $this->validation[] = new Validation_Date;
        parent::render();
    }
}
