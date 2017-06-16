<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_rm_frontend_field_ggeo
 *
 * @author RegistrationMagic
 */
class RM_Frontend_Field_GGeo extends RM_Frontend_Field_Base
{

    private $api_key;

    public function __construct($id, $type, $label, $options, $gmaps_api_key , $page_no, $is_primary = false, $extra_opts = null)
    {
        parent::__construct($id, $type, $label, $options, $page_no,$is_primary, $extra_opts);
        $this->api_key = $gmaps_api_key;
    }

    public function get_pfbc_field()
    {
        return null;
    }
}
