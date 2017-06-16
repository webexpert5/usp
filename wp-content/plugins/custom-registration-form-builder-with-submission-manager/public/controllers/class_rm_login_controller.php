<?php

class RM_Login_Controller{

    public $mv_handler;

    function __construct(){
        $this->mv_handler= new RM_Model_View_Handler();
    }

    public function form($model,$service,$request,$params){

        if(isset($request->req['rm_target']))
        {
            if($request->req['rm_target'] == 'fbcb')
            {
                $service->facebook_login_callback();
            }
        }

        if ($this->mv_handler->validateForm("rm_login_form"))
        {
            $user= $service->login($request);
            
            if (is_wp_error($user)) {
                RM_PFBC_Form::setError('rm_login_form',$user->get_error_message());
            }else{
                $redirect_to= RM_Utilities::after_login_redirect($user);
                RM_Utilities::redirect($redirect_to);
                die;
            }
        }

        $data= new stdClass();
        //$service->facebook_login_callback();
        $data->facebook_html= $service->facebook_login_html();
        $view= $this->mv_handler->setView('login',true);
        return $view->read($data);
    }
}