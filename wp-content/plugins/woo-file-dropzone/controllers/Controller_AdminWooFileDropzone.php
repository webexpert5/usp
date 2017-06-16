<?php
defined('ABSPATH') or die('No script kiddies please!');

class Controller_AdminWooFileDropzone
{
    private $model = null;
    private $restrictedFileTypes = array(
        '.php',
        '.sh',
        '.exe',
        '.bat',
        '.jsp',
        '.asp',
        '.js',
        '.cpp',
        '.cs',
        'jsc'
    );

    public function __construct()
    {
        $this->model = new Model_WooFileDropzone();
        add_action('admin_init', [$this, 'loadScripts']);
        add_action('admin_menu', [$this, 'registerMenus']);
        add_action('wp_ajax_wc_file_dropzone_ajax_actions', [$this, 'fileDropzoneAllAjaxActionsCallback']);
        add_action('add_meta_boxes', [$this, 'addAdminOrderDetailMetaBox']);

    }

    public function loadScripts()
    {
        wp_register_script('woo_file_dropzone_form_validator.js',
            WOO_FILE_DROPZONE_PLUGIN_URL . 'assets/js/form-validator/jquery.form-validator.min.js',
            array('jquery')
        );
        wp_enqueue_script('woo_file_dropzone_form_validator.js');
    }

    public function registerMenus()
    {
        add_options_page('Woo File Dropzone',
            'Woo File Dropzone',
            'manage_options',
            'woo-file-dropzone-options',
            [$this, 'configureOptions']);
    }

    public function configureOptions()
    {

        $data = $this->model->getOptions();
        // boolean safe
        if (!$data) {
            $data['fields'] = array();
        }
        Helper_WooFileDropzone::loadView('options', $data);
    }

    public function fileDropzoneAllAjaxActionsCallback()
    {
        if (isset($_POST['do_action']) && $_POST['do_action'] == 'save_options') {
            parse_str($_POST['form_data'], $post);
            //Trim elements
            array_walk_recursive($post, 'trim');

            // Check if allowed types are not in restrictive file types
            $tempExtensions = explode(',', $post['allowedFileTypes']);
            $allowedExtensions = array();
            foreach ($tempExtensions as $extension) {
                if (!in_array(trim($extension), $this->restrictedFileTypes)) {
                    //Comma safe, if there are extra commas
                    if (strlen($extension) > 2) {
                        $allowedExtensions[] = $extension;
                    }
                }
            }
            $data = null;
            $counter = 1;
            foreach ($post['fieldLabel'] as $index => $value) {
                $fieldName = 'woo_file_dropzone_field_' . $counter++;
                $data['fields'][] = array(
                    'fieldLabel' => $value,
                    'showField' => $post['showField'][$index],
                    'fieldType' => $post['fieldType'][$index],
                    'isRequired' => $post['isRequired'][$index],
                    "fieldName" => $fieldName,
                );
            }
            $data['uploadButtonTitle'] = $post['uploadButtonTitle'];
            $data['sendButtonTitle'] = $post['sendButtonTitle'];
            $data['alreadyUploadedMsg'] = $post['alreadyUploadedMsg'];
            $data['allowedFileTypes'] = implode(',', $allowedExtensions);
            $data['fileTypeErrorMsg'] = $post['fileTypeErrorMsg'];
            $data['maxFileSize'] = $post['maxFileSize'];
            $data['showOn'] = $post['showOn'];
            $data['popupoverPosition'] = $post['popupoverPosition'];
            $data['allowedNumberOfFiles'] = $post['allowedNumberOfFiles'];
            $data['isFileUploadMandatory'] = isset($post['isFileUploadMandatory']) ? true : false;
            $this->model->saveOptions($data);
            echo json_encode(array('success' => true));
            wp_die();
        } elseif (isset($_POST['do_action']) && $_POST['do_action'] == 'delete_file') {
            $this->model->deleteFileByFileName($_POST['ID'], $_POST['fileName']);
            echo json_encode(array('success' => true));
            wp_die();
        }
    }


    public function addAdminOrderDetailMetaBox($postType)
    {
        add_meta_box(
            'wooFileDropzoneMetaBox',
            __('Woo File Dropzone', 'woo-file-dropzone'),
            [$this, 'renderMetaBoxContent'],
            'shop_order',
            'side',
            'low'
        );

    }

    public function renderMetaBoxContent($post)
    {
        $data['records'] = $this->model->getOrderDetails($post->ID);
        Helper_WooFileDropzone::loadView('admin-order-detail', $data);
    }


}
