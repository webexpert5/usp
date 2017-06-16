<?php

/**
 * Utilities of plugin
 *
 * @author cmshelplive
 */
class RM_Utilities {

    private $instance;
    private $table_name_for;

    private function __construct() {
        
    }

    private function __wakeup() {
        
    }

    private function __clone() {
        
    }

    public static function get_instance() {
        if (!isset(self::$instance) && !( self::$instance instanceof RM_Utilities )) {
            self::$instance = new RM_Utilities();
        }

        return self::$instance;
    }

    /**
     * Redirect user to a url or post permalink with some delay
     * 
     * @param string $url 
     * @param boolean $is_post      if set true url will not be used. will redirect the user to $post_id
     * @param int $post_id          ID of the post on which user will be redirected
     * @param boolean/int $delay    Delay in redirection(in ms) or default 5s is used if set true
     */
    public static function redirect($url='', $is_post = false, $post_id = 0, $delay = false) {

        if ($is_post && $post_id > 0) {
            $url = get_permalink($post_id);
        }

        if (headers_sent() || $delay) {
            if(defined('RM_AJAX_REQ'))
                $prefix = 'parent.';
            else
                $prefix = '';
            
            $string = '<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">';
            if ($delay === true) {
                $string .= "window.setTimeout(function(){".$prefix."window.location.href = '" . $url . "';}, 5000);";
            }elseif((int)$delay){
                $string .= "window.setTimeout(function(){".$prefix."window.location.href = '" . $url . "';}, ".(int)$delay.");";
            }else {
                $string .= $prefix.'window.location = "' . $url . '"';
            }

            $string .= '</script></pre>';

            echo $string;
        } else {
            if (isset($_SERVER['HTTP_REFERER']) AND ( $url == $_SERVER['HTTP_REFERER']))
                wp_redirect($_SERVER['HTTP_REFERER']);
            else
                wp_redirect($url);

            exit;
        }
    }

    public static function user_role_dropdown($placeholder = false) {
        $roles = array();
        if ($placeholder)
            $roles[null] = RM_UI_Strings::get('PH_USER_ROLE_DD');

        if (!function_exists('get_editable_roles'))
            require_once ABSPATH . 'wp-admin/includes/user.php';

        $user_roles = get_editable_roles();
        foreach ($user_roles as $key => $value) {
            $roles[$key] = $value['name'];
        }
        return $roles;
    }

    public static function wp_pages_dropdown($args = null) {
        $wp_pages = array('Select page');
        if ($args === null)
            $args = array(
                'depth' => 0,
                'child_of' => 0,
                'selected' => 0,
                'echo' => 1,
                'name' => 'page_id',
                'id' => null, // string
                'class' => null, // string
                'show_option_none' => null, // string
                'show_option_no_change' => null, // string
                'option_none_value' => null, // string
            );

        $pages = get_pages($args);
        foreach ($pages as $page) {
            if (!$page->post_title) {
                $page->post_title = "#$page->ID (no title)";
            }
            $wp_pages[$page->ID] = $page->post_title;
        }

        return $wp_pages;
    }

    public static function merge_object($args, $defaults = null) {
        if ($args instanceof stdClass)
            if (is_object($defaults))
                foreach ($defaults as $key => $default)
                    if (!isset($args->$key))
                        $args->$key = $default;

        return $args;
    }

    public static function get_field_types() {
        $field_types = array(
            null => 'Select A Field',
            'Textbox' => 'Text',
            'HTMLP' => 'Paragraph',
            'HTMLH' => 'Heading',
            'Select' => 'Drop Down',
            'Radio' => 'Radio Button',
            'Textarea' => 'Textarea',
            'Checkbox' => 'Checkbox',
            'jQueryUIDate' => 'Date',
            'Email' => 'Email',
            'Number' => 'Number',
            'Country' => 'Country',
            'Timezone' => 'Timezone',
            'Terms' => 'T&C Checkbox',
            'Price' => 'Pricing',
            'Fname' => 'First Name',
            'Lname' => 'Last Name',
            'BInfo' => 'Biographical Info',
            'Nickname' => 'Nickname',
            'Password' => 'Password',
            'Website' => 'Website'
        );
        return $field_types;
    }

    public static function after_login_redirect($user) {
        $post_id = get_option('rm_option_post_submission_redirection_url');
        if ($post_id != 0 && isset($user->roles) && is_array($user->roles)) {

            if (!in_array('administrator', $user->roles)) {
                $url = home_url("?p=" . $post_id);
                return $url;
            }
        }

        return admin_url();
    }

    public static function get_current_url() {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        $currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parts = parse_url($currentUrl);
        $query = '';
        if (!empty($parts['query'])) {
            // drop known fb params
            $params = explode('&', $parts['query']);
            $retained_params = array();
            foreach ($params as $param) {
                $retained_params[] = $param;
            } if (!empty($retained_params)) {
                $query = '?' . implode($retained_params, '&');
            }
        }        // use port if non default
        $port = isset($parts['port']) &&
                (($protocol === 'http://' && $parts['port'] !== 80) ||
                ($protocol === 'https://' && $parts['port'] !== 443)) ? ':' . $parts['port'] : '';        // rebuild
        return $protocol . $parts['host'] . $port . $parts['path'] . $query;
    }

    public static function get_forms_dropdown($service) {
        $forms = $service->get_all('FORMS', $offset = 0, $limit = 50, $column = '*', $sort_by = 'created_on', $descending = true);
        $form_dropdown_array = array();
        if ($forms)
            foreach ($forms as $form)
                $form_dropdown_array[$form->form_id] = $form->form_name;
        return $form_dropdown_array;
    }

    public static function get_paypal_field_types($service) {
        $pricing_fields = $service->get_all('PAYPAL_FIELDS', $offset = 0, $limit = 999999, $column = '*');
        //var_dump($pricing_fields);
        $field_dropdown_array = array();
        if ($pricing_fields)
            foreach ($pricing_fields as $field)
                $field_dropdown_array[$field->field_id] = $field->name;
        else
            $field_dropdown_array[null] = RM_UI_Strings::get('MSG_CREATE_PRICE_FIELD');

        return $field_dropdown_array;
    }

    public static function send_email($to, $data) {
        /*
         * Function to send email
         */
    }

    public static function trim_array($var) {
        if (is_array($var) || is_object($var))
            foreach ($var as $key => $var_)
                if (is_array($var))
                    $var[$key] = self::trim_array($var_);
                else
                    $var->$key = self::trim_array($var_);
        else
            $var = trim($var);

        return $var;
    }

    public static function escape_array($var) {
        if (is_array($var) || is_object($var))
            foreach ($var as $key => $var_)
                if (is_array($var))
                    $var[$key] = self::escape_array($var_);
                else
                    $var->$key = self::escape_array($var_);
        else
            $var = addslashes($var);

        return $var;
    }

    public static function strip_slash_array($var) {
        if (is_array($var) || is_object($var))
            foreach ($var as $key => $var_)
                if (is_array($var))
                    $var[$key] = self::strip_slash_array($var_);
                else
                    $var->$key = self::strip_slash_array($var_);
        else
            $var = stripslashes($var);

        return $var;
    }

    public static function get_current_time($time = null) {
        if (!is_numeric($time))
            return date('Y-m-d H:i:s');
        else
            return date('Y-m-d H:i:s', $time);
    }

    public static function create_submission_page() {
        global $wpdb;

        $submission_page = array(
            'post_type' => 'page',
            'post_title' => 'Submissions',
            'post_status' => 'publish',
            'post_name' => 'rm_submissions',
            'post_content' => '[RM_Front_Submissions]'
        );

        $page_id = get_option('rm_option_front_sub_page_id');

        if ($page_id) {
            $post = $wpdb->get_var("SELECT `ID` FROM  `" . $wpdb->prefix . "posts` WHERE  `post_content` LIKE  \"%[RM_Front_Submissions]%\" AND `post_status`='publish' AND `ID` = " . $page_id);
            if (!$post)
                $post = $wpdb->get_var("SELECT `ID` FROM  `" . $wpdb->prefix . "posts` WHERE  `post_content` LIKE  \"%[CRF_Submissions]%\" AND `post_status`='publish' AND `ID` = " . $page_id);
        } else {
            $post = $wpdb->get_var("SELECT `ID` FROM  `" . $wpdb->prefix . "posts` WHERE  `post_content` LIKE  \"%[RM_Front_Submissions]%\" AND `post_status`='publish'");
            if (!$post)
                $post = $wpdb->get_var("SELECT `ID` FROM  `" . $wpdb->prefix . "posts` WHERE  `post_content` LIKE  \"%[CRF_Submissions]%\" AND `post_status`='publish'");
        }

        if (!$post) {
            $page_id = wp_insert_post($submission_page);
            update_option('rm_option_front_sub_page_id', $page_id);
        } else {
            if ($page_id != $post)
                update_option('rm_option_front_sub_page_id', $post);
        }
    }

    public static function get_class_name_for($model_identifier) {
        $prefix = 'RM_';
        $class_name = $prefix . self::ucwords(strtolower($model_identifier));
        return $class_name;
    }

    public static function ucwords($string, $delimiter = " ") {
        if ($delimiter != " ") {
            $str = str_replace($delimiter, " ", $string);
            $str = ucwords($str);
            $str = str_replace(" ", $delimiter, $str);
        } elseif ($delimiter == " ")
            $str = ucwords($string);

        return $str;
    }

    public static function convert_to_unix_timestamp($mysql_timestamp) {
        return strtotime($mysql_timestamp);
    }

    public static function convert_to_mysql_timestamp($unix_timestamp) {
        return date("Y-m-d H:i:s", $unix_timestamp);
    }

    public static function create_pdf($html = null, $title = null) {
        require_once plugin_dir_path(dirname(__FILE__)) . 'external/tcpdf_min/tcpdf.php';
// create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Registration Magic');
        $pdf->SetTitle('Submission');
        $pdf->SetSubject('PDF for Submission');
        $pdf->SetKeywords('submission,pdf,print');

// set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 006', PDF_HEADER_STRING);
        $pdf->SetHeaderData('', '', $title, '');

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
        $pdf->SetFont('courier', '', 10);

// add a page
        $pdf->AddPage();

        //var_dump(htmlentities(ob_get_contents()));die;
// output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
        $pdf->lastPage();
        if (ob_get_contents()) {
            ob_end_clean();
        }

//Close and output PDF document
        $pdf->Output('rm_submission.pdf', 'D');
    }

    public static function create_json_for_chart($string_label, $numeric_label, array $dataset) {
        $data_table = new stdClass;
        $data_table->cols = array();
        $data_table->rows = array();
        $data_table->cols = array(
            // Labels for your chart, these represent the column titles
            // Note that one column is in "string" format and another one is in "number" format as pie chart only require "numbers" for calculating percentage and string will be used for column title
            (object) array('label' => $string_label, 'type' => 'string'),
            (object) array('label' => $numeric_label, 'type' => 'number')
        );

        $rows = array();

        foreach ($dataset as $name => $value) {
            $temp = array();
            // the following line will be used to slice the Pie chart
            $temp[] = (object) array('v' => (string) $name);

            // Values of each slice
            $temp[] = (object) array('v' => (int) $value);
            $rows[] = (object) array('c' => $temp);
        }
        $data_table->rows = $rows;
        $json_table = json_encode($data_table);
        return $json_table;
    }

    public static function HTMLToRGB($htmlCode) {
        if ($htmlCode[0] == '#')
            $htmlCode = substr($htmlCode, 1);

        if (strlen($htmlCode) == 3) {
            $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
        }

        $r = hexdec($htmlCode[0] . $htmlCode[1]);
        $g = hexdec($htmlCode[2] . $htmlCode[3]);
        $b = hexdec($htmlCode[4] . $htmlCode[5]);

        return $b + ($g << 0x8) + ($r << 0x10);
    }

    public static function RGBToHSL($RGB) {
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = ((float) $r) / 255.0;
        $g = ((float) $g) / 255.0;
        $b = ((float) $b) / 255.0;

        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);

        $l = ($maxC + $minC) / 2.0;

        if ($maxC == $minC) {
            $s = 0;
            $h = 0;
        } else {
            if ($l < .5) {
                $s = ($maxC - $minC) / ($maxC + $minC);
            } else {
                $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
            }
            if ($r == $maxC)
                $h = ($g - $b) / ($maxC - $minC);
            if ($g == $maxC)
                $h = 2.0 + ($b - $r) / ($maxC - $minC);
            if ($b == $maxC)
                $h = 4.0 + ($r - $g) / ($maxC - $minC);

            $h = $h / 6.0;
        }

        $h = (int) round(255.0 * $h);
        $s = (int) round(255.0 * $s);
        $l = (int) round(255.0 * $l);

        return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
    }

    public static function send_mail($email) {
        add_action('phpmailer_init', 'RM_Utilities::config_phpmailer');
        
        $success = true;
        
        if (!$email->to)
            return false;
        
        //Just in case if data has not been supplied, set proper default values so email function does not fail.
        $exdata = property_exists($email, 'exdata') ? $email->exdata : null;
        //Checking using isset instead of property_exists as we do not want to get null value getting passed as attachments.
        $attachments = isset($email->attachments) ? $email->attachments : array(); 
        
        if (is_array($email->to))
        {
            foreach ($email->to as $to)
            {
                
                if(!self::rm_wp_mail($email->type, $to, $email->subject, $email->message, $email->header, $exdata, $attachments));
                    $success = false;
            }
        } else
            $success = self::rm_wp_mail($email->type, $email->to, $email->subject, $email->message, $email->header, $exdata, $attachments);
        
        return $success;
       
    }
    
    //Sends a generic mail to a given address.
    public static function quick_email($to, $sub, $body)
    {
        $gopts = new RM_Options;
        $from_email = $gopts->get_value_of('senders_email_formatted');
        $header = "From: $from_email\r\n";
        $header.= "Content-Type: text/html; charset=utf-8\r\n";
        
        $email = new stdClass;
        $email->type = RM_EMAIL_GENERIC;
        $email->to = $to;
        $email->subject = $sub;
        $email->message = $body;        
        $email->header = $header;
        
        self::send_mail($email);
    }
    
    private static function rm_wp_mail($mail_type, $to, $subject, $message, $header, $additional_data = null, $attachments = array()) {
        
        $mails_not_to_be_saved = array(RM_EMAIL_USER_ACTIVATION_ADMIN,
                                       RM_EMAIL_PASSWORD_USER, 
                                       RM_EMAIL_POSTSUB_ADMIN,
                                      /* RM_EMAIL_NOTE_ADDED,*/
                                       RM_EMAIL_TEST);
        
        if(wp_mail($to, $subject, $message, $header, $attachments))
        {
            $sent_on = date('Y-m-d H:i:s');
            if(!in_array($mail_type, $mails_not_to_be_saved))
            {
                $form_id = null;
                $exdata = null;
                
                if(is_array($additional_data) && count($additional_data) > 0)
                {
                    if(isset($additional_data['form_id'])) $form_id = $additional_data['form_id'];
                    if(isset($additional_data['exdata'])) $exdata = $additional_data['exdata'];
                }
                $row_data = array('type' => $mail_type, 'to' => $to, 'sub' => htmlspecialchars($subject), 'body' => htmlspecialchars($message), 'sent_on' => $sent_on, 'headers' => $header, 'form_id' => $form_id,'exdata' => $exdata);
                $fmts = array('%d','%s','%s','%s','%s', '%s', '%d', '%s');
                
                RM_DBManager::insert_row('SENT_EMAILS', $row_data, $fmts);
            }
            return true;
        }
        else
            return false;          
    }

// format date string
    public static function localize_time($date_string, $dateformatstring = null, $advanced = false, $is_timestamp = false) {

        if ($is_timestamp) {
            $date_string = date('Y-m-d H:i:s', $date_string);
        }

        if (!$dateformatstring) {
            $df = get_option('date_format', null)? : 'd M Y';
            $tf = get_option('time_format', null)? : 'h:ia';
            $dateformatstring = $df . ' @ ' . $tf;
        }

        return get_date_from_gmt($date_string, $dateformatstring);
    }

    public static function mime_content_type($filename) {

        $mime_types = array(
            'txt' => 'text/plain',
            'csv' => 'text/csv; charset=utf-8',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $arr = explode('.', $filename);
        $ext = array_pop($arr);
        $ext = strtolower($ext);
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } else {
            return 'application/octet-stream';
        }
    }

    public static function config_phpmailer($phpmailer) {
        $options = new RM_Options;

        if ($options->get_value_of('enable_smtp') == 'yes') {
            $phpmailer->isSMTP();
            $phpmailer->SMTPDebug = 0;
            $phpmailer->Host = $options->get_value_of('smtp_host');
            $phpmailer->SMTPAuth = $options->get_value_of('smtp_auth') == 'yes' ? true : false;
            $phpmailer->Port = $options->get_value_of('smtp_port');
            $phpmailer->Username = $options->get_value_of('smtp_user_name');
            $phpmailer->Password = $options->get_value_of('smtp_password');
            $phpmailer->SMTPSecure = ($options->get_value_of('smtp_encryption_type') == 'enc_tls') ? 'tls' : (($options->get_value_of('smtp_encryption_type') == 'enc_ssl') ? 'ssl' : '' );
        }
        $phpmailer->From = $options->get_value_of('senders_email');
        $phpmailer->FromName = $options->get_value_of('senders_display_name');
        if(empty($phpmailer->AltBody))
            $phpmailer->AltBody = self::html_to_text_email($phpmailer->Body);

        return;
    }

    public static function check_smtp() {

        $options = new RM_Options;

        $bckup = $options->get_all_options();

        $email = isset($_POST['test_email']) ? $_POST['test_email'] : null;

        $options->set_values(array(
            'enable_smtp' => 'yes',
            'smtp_host' => isset($_POST['smtp_host']) ? $_POST['smtp_host'] : null,
            'smtp_auth' => isset($_POST['SMTPAuth']) ? $_POST['SMTPAuth'] : null,
            'smtp_port' => isset($_POST['Port']) ? $_POST['Port'] : null,
            'smtp_user_name' => isset($_POST['Username']) ? $_POST['Username'] : null,
            'smtp_password' => isset($_POST['Password']) ? $_POST['Password'] : null,
            'smtp_encryption_type' => isset($_POST['SMTPSecure']) ? $_POST['SMTPSecure'] : null,
            'senders_email' => isset($_POST['From']) ? $_POST['From'] : null,
            'senders_display_name' => isset($_POST['FromName']) ? $_POST['FromName'] : null
        ));
        if (!$email) {
            echo RM_UI_Strings::get('LABEL_FAILED');
            $options->set_values($bckup);
            die;
        }

        $test_email = new stdClass();
        $test_email->type = RM_EMAIL_TEST;
        $test_email->to = $email;
        $test_email->subject = 'Test SMTP Connection';
        $test_email->message = 'Test';
        $test_email->header = '';
        $test_email->attachments = array();
        if (self::send_mail($test_email))
            echo RM_UI_Strings::get('LABEL_SUCCESS');
        else
            echo RM_UI_Strings::get('LABEL_FAILED');

        $options->set_values($bckup);
        die;
    }

    public static function handle_rating_operations() {
        $type=$_POST['type'];
        $data=$_POST['info'];
        $options = new RM_Options;
        $service=  new RM_Services;
        $events= $options->get_value_of('review_events');
        if($type=='remind')
        {
            $events['event']=$service->get_review_event();
            $events['status']['flag']='remind';
            $events['status']['time']=date('Y-m-d');
        }
        elseif($type=='wordpress')
        {
            $events['event']=$service->get_review_event();
            $events['status']['flag']='reviewed';
            $events['status']['time']=date('Y-m-d');
        }
        elseif($type=='rating')
        {
            $events['rating']=$data;
        }
        elseif($type=='feedback')
        {
            $events['event']=$service->get_review_event();
            $events['status']['flag']='feedback';
            $events['status']['time']=date('Y-m-d');
        }
        else
        {
            
        }
         $options->set_value_of('review_events',$events); 
         die;
    }

    public static function disable_newsletter_banner() {
        global $rm_env_requirements;

        if ($rm_env_requirements & RM_REQ_EXT_CURL) {
            require_once RM_EXTERNAL_DIR . "Xurl/rm_xurl.php";

            $xurl = new RM_Xurl("https://registrationmagic.com/subscribe_to_newsletter/");

            if (function_exists('is_multisite') && is_multisite()) {
                $nl_sub_mail = get_site_option('admin_email');
            } else {
                $nl_sub_mail = get_option('admin_email');
            }

            $user = get_user_by('email', $nl_sub_mail);
            $req_arr = array('sub_email' => $nl_sub_mail, 'fname' => $user->first_name, 'lname' => $user->last_name);

            $xurl->post($req_arr);
        }
        if (function_exists('is_multisite') && is_multisite()) {
            update_site_option('rm_option_newsletter_subbed', 1);
        } else {
            update_option('rm_option_newsletter_subbed', 1);
        }

        wp_die();
    }

    public static function is_ssl() {
        //return true;
        return is_ssl();
    }

    //More reliable check for write permission to a directory than the php native is_writable.
    public static function is_writable_extensive_check($path) {
        //NOTE: use a trailing slash for folders!!!
        if ($path{strlen($path) - 1} == '/') // recursively return a temporary file path
            return self::is_writable_extensive_check($path . uniqid(mt_rand()) . '.tmp');
        else if (is_dir($path))
            return self::is_writable_extensive_check($path . '/' . uniqid(mt_rand()) . '.tmp');
        // check tmp file for read/write capabilities
        $rm = file_exists($path);
        $f = @fopen($path, 'a');
        if ($f === false)
            return false;
        fclose($f);
        if (!$rm)
            unlink($path);
        return true;
    }

    //Check for fatal errors with which can not continue.
    public static function fatal_errors() {
        global $rm_env_requirements;
        global $regmagic_errors;
        $fatality = false;
        $error_msgs = array();
        
        //Now check for any other remaining errors that might be originally in the global variable
        foreach ($regmagic_errors as $err) {
            if (!$err->should_cont) {
                $fatality = true;
                break;
            }
        }

        if (!($rm_env_requirements & RM_REQ_EXT_MCRYPT)) {
            $regmagic_errors[RM_ERR_ID_EXT_MCRYPT] = (object) array('msg' => RM_UI_Strings::get('CRIT_ERR_MCRYPT'), 'should_cont' => false); //"PHP extension mcrypt is not enabled on server. This plugin cannot function without it.";
            $fatality = true;
        }
        if (!($rm_env_requirements & RM_REQ_EXT_SIMPLEXML)) {
            $regmagic_errors[RM_ERR_ID_EXT_SIMPLEXML] = (object) array('msg' => RM_UI_Strings::get('CRIT_ERR_XML'), 'should_cont' => false); //"PHP extension SimpleXML is not enabled on server. This plugin cannot function without it.";
            $fatality = true;
        }

        if (!($rm_env_requirements & RM_REQ_PHP_VERSION)) {
            $regmagic_errors[RM_ERR_ID_PHP_VERSION] = (object) array('msg' => RM_UI_Strings::get('CRIT_ERR_PHP_VERSION'), 'should_cont' => false); //"This plugin requires atleast PHP version 5.3. Cannot continue.";
            $fatality = true;
        }

        if (!($rm_env_requirements & RM_REQ_EXT_CURL)) {
            $regmagic_errors[RM_ERR_ID_EXT_CURL] = (object) array('msg' => RM_UI_Strings::get('RM_ERROR_EXTENSION_CURL'), 'should_cont' => true);
        }

        if (!($rm_env_requirements & RM_REQ_EXT_ZIP)) {
            $regmagic_errors[RM_ERR_ID_EXT_ZIP] = (object) array('msg' => RM_UI_Strings::get('RM_ERROR_EXTENSION_ZIP'), 'should_cont' => true);
        }

        
        return $fatality;
    }

    public static function rm_error_handler($errno, $errstr, $errfile, $errline) {
        global $regmagic_errors;

        var_dump($errno);
        var_dump($errstr);

        return true;
    }
    
    public static function is_banned_ip($ip_to_check, $format)
    {
        if($format === null)
            return false;
        
        $matchrx = '/';
        $gen_regex = array('[0-2]','[0-9]','[0-9]','\.',
                           '[0-2]','[0-9]','[0-9]','\.',
                           '[0-2]','[0-9]','[0-9]','\.',
                           '[0-2]','[0-9]','[0-9]');
        
        for($i=0;$i<15;$i++)
        {
            if($format[$i] == '?' || $format[$i] == '.')
                $matchrx .= $gen_regex[$i];
            else
                $matchrx .= $format[$i];
        }
        
        $matchrx .= '/';
        
        if(preg_match ( $matchrx , $ip_to_check) === 1)
           return true;
        else
           return false;
    }
    
    public static function is_banned_email($email_to_check, $format)
    {
        if(!$format)
            return false;
        
        $matchrx = '/';
        
        $gen_regex = array('?' => '.',
                           '*' => '.*',
                           '.' => '\.'
                            );
        
        $formatlen = strlen($format);
        
        for($i=0; $i<$formatlen; $i++)
        {
            if($format[$i] == '?' || $format[$i] == '.' || $format[$i] == '*')
                $matchrx .= $gen_regex[$format[$i]];
            else
                $matchrx .= $format[$i];
        }
        
        $matchrx .= '/';
        
        //Following check is employed instead preg_match so that partial matches
        //will not get selected unless user specifies using wildcard '*'.      
        $test = preg_replace ( $matchrx, '', $email_to_check);        
        
        if($test == '')
            return true;
        else
            return false;
    }

    public static function enc_str($string) {
        $key = 'A Terrific tryst with tyranny';

        $iv = mcrypt_create_iv(
                mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM
        );

        $encrypted = base64_encode($iv . mcrypt_encrypt(
                        MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), $string, MCRYPT_MODE_CBC, $iv
                )
        );
        return $encrypted;
    }

    public static function dec_str($string) {
        $key = 'A Terrific tryst with tyranny';

        $data = base64_decode($string);
        $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

        $decrypted = rtrim(
                mcrypt_decrypt(
                        MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)), MCRYPT_MODE_CBC, $iv
                ), "\0"
        );

        return $decrypted;
    }

    public static function link_activate_user() {
        $req = $_GET['user'];

        $user_service = new RM_User_Services();

        $req_deco = self::dec_str($req);

        $user_data = json_decode($req_deco);

        echo '<!DOCTYPE html>
                    <html>
                    <head>
                      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                      <meta http-equiv="Content-Style-Type" content="text/css">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                      <title></title>
                      <meta name="Generator" content="Cocoa HTML Writer">
                      <meta name="CocoaVersion" content="1404.34">
                        <link rel="stylesheet" type="text/css" href="' . RM_BASE_URL . 'admin/css/style_rm_admin.css">
                    </head>
                    <body class="rmajxbody">
        <div class="rmagic">';

        echo '<div class="rm_user_activation_msg">';

        if ($user_data->activation_code == get_user_meta($user_data->user_id, 'rm_activation_code', true)) {
            if (!delete_user_meta($user_data->user_id, 'rm_activation_code')) {
                echo '<div class="rm_fail_del">' . RM_UI_Strings::get('ACT_AJX_FAILED_DEL') . '</div>';
                die;
            }

            if ($user_service->activate_user_by_id($user_data->user_id)) {
                 $users=array($user_data->user_id);
                $user_service-> notify_users($users,'user_activated');
                echo '<h1 class="rm_user_msg_ajx">' . RM_UI_Strings::get('ACT_AJX_ACTIVATED') . '</h1>';
                $user = get_user_by('id', $user_data->user_id);
                echo '<div class = rm_user_info><div class="rm_field_cntnr"><div class="rm_user_label">' . RM_UI_Strings::get('LABEL_USER_NAME') . ' : </div><div class="rm_label_value">' . $user->user_login . '</div></div><div class="rm_field_cntnr"><div class="rm_user_label">' . RM_UI_Strings::get('LABEL_USEREMAIL') . ' : </div><div class="rm_label_value">' . $user->user_email . '</div></div></div>';
                echo '<div class="rm_user_msg_ajx">' . RM_UI_Strings::get('ACT_AJX_ACTIVATED2') . '</div>';
            } else
                echo '<div class="rm_not_authorized_ajax rm_act_fl">' . RM_UI_Strings::get('ACT_AJX_ACTIVATE_FAIL') . '</div>';
        } else
            echo '<div class="rm_not_authorized_ajax">' . RM_UI_Strings::get('ACT_AJX_NO_ACCESS') . '</div>';

        echo '</div></div></html></body>';
        /* ?>
          <button type="button" onclick="window.location.reload()">Retry</button>
          <button type="button" onclick="window.history.back()">GO BACK</button>
          <?php */
        die;
    }
    
    public static function html_to_text_email($html){
        $html = str_replace('<br>', "\r\n", $html);
        $html = str_replace('<br/>', "\r\n", $html);
        $html = str_replace('</br>', "\r\n", $html);
        
        $html = strip_tags($html);
        
        return trim($html);
    } 
    
   
    public static function set_default_form()
    {
        if(isset($_POST['rm_def_form_id']))
        {
            $gopts = new RM_Options;
            $gopts->set_value_of('default_form_id', $_POST['rm_def_form_id']);
        }
        die;
    }
    
    //One time login
    public static function safe_login()
    {   
        if(isset($_SESSION['RM_SLI_UID']))
        {           
            if(get_user_meta($_SESSION['RM_SLI_UID'], 'rm_user_status',true) === '0')
                wp_set_auth_cookie($_SESSION['RM_SLI_UID']);            
            unset($_SESSION['RM_SLI_UID']);            
        }
    }
    
    //Loads scripts without wp_enque_script for ajax calls.
    public static function enqueue_external_scripts($handle, $src = false, $deps = array(), $ver = false, $in_footer = false){
        if(!defined('RM_AJAX_REQ')){
            if (!wp_script_is($handle, 'enqueued'))
                    wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
        }elseif(!isset(self::$script_handle[$handle])){
            self::$script_handle[$handle] = $src;
            return '<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript" src="'. $src. '"></script></pre>';
        }
        
    }
    
     /*
     * Loads all the data requires in JS 
     * It will allow to use language strings in JS
     */
    public static function load_admin_js_data(){
        $data= new stdClass();
        echo json_encode($data);
         die;
         
    }
    
    public static function load_js_data(){
        $data= new stdClass();
        
        // Validation message override
        $data->validations= array();
        $data->validations['required']= RM_UI_Strings::get("VALIDATION_REQUIRED");
        $data->validations['email']= RM_UI_Strings::get("INVALID_EMAIL");
        $data->validations['url']= RM_UI_Strings::get("INVALID_URL");
        $data->validations['pattern']= RM_UI_Strings::get("INVALID_FORMAT");
        $data->validations['number']= RM_UI_Strings::get("INVALID_NUMBER");
        $data->validations['digits']= RM_UI_Strings::get("INVALID_DIGITS");        
        $data->validations['maxlength']= RM_UI_Strings::get("INVALID_MAXLEN");
        $data->validations['minlength']= RM_UI_Strings::get("INVALID_MINLEN");
        $data->validations['max']= RM_UI_Strings::get("INVALID_MAX");
        $data->validations['min']= RM_UI_Strings::get("INVALID_MIN");        
        
        echo json_encode($data);
        wp_die();
         
    }
    
    public static function save_submit_label(){
     $form_id=$_POST['form_id'];
     $label=$_POST['label'];
    
     $form=new RM_Forms;
     $form->load_from_db($form_id);
     $form->form_options->form_submit_btn_label=$label;
     $form->update_into_db();
     echo "changed";die;
    }
    
    public static function update_tour_state($tour_id, $state)
    {
        $gopts = new RM_Options;
        
        $existing_tour = $gopts->get_value_of('tour_state');
        
        if(is_array($existing_tour))
        {
            $existing_tour[$tour_id] = strtolower($state);
        }
        $gopts->set_value_of('tour_state', $existing_tour);
    }    
    
    public static function has_taken_tour($tour_id)
    {
        $gopts = new RM_Options;
        
        $existing_tour = $gopts->get_value_of('tour_state');
        
        if(isset($existing_tour[$tour_id]))
            return ($existing_tour[$tour_id] == 'taken');
        else
            return false;
    }
    
    public static function update_tour_state_ajax()
    {
        $tour_id = $_POST['tour_id'];
        $state = $_POST['state'];
        
        self::update_tour_state($tour_id, $state);
        wp_die();
    }
    
    public static function process_field_options($value)
    {
       $p_options = array();
       
       if(!is_array($value))
           $tmp_options = explode(',', $value);
       else
           $tmp_options = $value;
               
       foreach($tmp_options as $val)
       {
           $val = trim($val);
           $val = trim($val, "|");
           $t = explode("|",$val);

           if(count($t) <= 1 || trim($t[1]) === "")
               $p_options[$val] = $val;
           else
               $p_options[trim($t[1])] = trim($t[0]);
       }
       
       return $p_options;
   }
   
   public static function get_lable_for_option($field_id, $opt_value)
   {
       $rmf = new RM_Fields;
       if(!$rmf->load_from_db($field_id))
           return $opt_value;
       
       //Return same value if it is not a multival field
       if(!in_array($rmf->field_type, array('Checkbox','Radio','Select')))
           return $opt_value;
       
       $val = $rmf->get_field_value();
       $p_opts = self::process_field_options($val);
       
       if(!is_array($opt_value))
       {
           if(isset($p_opts[$opt_value]))
               return $p_opts[$opt_value];
           else
               return $opt_value;
       }
       else
       {
           $tmp = array();
           foreach($opt_value as $val)
           {
               if(isset($p_opts[$val]))
                   $tmp[] = $p_opts[$val];
               else
                   $tmp[] = $val;
           }
           return $tmp;
       }
   }
   
   //Print nested array like vars as html table.
    public static function var_to_html($variable)
    {
        $html = "";

        if (is_array($variable) || is_object($variable))
        {
            $html .=  "<table style='border:none; padding:3px; width:100%; margin: 0px;'>";
            if(count($variable) === 0) $html .= "empty";
            foreach ($variable as $k => $v) {
                    $html .=  '<tr><td style="background-color:#F0F0F0; vertical-align:top; min-width:100px;">';
                    $html .=  '<strong>' . $k . "</strong></td><td>";
                    $html .=  self::var_to_html($v);
                    $html .=  "</td></tr>";
            }

            $html .=  "</table>";
            return $html;
        }

        $html .=  $variable ? $variable : "NULL";
        return $html;
   }
   
   public static function is_date_valid()
   {
       $date = $_POST['date'];
       
       try {
            $test = new DateTime($date);
            echo "VALID";
        } catch(Exception $e) {
            echo "INVALID";
        }
        
        wp_die();
   }
}
