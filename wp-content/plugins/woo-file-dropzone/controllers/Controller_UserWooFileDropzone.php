<?php
defined('ABSPATH') or die('No script kiddies please!');

class Controller_UserWooFileDropzone
{
    /**
     * Test comment
     */
    private $model = null;
    private $wpdb = null;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->model = new Model_WooFileDropzone();
        if (defined('DOING_AJAX') && DOING_AJAX) {
            //Ajax related stuff
            add_action('wp_ajax_woo_file_dropzone_user_ajax_actions', [$this, 'ajaxOperations']);
            add_action('wp_ajax_nopriv_woo_file_dropzone_user_ajax_actions', [$this, 'ajaxOperations']);
        } else {

            // Register Scripts
            add_action('wp_enqueue_scripts', [$this, 'registerScripts'], 99);

            // On order complete/pending etc
            add_action('woocommerce_order_status_changed', [$this, 'updateOrderStatus'], 99, 3);

            //Remove cart item key functionality if removed item then clear its files
            add_action('woocommerce_remove_cart_item', [$this, 'actionWhenItemRemovedFromCart'], 10, 2);

            add_shortcode('woo_file_dropzone_product_detail_page', [$this, 'getForShortcodeOnProductDetailPage'], 10);

            add_shortcode('woo_file_dropzone_cart_page', [$this, 'getForShortcodeOnCartPage'], 10, 1);

            add_shortcode('woo_file_dropzone_checkout_page', [$this, 'getForShortcodeOnCheckoutPage'], 10, 1);

            add_action('woocommerce_add_to_cart', [$this, 'updateSessionWhenAddToCart'], 10, 2);

            $this->init();
        }

    }

    public function registerScripts()
    {
        wp_enqueue_style('woo_file_dropzone_min.css',
            WOO_FILE_DROPZONE_PLUGIN_URL . 'assets/js/dropzone-4.3.0/dist/min/dropzone.min.css');

        wp_register_script('woo_file_dropzone_min.js',
            WOO_FILE_DROPZONE_PLUGIN_URL . 'assets/js/dropzone-4.3.0/dist/dropzone.js',
            array('jquery'), '', true);
        wp_enqueue_script('woo_file_dropzone_min.js');

        wp_enqueue_style('woo_file_dropzone_bootstrap-iso.css',
            WOO_FILE_DROPZONE_PLUGIN_URL . 'assets/css/bootstrap-iso.css');

        wp_register_script('woo_file_dropzone_bootstrap.min.js',
            WOO_FILE_DROPZONE_PLUGIN_URL . 'assets/js/bootstrap.min.js',
            array('jquery'), '', true);
        wp_enqueue_script('woo_file_dropzone_bootstrap.min.js');

    }

    // All ajax related stuff
    public function ajaxOperations()
    {

        if (isset($_POST['do_action']) && $_POST['do_action'] == 'uploadFile') {

            if (!wp_verify_nonce($_POST['woo_file_dropzone_upload_nonce'], 'woo_file_dropzone_upload')) {
                Helper_LoggerWooFileDropzone::log(3, 'Nonce Not Matched');
                wp_die('Sorry we can not process');
            }

            $sessionID = null;
            if (WC()->session->get('_woo_file_dropzone_sessionID')) {
                $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');
            } else {
                $sessionID = $this->model->generateUniqueSessionID();

                // Check if user is not logged in (guest)
                if (!is_user_logged_in()) {
                    // Check if cart is not empty then store in default woocommerce session.
                    if (WC()->cart->get_cart_contents_count() > 0) {
                        WC()->session->set('_woo_file_dropzone_sessionID', $sessionID);
                    } else {
                        if (!session_id()) {
                            session_start();
                        }
                        // Cart is empty use PHP session instead, because if cart is empty
                        // Woocommerce will remove all stored data with every request.
                        $_SESSION['_woo_file_dropzone_sessionID'] = $sessionID;

                    }
                } else {
                    WC()->session->set('_woo_file_dropzone_sessionID', $sessionID);
                }
            }

            $options = $this->model->getOptions();

            try {
                $upload = new Lib_UploaderWooFileDropzone(
                    WOO_FILE_DROPZONE_UPLOAD_FOLDER,
                    WOO_FILE_DROPZONE_UPLOAD_CONTENT_DIR
                );
            } catch (Exception $e) {
                // Can't create directory exception
                Helper_LoggerWooFileDropzone::log(1, $e->getMessage());
            }

            $mimeTypes = array();

            // Get MimeType Form Extensions
            foreach (explode(',', $options['allowedFileTypes']) as $ext) {
                $tempMimeType = $upload->get_mime_type($ext);
                if ($tempMimeType) {
                    $mimeTypes[] = $tempMimeType;
                } else {
                    Helper_LoggerWooFileDropzone::log(1, 'Mime Type Added by Admin Not Mapped With Array in Uploader Class');
                }
            }

            $files = array();
            //loop through all file() array
            $total_files = count($_FILES['file']['name']);
            $start = key($_FILES['file']['name']);
            $total_files += $start;

            for ($i = $start; $i < $total_files; $i++) {
                $files[] = array(
                    'name' => $_FILES['file']['name'][$i],
                    'type' => $_FILES['file']['type'][$i],
                    'tmp_name' => $_FILES['file']['tmp_name'][$i],
                    'error' => $_FILES['file']['error'][$i],
                    'size' => $_FILES['file']['size'][$i],
                );
            }

            $response['success'] = true;
            $tempResponseFileErrors = array();
            $fileNames = array();

            foreach ($files as $file) {
                //Initializing again because Conflict with random file generator and mimetype check
                try {
                    $upload = new Lib_UploaderWooFileDropzone(
                        WOO_FILE_DROPZONE_UPLOAD_FOLDER,
                        WOO_FILE_DROPZONE_UPLOAD_CONTENT_DIR
                    );

                    // load file array in uploader
                    $upload->file($file);

                    //set max. file size (in mb)
                    $upload->set_max_file_size($options['maxFileSize']);

                    //set allowed mime types
                    $upload->set_allowed_mime_types($mimeTypes);

                    $results = $upload->upload();

                } catch (Exception $e) {
                    Helper_LoggerWooFileDropzone::log(1, $e->getMessage());
                }
                if (!$results['status']) {
                    $tempResponseFileErrors[] = $results['errors'];
                    $response['success'] = false;
                    //Log Error
                    Helper_LoggerWooFileDropzone::log(1, implode(',', $results['errors']));
                    break;
                } else {
                    $fileNames[] = $results['filename'];
                }
            }

            if ($response['success']) {

                $custom_fields_data = array();

                //Get all fields if there are
                $_tempFields = json_decode(stripslashes($_POST['fields']));

                if (!empty($_tempFields)) {
                    foreach ($_tempFields as $key => $value) {
                        if (preg_match('/^woo_file_dropzone_field_*/', $key)) {
                            $custom_fields_data[$key] = $this->wpdb->_escape($value);
                        }
                    }
                }

                //48 hours as standard woocommerce session expiry time
                $session_expiry_time = time() + (60 * 60 * 48);

                $dbRow = array(
                    'session_id' => $sessionID,
                    'file_urls' => serialize($fileNames),
                    'product_id' => $_POST['product_id'],
                    'variation_id' => (int)$_POST['variation_id'],
                    'extra_fields' => serialize($custom_fields_data),
                    'session_expiry_time' => $session_expiry_time
                );

                $this->model->saveUploadedImages($dbRow);
            } else {
                //errors
                $response['errors'] = $tempResponseFileErrors;
            }
            wp_send_json($response);
            wp_die();

        } elseif (isset($_POST['do_action']) && $_POST['do_action'] == 'getUploadedFiles') {
            $response = $this->getFilesToLoadInBox($_POST['product_id'], (int)$_POST['variation_id']);
            wp_send_json($response);
            wp_die();
        } elseif (isset($_POST['do_action']) && $_POST['do_action'] == 'deleteUploadedFile') {
            $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');
            $row = null;
            if ($_POST['variation_id']) {
                $row = $this->model->getFileDetailsByVariationId((int)$_POST['variation_id'], $sessionID);
            } else {
                $row = $this->model->getFileDetailsByProductId($_POST['product_id'], $sessionID);
            }
            $this->model->deleteFileByFileName($row[0]->id, $_POST['fileName']);
            wp_die();
        }

    }

    // Initialization of front section forms
    public function init()
    {
        $options = $this->model->getOptions();
        if (strcmp($options['showOn'], 'productDetailPage') === 0) {
            add_action('woocommerce_before_add_to_cart_button', [$this, 'getForProductDetailPage']);

        } elseif (strcmp($options['showOn'], 'cartPage') === 0) {
            add_action('woocommerce_after_cart_contents', [$this, 'getForCartPage']);

        } elseif (strcmp($options['showOn'], 'checkoutPage') === 0) {
            add_action('woocommerce_review_order_after_cart_contents', [$this, 'getForCheckoutPage']);
        }

        // Start php session first if it is not started
        if (!session_id()) {
            session_start();
        }
    }

    //Get box for order detail page
    public function getForProductDetailPage()
    {

        global $product;
        $data = $this->model->getOptions();
        /**
         * Check if session is set, it means user have already uploaded some files
         * If files are against this product or variation, let user know that he/she has
         * already uploaded files
         */
        $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');

        if(is_null($sessionID)){
            $sessionID = isset($_SESSION['_woo_file_dropzone_sessionID']) ? $_SESSION['_woo_file_dropzone_sessionID'] : 0 ;
        }

        $data['isFileAlreadyUploadedForThisProduct'] = false;

        $variation_ids = array();//can be more than one
        if ($sessionID) {
            $records = $this->model->getFileDetailsByProductId($product->id, $sessionID);

            if ($records) {
                foreach ($records as $row) {
                    if ($row->variation_id != 0) {
                        $variation_ids[] = $row->variation_id;
                    }
                }
                $data['isFileAlreadyUploadedForThisProduct'] = true;
            }
        }
        $data['product_id'] = $product->id;
        $data['variation_ids'] = $variation_ids;
        Helper_WooFileDropzone::loadView('product-detail-page', $data);
    }

    //Get box for Cart Page
    public function getForCartPage()
    {
        $data = $this->model->getOptions();
        $items = WC()->cart->get_cart();

        foreach ($items as $key => $item) {

            $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');
            $data['isFileAlreadyUploadedForThisProduct'] = false;
            $data['variation_id'] = $item['variation_id'];
            $data['product_id'] = $item['product_id'];
            if ($sessionID) {

                //if variation id in cart then item was added with variation id
                if ($item['variation_id'] != 0) {
                    $records = $this->model->getFileDetailsByVariationId($item['variation_id'], $sessionID);
                    if ($records) {
                        $row = $records[0];
                        $data['isFileAlreadyUploadedForThisProduct'] = true;
                    }
                } //product was added with product_id
                else {
                    $records = $this->model->getFileDetailsByProductId($item['product_id'], $sessionID);
                    if ($records) {
                        $data['isFileAlreadyUploadedForThisProduct'] = true;
                    }
                }

            }

            Helper_WooFileDropzone::loadView('cart-page', $data);
        }
    }

    ///Get box for checkout page
    public function getForCheckoutPage()
    {
        //If not checked is ajax, it will show form two times
        if (!is_ajax()) {
            $data = $this->model->getOptions();
            $items = WC()->cart->get_cart();

            foreach ($items as $key => $item) {
                $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');
                $data['isFileAlreadyUploadedForThisProduct'] = false;
                $data['variation_id'] = $item['variation_id'];
                $data['product_id'] = $item['product_id'];
                if ($sessionID) {

                    //if variation id in cart then item was added with variation id
                    if ($item['variation_id'] != 0) {
                        $records = $this->model->getFileDetailsByVariationId($item['variation_id'], $sessionID);
                        if ($records) {
                            $data['isFileAlreadyUploadedForThisProduct'] = true;
                        }
                    } //product was added with product_id
                    else {
                        $records = $this->model->getFileDetailsByProductId($item['product_id'], $sessionID);
                        if ($records) {
                            $data['isFileAlreadyUploadedForThisProduct'] = true;
                        }
                    }

                }
                Helper_WooFileDropzone::loadView('checkout-page', $data);
            }
        }
    }

    //for shortcodes
    public function getForShortcodeOnProductDetailPage()
    {
        global $product;
        $data = $this->model->getOptions();
        /**
         * Check if session is set, it means user have already uploaded some files
         * If files are against this product or variation, let user know that he/she has
         * already uploaded files
         */
        $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');

        if(is_null($sessionID)){
            $sessionID = isset($_SESSION['_woo_file_dropzone_sessionID']) ? $_SESSION['_woo_file_dropzone_sessionID'] : 0 ;
        }


        $data['isFileAlreadyUploadedForThisProduct'] = false;

        $variation_ids = array();//can be more than one

        if ($sessionID) {
            $records = $this->model->getFileDetailsByProductId($product->id, $sessionID);
            if ($records) {
                foreach ($records as $row) {
                    if ($row->variation_id != 0) {
                        $variation_ids[] = $row->variation_id;
                    }
                }
                $data['isFileAlreadyUploadedForThisProduct'] = true;
            }
        }
        $data['product_id'] = $product->id;
        $data['variation_ids'] = $variation_ids;
        Helper_WooFileDropzone::loadView('product-detail-page', $data);

    }

    public function getForShortcodeOnCartPage($attr)
    {
        $params = shortcode_atts(array(
            'cart_item_key' => '1'
        ), $attr);

        $data = $this->model->getOptions();
        $items = WC()->cart->get_cart();
        foreach ($items as $key => $item) {
            if (strcmp($key, $params['cart_item_key']) === 0) {
                $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');
                $data['isFileAlreadyUploadedForThisProduct'] = false;
                $data['variation_id'] = $item['variation_id'];
                $data['product_id'] = $item['product_id'];
                if ($sessionID) {
                    //if variation id in cart then item was added with variation id
                    if ($item['variation_id'] != 0) {
                        $records = $this->model->getFileDetailsByVariationId($item['variation_id'], $sessionID);
                        if ($records) {
                            $data['isFileAlreadyUploadedForThisProduct'] = true;
                        }
                    } //product was added with product_id
                    else {
                        $records = $this->model->getFileDetailsByProductId($item['product_id'], $sessionID);
                        if ($records) {
                            $data['isFileAlreadyUploadedForThisProduct'] = true;
                        }
                    }
                }
                Helper_WooFileDropzone::loadView('cart-page', $data);
            }
        }

    }

    public function getForShortcodeOnCheckoutPage($attr)
    {
        $params = shortcode_atts(array(
            'cart_item_key' => '1'
        ), $attr);

        //If not checked is ajax, it will show form two times
        if (is_ajax()) {
            $data = $this->model->getOptions();
            $items = WC()->cart->get_cart();
            foreach ($items as $key => $item) {
                if (strcmp($key, $params['cart_item_key']) === 0) {
                    $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');
                    $data['isFileAlreadyUploadedForThisProduct'] = false;
                    $data['variation_id'] = $item['variation_id'];
                    $data['product_id'] = $item['product_id'];
                    if ($sessionID) {
                        //if variation id in cart then item was added with variation id
                        if ($item['variation_id'] != 0) {
                            $records = $this->model->getFileDetailsByVariationId($item['variation_id'], $sessionID);
                            if ($records) {
                                $data['isFileAlreadyUploadedForThisProduct'] = true;
                            }
                        } //product was added with product_id
                        else {
                            $records = $this->model->getFileDetailsByProductId($item['product_id'], $sessionID);
                            if ($records) {
                                $data['isFileAlreadyUploadedForThisProduct'] = true;
                            }
                        }
                    }
                    Helper_WooFileDropzone::loadView('checkout-page', $data);
                }
            }
        }
    }

    //Clear files and db entries if item is no longer exists in cart
    public function actionWhenItemRemovedFromCart($cart_item_key, $cart)
    {
        $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');

        //Do process if file were uploaded
        if ($sessionID) {
            $product = WC()->cart->get_cart_item($cart_item_key);

            if ($product['variation_id'] != 0) {
                //Remove if product was added by variation id
                $result = $this->model->getFileDetailsByVariationId($product['variation_id'], $sessionID);
                if ($result) {
                    $this->model->collectGarbageByVariationId($product['variation_id'], $sessionID);
                }
            } else {
                //Remove if product was added by product id
                $result = $this->model->getFileDetailsByProductId($product['product_id'], $sessionID);
                if ($result) {
                    $this->model->collectGarbageByProductId($product['product_id'], $sessionID);
                }
            }

            //If all items from cart are removed
            //clean Garbage of uploaded files and entries
            // As this hook is called after removed item and before remove, so
            //if there is last item which is going to be removed.
            if (WC()->cart->get_cart_contents_count() == 1) {
                $this->model->collectGarbageBySessionId($sessionID);
            }
        }

    }

    //Update status when order is processed
    public function updateOrderStatus($order_id, $old_status, $new_status)
    {
        if (!is_admin()) {

            $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');
            if ($sessionID) {
                if (strcmp($old_status, 'pending') === 0) {

                    //Get all files record
                    $records = $this->model->getFileDetailsBySessionId($sessionID);
                    if ($records) {
                        $dbProductIds = array();
                        $dbVariationIds = array();
                        foreach ($records as $row) {
                            $dbProductIds[] = $row->product_id;
                            if ($row->variation_id != 0) {
                                $dbVariationIds[] = $row->variation_id;
                            }
                        }

                        // Collect cart item details
                        $cartProductIds = array();
                        $cartVariationIds = array();
                        $items = WC()->cart->get_cart();
                        foreach ($items as $key => $item) {
                            $cartProductIds[] = $item['product_id'];
                            if ($item['variation_id'] != 0) {
                                $cartVariationIds[] = $item['variation_id'];
                            }
                        }

                        //Check if db variation or product id
                        // Is not in cart then clean files and db records
                        // Because user uploaded files but did not add to cart that items
                        for ($i = 0; $i < count($dbProductIds); $i++) {
                            if (!in_array($dbProductIds[$i], $cartProductIds)) {
                                // Product was not added clean garbage
                                $this->model->collectGarbageByProductId($dbProductIds[$i], $sessionID);
                            }
                        }

                        for ($j = 0; $j < count($dbVariationIds); $j++) {
                            if (!in_array($dbVariationIds[$j], $cartVariationIds)) {
                                $this->model->collectGarbageByVariationId($dbVariationIds[$j], $sessionID);
                            }
                        }
                        $this->model->updateOrderStatusOnPayment($sessionID, $order_id);
                        WC()->session->set('_woo_file_dropzone_sessionID', false);

                    }
                }
            }

        }
    }

    public function getFilesToLoadInBox($product_id, $variation_id = "0")
    {

        $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');

        if(is_null($sessionID)){
            if (!session_id()) {
                session_start();
            }
            $sessionID = isset($_SESSION['_woo_file_dropzone_sessionID']) ? $_SESSION['_woo_file_dropzone_sessionID'] : 0 ;
        }

        $records = null;
        if ($variation_id !== 0) {
            $records = $this->model->getFileDetailsByVariationId($variation_id, $sessionID);
        } else {
            $records = $this->model->getFileDetailsByProductId($product_id, $sessionID);
        }

        return [
            'extra_fields' => unserialize($records[0]->extra_fields),
            'file_urls' => unserialize($records[0]->file_urls),
            'image_uri' => WOO_FILE_DROPZONE_UPLOAD_CONTENT_URL
        ];

    }


    // While guest adds item into cart, Woocommerce default session do not presist data untill there is data in cart already
    // so default PHP session is used to store files against product, so guest can see their uploaded file
    // when user adds item into cart then remove session id from default PHP session and add into standard woocommerce session
    // Storage
    public function updateSessionWhenAddToCart()
    {

        // get sessionID from default woocommerce session
        $sessionID = WC()->session->get('_woo_file_dropzone_sessionID');

        // if sessionID is null, it means it is not stored in woocommerce default session storage
        // get sessionID from PHP $_SESSION Array.
        if (is_null($sessionID)) {

            // Check if sessionID is stored in php default SESSION
            if (isset($_SESSION['_woo_file_dropzone_sessionID'])) {
                if (!session_id()) {
                    session_start();
                }
                $sessionID = $_SESSION['_woo_file_dropzone_sessionID'];

                // Store sessionID in deafult woocommerce session so all the other logic
                // will work as it is as was working before.
                WC()->session->set('_woo_file_dropzone_sessionID', $sessionID);
                unset($_SESSION['_woo_file_dropzone_sessionID']);
            }

        }
    }

}

