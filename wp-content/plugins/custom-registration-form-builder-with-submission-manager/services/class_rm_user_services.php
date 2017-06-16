<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Class responsible for User and Roles related operations
 *
 * @author CMSHelplive
 */
class RM_User_Services extends RM_Services
{

    private $default_user_roles = array('administrator', 'editor', 'author', 'contributor', 'subscriber');

    public function get_user_roles()
    {
        $roles = get_editable_roles();
        //echo '<pre>';var_dump($roles);die;
        $role_names = array();
        foreach ($roles as $key => $role)
        {
            $role_names[$key] = $role['name'];
        }

        return $role_names;
    }

    // This function creates a copy of the role with a different name
    public function create_role($role_name, $display_name, $capability)
    {
        $role = get_role($capability);
        if (add_role($role_name, $display_name, $role->capabilities) !== null)
            return true;
        else
            return false;
    }

    public function get_roles_by_status()
    {
        $roles_data = new stdClass();
        $roles = $this->get_user_roles();
        $custom = array();
        $default = array();
        $linked_form = array();
        foreach ($roles as $key => $role)
        {
            if (in_array($key, $this->default_user_roles))
            {
                $default[$key] = $role;
                $linked_form[$key]=$this->get_linked_forms($key);
            } else
            {
                $custom[$key] = $role;
                $linked_form[$key]=$this->get_linked_forms($key);
            }
        }
        $roles_data->default = $default;
        $roles_data->custom = $custom;
        $roles_data->linked_forms=$linked_form;
        return $roles_data;
    }
    
    public function add_default_form($form=null,$role=null)
    {
        $role =isset($_POST['role'])? $_POST['role'] : null;
        $form =isset($_POST['form'])? $_POST['form'] : null;
       if(isset($role) && isset($form))
       {
           $gopts= new RM_Options;
           $default_forms=array();
           $opt_default_forms=$gopts->get_value_of('rm_option_default_forms');
           $default_forms= maybe_unserialize($opt_default_forms);
           if($form == '')
           {
                $default_forms[$role]=null;
                $opt_default_forms=  maybe_serialize($default_forms);
                $gopts->set_value_of('rm_option_default_forms',$opt_default_forms);
                echo "";die;  
           }  
           $default_forms[$role]=$form;
           $opt_default_forms=  maybe_serialize($default_forms);
           $gopts->set_value_of('rm_option_default_forms',$opt_default_forms);
           $forms_options=new RM_Forms;
           $forms_options->load_from_db($form);
           $form_name=$forms_options->get_form_name();
           echo $form_name;die;
          
       }
       echo "";die;
    }
    
        public function get_linked_forms($role)
    {
      $forms= RM_DBManager::get('FORMS', array("default_user_role" => $role), array("%s"));
      $linked_form=array();
      if($forms != null)
      {
          foreach($forms as $form)
          {
              $linked_form[$form->form_id] = $form->form_name;
          }
      }
      return $linked_form;
    }
    public function delete($users)
    {
        if (is_array($users) && !empty($users))
        {
            $curr_user = wp_get_current_user();
            if (isset($curr_user->ID))
                $curr_user_id = $curr_user->ID;
            else
                $curr_user_id = null;
            foreach ($users as $id)
            {
                if ($curr_user_id != $id)
                    wp_delete_user($id);
            }
        }
    }

    public function activate($users)
    {
        if (is_array($users) && !empty($users))
        {
            foreach ($users as $id)
            {
                update_user_meta($id, 'rm_user_status', '0');
            }
        }
    }

    public function notify_users($users, $type)
    {
        if (is_array($users) && !empty($users))
        {
            $front_form_service = new RM_Front_Form_Service;
            foreach ($users as $id)
            {
                $user = get_user_by('id', $id);
                $params = new stdClass;
                $params->email = $user->user_email;                
                $params->sub_id = get_user_meta($id, 'RM_UMETA_SUB_ID', true);
                $params->form_id = get_user_meta($id, 'RM_UMETA_FORM_ID', true);
                RM_Email_Service::notify_user_on_activation($params);
            }
        }
    }
    
    public static function send_email_ajax()
    {
        $to = $_POST['to'];
        $sub = $_POST['sub'];
        $body = $_POST['body'];
        
        RM_Utilities::quick_email($to, $sub, $body);
        
        wp_die();
    }

    public function deactivate_user_by_id($user_id)
    {
        $curr_user = wp_get_current_user();
        if (isset($curr_user->ID))
            $curr_user_id = $curr_user->ID;
        else
            $curr_user_id = null;
        if ($curr_user_id != $user_id)
            update_user_meta($user_id, 'rm_user_status', '1');
    }

    public function activate_user_by_id($user_id)
    {
        return update_user_meta($user_id, 'rm_user_status', '0');
    }

    public function deactivate($users)
    {
        if (is_array($users) && !empty($users))
        {
            $curr_user = wp_get_current_user();
            if (isset($curr_user->ID))
                $curr_user_id = $curr_user->ID;
            else
                $curr_user_id = null;
            foreach ($users as $id)
            {
                if ($curr_user_id != $id)
                    update_user_meta($id, 'rm_user_status', '1');
            }
        }
    }

    public function delete_roles($roles)
    {
        if (is_array($roles) && !empty($roles))
        {
            foreach ($roles as $name)
            {
                $users = $this->get_users_by_role($name);
                foreach ($users as $user)
                {
                    $user->add_role('subscriber');
                }

                remove_role($name);
            }
        }
    }

    public function get_users_by_role($role_name)
    {
        $args = array('role' => $role_name);
        $users = get_users($args);
        return $users;
    }

    public function get_user_count()
    {
        $result = count_users();
        $total_users = $result['total_users'];
        return $total_users;
    }

    public function get_users($offset = '', $number = '', $search_str = '', $user_status = 'all', $interval = 'all', $user_ids = array())
    {
        $args = array('number' => $number, 'offset' => $offset, 'include' => $user_ids, 'search' => '*' . $search_str . '*');
        //$args = array();

        switch ($user_status)
        {
            case 'active':
                $args['meta_query'] = array('relation' => 'OR',
                    array(
                        'key' => 'rm_user_status',
                        'value' => '1',
                        'compare' => '!='
                    ),
                    array(
                        'key' => 'rm_user_status',
                        'value' => '1',
                        'compare' => 'NOT EXISTS'
                ));
                break;

            case 'pending':
                $args['meta_query'] = array(array(
                        'key' => 'rm_user_status',
                        'value' => '1',
                        'compare' => '='
                ));
                break;
        }

        switch ($interval)
        {
            case 'today':
                $args['date_query'] = array(array('after' => date('Y-m-d', strtotime('today')), 'inclusive' => true));
                break;

            case 'week':
                $args['date_query'] = array(array('after' => date('Y-m-d', strtotime('this week')), 'inclusive' => true));
                break;

            case 'month':
                $args['date_query'] = array(array('after' => 'first day of this month', 'inclusive' => true));
                break;

            case 'year':
                $args['date_query'] = array(array('year' => date('Y'), 'inclusive' => true));
                break;
        }
        //echo "Args:<pre>", var_dump($args), "</pre>";
        $users = get_users($args);

        return $users;
    }

    public function get_total_user_per_pagination()
    {
        $total = $this->get_user_count();
        return (int) ($total / 2) + (($total % 2) == 0 ? 0 : 1);
    }

    public function get_all_user_data($page = '1', $number = '20', $search_str = '', $user_status = 'all', $interval = 'all', $user_ids = array())
    {
        $offset = ($page * $number) - $number;
        $all_user_info = $this->get_users($offset, $number, $search_str, $user_status, $interval, $user_ids);
        $all_user_data = array();

        foreach ($all_user_info as $user)
        {

            $tmpuser = new stdClass();
            $user_info = get_userdata($user->ID);
            $is_disabled = (int) get_user_meta($user->ID, 'rm_user_status', true);
            $tmpuser->ID = $user->ID;

            // echo'<pre>';var_dump($user_info);die;

            if (empty($user_info->display_name))
                $tmpuser->first_name = $user_info->first_name;
            else
                $tmpuser->first_name = $user_info->display_name;

            if (isset($user_info->user_email))
                $tmpuser->user_email = $user_info->user_email;
            else
                $tmpuser->user_email = '';

            if ($is_disabled == 1)
                $tmpuser->user_status = RM_UI_Strings::get('LABEL_DEACTIVATED');
            else
                $tmpuser->user_status = RM_UI_Strings::get('LABEL_ACTIVATED');

            $tmpuser->date = $user_info->user_registered;

            $all_user_data[] = $tmpuser;
        }

        return $all_user_data;
    }

    public function get_user_by($field, $value)
    {
        $user = get_user_by($field, $value);
        return $user;
    }

    public function login($request)
    {

        global $user;
        $credentials = array();
        $credentials['user_login'] = $request->req['username'];
        $credentials['user_password'] = $request->req['password'];
        if (isset($request->req['remember']))
            $credentials['remember'] = true;
        else
            $credentials['remember'] = false;

        require_once(ABSPATH . 'wp-load.php');
        require_once(ABSPATH . 'wp-includes/pluggable.php');
        $user = wp_signon($credentials, false);
        return $user;
    }

    public function facebook_login_html()
    {
        global $rm_env_requirements;

        if (!($rm_env_requirements & RM_REQ_EXT_CURL))
            return;

        global $rm_fb_sdk_req;
        $gopts = new RM_Options;
        $current_uri = RM_Utilities::get_current_url();
        //var_dump($current_uri);
        //var_dump($_GET['fbcb']);
        $sign = strpos($current_uri, '?') === FALSE ? '?' : '&';
        //var_dump($current_uri.$sign.'rm_target=fbcb');
        //die;
        if ($gopts->get_value_of('enable_facebook') == 'yes')
        {
            $fb_app_id = $gopts->get_value_of('facebook_app_id');
            $fb_app_secret = $gopts->get_value_of('facebook_app_secret');

            if (!$fb_app_id || !$fb_app_secret)
                return;

            if ($rm_fb_sdk_req === RM_FB_SDK_REQ_OK)
            {
                $fb = new Facebook\Facebook(array(
                    'app_id' => $fb_app_id,
                    'app_secret' => $fb_app_secret,
                    'default_graph_version' => 'v2.2',
                ));

                $helper = $fb->getRedirectLoginHelper();

                $permissions = array('email'); // Optional permissions
                $loginUrl = $helper->getLoginUrl($current_uri . $sign . 'rm_target=fbcb', $permissions);
                return '<div class="facebook_login"><a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a></div>';
            } else
            {
                $fb = new Facebook(array(
                    'appId' => $fb_app_id,
                    'secret' => $fb_app_secret
                ));

                $loginUrl = $fb->getLoginUrl(array('scope' => 'email', 'redirect_uri' => $current_uri . $sign . 'rm_target=fbcb'));
                return '<div class="facebook_login"><a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a></div>';
            }
        }
    }

    public function facebook_login_callback()
    {
        global $rm_env_requirements;

        if (!($rm_env_requirements & RM_REQ_EXT_CURL))
            return;

        global $rm_fb_sdk_req;
        $gopts = new RM_Options;

        $fb_app_id = $gopts->get_value_of('facebook_app_id');
        $fb_app_secret = $gopts->get_value_of('facebook_app_secret');

        if (!$fb_app_id || !$fb_app_secret)
            return;

        if ($rm_fb_sdk_req === RM_FB_SDK_REQ_OK)
        {
            $fb = new Facebook\Facebook(array(
                'app_id' => $fb_app_id,
                'app_secret' => $fb_app_secret,
                'default_graph_version' => 'v2.2',
            ));

            $helper = $fb->getRedirectLoginHelper();

            try
            {
                $accessToken = $helper->getAccessToken();
            } catch (Facebook\Exceptions\FacebookResponseException $e)
            {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e)
            {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            if (!isset($accessToken))
            {
                if ($helper->getError())
                {
                    header('HTTP/1.0 401 Unauthorized');
                    echo "Error: " . $helper->getError() . "\n";
                    echo "Error Code: " . $helper->getErrorCode() . "\n";
                    echo "Error Reason: " . $helper->getErrorReason() . "\n";
                    echo "Error Description: " . $helper->getErrorDescription() . "\n";
                } else
                {
                    header('HTTP/1.0 400 Bad Request');
                    echo 'Bad request';
                }
                exit;
            }

            // Logged in
            // echo '<h3>Access Token</h3>';
            //var_dump($accessToken->getValue());
            // The OAuth 2.0 client handler helps us manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();

            // Get the access token metadata from /debug_token
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);

            //echo '<h3>Metadata</h3>';
            //var_dump($tokenMetadata);
            // Validation (these will throw FacebookSDKException's when they fail)

            $tokenMetadata->validateAppId($fb_app_id); // Replace {app-id} with your app id
            // If you know the user ID this access token belongs to, you can validate it here
            //$tokenMetadata->validateUserId('123');
            $tokenMetadata->validateExpiration();

            if (!$accessToken->isLongLived())
            {
                // Exchanges a short-lived access token for a long-lived one
                try
                {
                    $accessToken2 = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (Facebook\Exceptions\FacebookSDKException $e)
                {
                    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                    exit;
                }

                //echo '<h3>Long-lived</h3>';
                //var_dump($accessToken2->getValue());
            }



            //$_SESSION['fb_access_token'] = (string) $accessToken;



            try
            {
                // Returns a `Facebook\FacebookResponse` object
                $response = $fb->get('/me?fields=id,name,email,first_name,last_name', (string) $accessToken);
            } catch (Facebook\Exceptions\FacebookResponseException $e)
            {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e)
            {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            $user = $response->getGraphUser();

            //var_dump($user->getFirstName());
            $user_name = $user->getName();
            $user_email = $user->getEmail();
            $user_name = $user->getName();
            $user_fname = $user->getFirstName();
            $user_lname = $user->getLastName();
            $redirection_post = $gopts->get_value_of('post_submission_redirection_url');

            if (email_exists($user_email))
            { // user is a member
                $user = get_user_by('email', $user_email);

                $user_id = $user->ID;
                
                $is_disabled = (int) get_user_meta($user_id, 'rm_user_status', true);
                        
                if(!$is_disabled)
                    wp_set_auth_cookie($user_id, true);
                
            } else
            { // this user is a guest
                $random_password = wp_generate_password(10, false);

                $user_id = wp_create_user($user_email, $random_password, $user_email);

                if (!is_wp_error($user_id))
                {
                    if (function_exists('is_multisite') && is_multisite())
                        add_user_to_blog(get_current_blog_id(), $user_id, 'subscriber');

                    update_user_meta($user_id, 'avatar_image', 'https://graph.facebook.com/' . $user->getId() . '/picture?type=large');

                    wp_update_user(array(
                        'ID' => $user_id,
                        'display_name' => $user_name,
                        'first_name' => $user_fname,
                        'last_name' => $user_lname
                    ));
                    
                    //varify auto approval setting
                    $auto_approval = $gopts->get_value_of('user_auto_approval');

                    if($auto_approval == 'yes')
                    {
                        wp_set_auth_cookie($user_id, true);
                    }
                    else  //Deactivate the user
                    {
                       update_user_meta($user_id, 'rm_user_status', '1');
                    }
                }
            }
        } else
        {
            $fb = new Facebook(array(
                'appId' => $fb_app_id,
                'secret' => $fb_app_secret
            ));

            $user = $fb->getUser();

            if ($user)
            {
                $user_profile = $fb->api('/me?fields=id,name,email,first_name,last_name');
                if (isset($user_profile['email']))
                {
                    $user_email = $user_profile['email'];
                    $redirection_post = $gopts->get_value_of('post_submission_redirection_url');

                    if (email_exists($user_email))
                    { // user is a member
                        $user = get_user_by('email', $user_email);
                        $user_id = $user->ID;
                        $is_disabled = (int) get_user_meta($user_id, 'rm_user_status', true);
                        if(!$is_disabled)
                            wp_set_auth_cookie($user_id, true);
                    } else
                    { // this user is a guest
                        $random_password = wp_generate_password(10, false);

                        $user_id = wp_create_user($user_email, $random_password, $user_email);
                        if (!is_wp_error($user_id))
                        {

                            if (function_exists('is_multisite') && is_multisite())
                                add_user_to_blog(get_current_blog_id(), $user_id, 'subscriber');

                            update_user_meta($user_id, 'avatar_image', 'https://graph.facebook.com/' . $user_profile['id'] . '/picture?type=large');

                            wp_update_user(array(
                                'ID' => $user_id,
                                'display_name' => $user_profile['name'],
                                'first_name' => $user_profile['first_name'],
                                'last_name' => $user_profile['last_name']
                            ));
                            //varify auto approval setting
                            $auto_approval = $gopts->get_value_of('user_auto_approval');

                            if($auto_approval == 'yes')
                            {
                                wp_set_auth_cookie($user_id, true);
                            }
                            else  //Deactivate the user
                            {
                               update_user_meta($user_id, 'rm_user_status', '1');
                            }
                        }
                    }
                } else
                    die('Error: Unable to fetch email address from Facebbok.');
            }
        }

        if ($redirection_post > 0)
        {
            $after_login_url = get_permalink($redirection_post);
        } else
        {
            $after_login_url = home_url();
        }
        RM_Utilities::redirect($after_login_url);
    }

    public function set_user_role($user_id, $role)
    {
        $user = new WP_User($user_id);
        $user->set_role($role);
    }

    public function reset_user_password($pass, $conf, $user_id)
    {
        if ($pass && $conf && $user_id)
        {
            if ($pass === $conf)
            {
                wp_set_password($pass, $user_id);
            }
        } else
        {
            throw new InvalidArgumentException("Invalid Argument Supplied in " . __CLASS__ . '::' . __FUNCTION__);
        }
    }

    public function create_user_activation_link($user_id)
    {
        if ((int) $user_id)
        {
            $pass = wp_generate_password(10, false);
            $activation_code = md5($pass);

            if (!update_user_meta($user_id, 'rm_activation_code', $activation_code))
                return false;

            $user_data_obj = new stdClass();
            $user_data_obj->user_id = $user_id;
            $user_data_obj->activation_code = $activation_code;

            $user_data_json = json_encode($user_data_obj);

            $user_data_enc = urlencode(RM_Utilities::enc_str($user_data_json));

            $user_activation_link = admin_url('admin-ajax.php') . '?action=rm_activate_user&user=' . $user_data_enc;

            return $user_activation_link;
        }

        return false;
    }

}
