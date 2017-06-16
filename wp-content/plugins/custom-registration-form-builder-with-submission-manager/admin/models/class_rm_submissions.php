<?php

/**
 * Model class for submissions
 * 
 * @author cmshelplive
 */
class RM_Submissions extends RM_Base_Model
{

    private $submission_id;
    private $form_id;
    private $data;
    private $submitted_on;
    private $user_email;
    private $child_id;
    private $last_child;
    //private $initialized;
    private $unique_token;
    //errors submission validation
    private $errors;
   

    public function __construct()
    {
        $this->initialized = false;
        $this->submission_id = NULL;
        $this->child_id = 0;
        $this->is_read= 0;
    }
    
     /*     * *Getters** */
    
    public static function get_identifier()
    {
        return 'SUBMISSIONS';
    }
    
    public function get_submission_id()
    {
        return $this->submission_id;
    }
    
    function get_child_id() {
        return $this->child_id;
    }
    function set_child_id($child_id) {
        $this->child_id = $child_id;
    }
    
    function get_last_child() {
        return $this->last_child;
    }

    function set_last_child($last_child) {
        $this->last_child = $last_child;
    }

        
  public function get_submission_ip()
    {
       $service = new RM_Services;
       $sub_id = $service->get_oldest_submission_from_group($this->submission_id);
       $where=array('submission_id'=>$sub_id);
       $sub_ip=  RM_DBManager::get('STATS', $where,array('%d'), 'col', 0,1,'user_ip');
       return isset($sub_ip['0'])?$sub_ip['0']:null;
    }
    
    public function get_submission_browser()
    {
       $service = new RM_Services;
       $sub_id = $service->get_oldest_submission_from_group($this->submission_id);
       $where=array('submission_id'=>$sub_id);
       $sub_bw=  RM_DBManager::get('STATS', $where,array('%d'), 'col', 0,1,'browser_name');
       return isset($sub_bw['0'])?$sub_bw['0']:null;
    }
    public function get_form_id()
    {
        return $this->form_id;
    }

    public function get_data()
    {
        return RM_Utilities::strip_slash_array(maybe_unserialize($this->data));
    }

    public function get_submitted_on()
    {
        return $this->submitted_on;
    }

    public function get_user_email()
    {
        return trim($this->user_email);
    }
    
    public function get_unique_token()
    {
        return trim($this->unique_token);
    }
    

    /*     * *Setters** */

    public function set_submission_id($submission_id)
    {
        $this->submission_id = $submission_id;
    }

    public function set_unique_token($unique_token)
    {
        $this->unique_token = $unique_token;
    }
    
    public function set_form_id($form_id)
    {
        $this->form_id = $form_id;
    }

    public function set_data($data)
    {
        $this->data = maybe_serialize($data);
    }

    public function set_submitted_on($submitted_on)
    {
        $this->submitted_on = $submitted_on;
    }

    public function set_user_email($user_email)
    {
        $this->user_email = $user_email;
    }
    
//    public function set($request)
//    {
//
//        foreach ($request as $property => $value)
//        {
//            if (property_exists ($this ,$property ))
//            {
//                $this->$property = $value;
//            }
//        }
//    }

    /*     * *validations** */

    private function validate_form_id()
    {
        if (empty($this->form_id))
        {
            $this->errors['FORM_ID'] = 'Form id can not be empty';
        }
    }
    
    public function is_have_attcahment()
     {
        $data=$this->get_data();
        foreach($data as $sub_data)
        {
            if(isset($sub_data->type) && $sub_data->type== 'File' && $sub_data->value != null)
            {
                return true;
           }
        }
      return false;
     }
     
      public function get_payment_status()
    {
        $service=new RM_Services;
        //First get the parent submission as edited submissions do not have any payment assosiated.
        $parent_sub_id = $service->get_oldest_submission_from_group($this->get_submission_id());
        
        $payment = $service->get('PAYPAL_LOGS', array('submission_id' => $parent_sub_id), array('%d'), 'row', 0, 99999);
         if($payment == null)   
             return null;
         else
             return $payment->status;
    }
    
    private function validate_data()
    {
        if (empty($this->data))
        {
            $this->errors['DATA'] = 'No data submitted';
        }
        if (!is_array($this->data))
        {
            $this->errors['DATA'] = 'Invalid data format';
        } $this->errors['DATA'] = 'Invalid data format';
    }

    private function validate_user_email()
    {
        if (empty($this->user_email))
        {
            $this->errors['USER_EMAIL'] = 'User email must not be empty.';
        }
        if (!is_email($this->user_email))
        {
            $this->errors['USER_EMAIL'] = 'Invalid Email format.';
        }
    }
    
     public function is_valid()
    {
        $this->validate_form_id();
        $this->validate_data();
        $this->validate_user_email();
        
        return count($this->errors) === 0;
    }
    
    public function errors(){
        return $this->errors;
    }
    
     /*     * **Database Operations*** */

    public function insert_into_db()
    {

        if (!$this->initialized)   
        {
            return false;
        }

        if ($this->submission_id)
        {
            return false;
        }
        
        $this->unique_token = $this->form_id.time().rand(100,10000);
        $data = array(            
                    'form_id' => $this->form_id,
                    'data' => $this->data,
                    'user_email' => $this->user_email,
                    'submitted_on' => date('Y-m-d H:i:s'),
                    'unique_token'=> $this->unique_token,
                    );

        $data_specifiers = array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s'
        );

        $result = RM_DBManager::insert_row('SUBMISSIONS', $data, $data_specifiers);

        if (!$result)
        {
            return false;
        }

        $this->submission_id = $result;
        $this->last_child = $result;
        $this->update_into_db();

        return $result;
    }

    public function update_into_db()
    {
        if (!$this->initialized)
        {
            return false;
        }
        if (!$this->submission_id)
        {
            return false;
        }

        $data = array(            
                    'form_id' => $this->form_id,
                    'data' => $this->data,
                    'user_email' => $this->user_email,
                    'is_read'=> $this->is_read,
                    'child_id' => $this->child_id,
                    'last_child' => $this->last_child ? $this->last_child : $this->submission_id
                    );

        $data_specifiers = array(
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d'
        );

        $result = RM_DBManager::update_row('SUBMISSIONS', $this->submission_id, $data, $data_specifiers);

        if (!$result)
        {
            return false;
        }

        return true;
    }

    public function load_from_db($submission_id,$should_set_id=true)
    {

        $result = RM_DBManager::get_row('SUBMISSIONS', $submission_id);

        if (null !== $result)
        {       
                if($should_set_id)
                    $this->submission_id = $submission_id;
                $this->form_id = $result->form_id;
                $this->data = $result->data;
                $this->user_email = $result->user_email;
                $this->submitted_on = $result->submitted_on;
                $this->unique_token = $result->unique_token;
                $this->is_read= $result->is_read;
                $this->child_id = $result->child_id;
                $this->last_child = $result->last_child ? $result->last_child : $submission_id;
                $this->initialized= true;
        } else
        {
            return false;
        }
        
        $this->initialized = true;
        return true;
    }

    public function remove_from_db()
    {
        return RM_DBManager::remove_row('SUBMISSIONS', $this->submission_id);
    }


}
