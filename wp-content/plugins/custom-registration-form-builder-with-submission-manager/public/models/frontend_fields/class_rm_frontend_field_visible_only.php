<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Field_Visible_Only extends RM_Frontend_Field_Base
{
    protected $field_value;
    protected $field_class;
    
    public function __construct($id, $type, $label, $options, $value, $page_no = 1, $is_primary = false)
    {
        parent::__construct($id, $type, $label, $options, $page_no, $is_primary);
        
        $this->field_value = $value;
        $this->field_class = isset($options['class'])?$options['class']:null;
    }
    
    public function get_pfbc_field()
    {
        if ($this->pfbc_field)
            return $this->pfbc_field;
        else
        {
            $class_name = "Element_" . $this->field_type;
            $this->pfbc_field = new $class_name($this->field_value,$this->field_class, $this->field_options);
            return $this->pfbc_field;
        }  
    }
}
