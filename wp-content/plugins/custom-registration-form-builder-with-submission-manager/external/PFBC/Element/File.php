<?php
class Element_File extends Element {
	protected $_attributes = array("type" => "file");

    public function render() {
        $multiple= get_option('rm_option_allow_multiple_file_uploads');
        if($multiple=="yes"){
            $this->_attributes['multiple']= "multiple";
            $this->_attributes['name']= $this->_attributes['name'].'[]';
        }
        
        if($this->isRequired())
            $this->validation[] = new Validation_File("", true);
        else
            $this->validation[] = new Validation_File;
        
        parent::render();
    }
}
