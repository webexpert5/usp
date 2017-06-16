<?php
defined('ABSPATH') or die('No script kiddies please!');
?>
<div class="wrap">
    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1):
        ?>
        <div class="updated notice">
            <p>Settings saved</p>
        </div>
        <?php
    endif;
    ?>

    <h3><?php echo _e('Woo File Dropzone', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></h3>
    <p>
        Please read this blog post <a target="_blank" href="https://gmbhurgri.com/woo-file-dropzone-woocommerce-plugin-user-guide/">https://gmbhurgri.com/woo-file-dropzone-woocommerce-plugin-user-guide/</a> for better understanding of how to use
    </p>
    <p>
        For testing to upload file, Please open store front in another browser, if supper admin is logged in front end file upload box will not show to him, due to some session problems, it is now restricted to Admin account.
    </p>
    <p> for the fields section if you don't want to show fields just uncheck that fields checkbox "Show Fields", you can
        add both textarea and text fields and also can require from customer when he/she sent files to you.</p>
    <p>
        You will see a new box in woocommerce order detail page where admin can manage user uploaded files.
    </p>
    <p>
        For any query you can contact us <a target="_new" href="http://gmbhurgri.com">http://gmbhurgri.com</a><br>
        Email: gmbhurgri@gmail.com
    </p>
    <h3><?php echo _e('Shortcodes', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></h3>
    <table class="widefat">
        <tr>
            <td>
                <label for="">Shortcode For Product Detail Page</label>
            </td>
            <td>
                <input
                        type="text"
                        value="[woo_file_dropzone_product_detail_page]"
                        class="wcfu-shortcode-class input-text"
                        readonly="readonly"
                        style="width: 600px"
                />
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Shortcode For Cart Page</label>
            </td>
            <td>
                <input
                        type="text"
                        value="[woo_file_dropzone_cart_page cart_item_key={$cart_item_key}]"
                        class="wcfu-shortcode-class input-text"
                        readonly="readonly"
                        style="width: 600px"
                />
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Shortcode For Checkout Page</label>
            </td>
            <td>
                <input
                        type="text"
                        value="[woo_file_dropzone_checkout_page cart_item_key={$cart_item_key}]"
                        class="wcfu-shortcode-class input-text"
                        readonly="readonly"
                        style="width: 600px"
                />
            </td>
        </tr>
    </table>
    <h3><?php echo _e('Configure in Woocommerce Template (Theme files)', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></h3>
    <p>
        <?php echo _e('Please Note, These shortcodes are intended for pasting in code files not in wordpress editor, Also insert shortcode in one file only not use all shortcodes at the same time.',
            WOO_FILE_DROPZONE_TEXT_DOMAIN); ?>
    </p>
    <table class="widefat">
        <tr>
            <th>
                File (Paste In)
            </th>
            <th>
                Code
            </th>
        </tr>
        <tr>
            <td>
                woocommerce/templates/single-product/add-to-cart/simple.php
            </td>
            <td>
                <code>
                    echo do_shortcode("[woo_file_dropzone_product_detail_page]");
                </code>
            </td>

        </tr>
        <tr>
            <td colspan="2">
                <?php echo _e('Please Put these shortcodes inside cart loop', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?>
            </td>
        </tr>
        <tr>
            <td>
                woocommerce/templates/cart/cart.php
            </td>
            <td>
                <code>
                    echo do_shortcode("[woo_file_dropzone_cart_page cart_item_key={$cart_item_key}]");
                </code>
            </td>

        </tr>
        <tr>
            <td>
                woocommerce/templates/checkout/review-order.php
            </td>
            <td>
                <code>
                    echo do_shortcode("[woo_file_dropzone_checkout_page cart_item_key={$cart_item_key}]");
                </code>
            </td>
        </tr>
    </table>
    <h3><?php echo _e('Configure Options', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></h3>
    <div id="wcuf-form-div" style="margin-bottom: 20px">
        <form method="post" id="woo-file-dropzone-form">
            <table>
                <tr>
                    <td><label
                                for="file-upload-mandatory"><?php _e('File Upload Mandatory', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></label></td>
                    <td>
                        <input
                                type="checkbox"
                            <?php
                            if ($isFileUploadMandatory) {
                                echo "checked='checked'";
                            }
                            ?>
                                name="isFileUploadMandatory"
                                id="file-upload-mandatory"
                        />
                        (User can not add item into cart till he uploads file. Can only work if Used For Product detail page)
                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="upload-button-titile"> <?php _e('Upload Button Title', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></label>
                    </td>
                    <td>

                        <input
                                type="text"
                                value="<?php echo isset($uploadButtonTitle) ? $uploadButtonTitle : 'Upload Files' ?>"
                                name="uploadButtonTitle"
                                id="upload-button-title"
                                data-validation="required"/>

                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="send-button-title"><?php _e('Send Button Title', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></label>
                    </td>
                    <td>
                        <input
                                type="text"
                                value="<?php echo isset($sendButtonTitle) ? $sendButtonTitle : 'Send' ?>"
                                name="sendButtonTitle"
                                id="send-button-title"
                                data-validation="required"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="already-uploaded-msg">
                            <?php _e('Already Uploaded Message', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?>
                        </label>
                    </td>
                    <td>
                        <input
                                type="text"
                                value="<?php echo isset($alreadyUploadedMsg) ? $alreadyUploadedMsg : 'All Set, Your files are received' ?>"
                                name="alreadyUploadedMsg"
                                id="already-uploaded-msg"
                                data-validation="required"
                                style="width: 600px"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="allowed-file-types"
                        ><?php _e('Allowed File Types', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?>
                        </label>
                    </td>
                    <td>
                        <input
                                type="text"
                                value="<?php echo isset($allowedFileTypes) ? $allowedFileTypes : '.jpg,.png,.gif' ?>"
                                name="allowedFileTypes"
                                id="allowed-file-types"
                                data-validation="required"
                                style="width: 600px"
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="file-type-error-msg">
                            <?php _e('File Type Error Message', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?>
                        </label>
                    </td>
                    <td>
                        <input
                                type="text"
                                value="<?php echo isset($fileTypeErrorMsg) ? $fileTypeErrorMsg : 'This File Type is Not Allowed' ?>"
                                name="fileTypeErrorMsg"
                                id="file-type-error-msg"
                                data-validation="required"
                                style="width: 600px"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="max-file-size">
                            <?php _e('Max File size(in MB)', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?>
                        </label>
                    </td>
                    <td>
                        <input
                                type="text"
                                value="<?php echo isset($maxFileSize) ? $maxFileSize : '1' ?>"
                                name="maxFileSize"
                                id="max-file-size"
                                data-validation="number"
                                data-validation="required"
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="allowed-number-of-files">
                            <?php _e('Allowed Number of Files', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?>
                        </label>
                    </td>
                    <td>
                        <input
                                type="text"
                                value="<?php echo isset($allowedNumberOfFiles) ? $allowedNumberOfFiles : '1' ?>"
                                name="allowedNumberOfFiles"
                                id="allowed-number-of-files"
                                data-validation="number"
                                data-validation="required"
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label
                                for="popupover-position"><?php _e('Popover Direction', WOO_FILE_DROPZONE_TEXT_DOMAIN) ?></label>
                    </td>
                    <td>
                        <select name="popupoverPosition"
                                id="popupover-postion"
                                data-validation="required">
                            <option value="">Select</option>
                            <option
                                    value="right" <?php echo (isset($popupoverPosition) && $popupoverPosition == 'right') ? "selected='selected'" : ''; ?>>
                                Right
                            </option>
                            <option
                                    value="left" <?php echo (isset($popupoverPosition) && $popupoverPosition == 'left') ? "selected='selected'" : ''; ?>>
                                Left
                            </option>
                            <option
                                    value="top" <?php echo (isset($popupoverPosition) && $popupoverPosition == 'top') ? "selected='selected'" : ''; ?>>
                                Top
                            </option>
                            <option
                                    value="bottom" <?php echo (isset($popupoverPosition) && $popupoverPosition == 'bottom') ? "selected='selected'" : ''; ?>>
                                Bottom
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="show-">Show on Product Detail Page</label></td>
                    <td>
                        <input
                                type="radio"
                                name="showOn"
                                value="productDetailPage"
                                id="show-on-product-detail-page"
                                data-validation="required"
                            <?php if (isset($showOn) && $showOn == 'productDetailPage') {
                                echo "checked";
                            } ?>
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="show-on-cart-page">Show on Cart Page</label>
                    </td>
                    <td>
                        <input
                                type="radio"
                                name="showOn"
                                id="show-on-cart-page"
                                value="cartPage"
                                data-validation="required"
                            <?php if (isset($showOn) && $showOn == 'cartPage') {
                                echo "checked";
                            } ?>
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="show-on-checkout-page">Show on Checkout Page</label>
                    </td>
                    <td>
                        <input
                                type="radio"
                                name="showOn"
                                value="checkoutPage"
                                data-validation="required"
                                id="show-on-checkout-page"
                            <?php if (isset($showOn) && $showOn == 'checkoutPage') {
                                echo "checked";
                            } ?>
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="use-short-code">Use with Shortcode</label>
                    </td>
                    <td>
                        <input
                                type="radio"
                                name="showOn"
                                value="useShortcode"
                                data-validation="required"
                                id="use-short-code"
                            <?php if (isset($showOn) && $showOn == 'useShortcode') {
                                echo "checked";
                            } ?>
                        />
                    </td>
                </tr>
            </table>

            <table class="widefat" id="wcuf-entries-table">
                <thead>
                <tr>
                    <th>Label</th>
                    <th>Show Field</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($fields): ?>
                    <?php foreach ($fields as $row): ?>
                        <tr>
                            <td><input type="text" name="fieldLabel[]" data-validation="required"
                                       value="<?php echo $row['fieldLabel'] ?>"></td>
                            <td>
                                <input type="hidden"
                                       name="showField[]"
                                       value="<?php echo ($row['showField'] == 'on') ? 'on' : 'off' ?>"/>
                                <input type="checkbox"
                                       value="<?php echo ($row['showField'] == 'on') ? 'on' : 'off' ?>"
                                       class="checkbox-hide-label"
                                    <?php if ($row['showField'] == "on") echo "checked='checked'" ?>/>
                            </td>
                            <td>
                                <select name="fieldType[]" class="input" data-validation="required">
                                    <option value="">Please Select</option>
                                    <option value="text" <?php if ($row['fieldType'] == 'text') {
                                        echo "selected";
                                    } ?>>
                                        Text
                                    </option>
                                    <option
                                            value="textarea" <?php if ($row['fieldType'] == 'textarea') {
                                        echo "selected";
                                    } ?>>
                                        Textarea
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden"
                                       value="<?php echo ($row['isRequired'] == 'on') ? 'on' : 'off'; ?>"
                                       name="isRequired[]"/>
                                <input type="checkbox"
                                       value="<?php echo ($row['isRequired'] == 'on') ? 'on' : 'off'; ?>"
                                       class="input checkbox-is-required" <?php if ($row['isRequired'] == "on") {
                                    echo "checked='checked'";
                                } ?>/>
                            </td>
                            <td><input
                                        type="button"
                                        value="Remove"
                                        class="button wcuf-remove"></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td><input type="text" name="fieldLabel[]" data-validation="required" value="dummy field"></td>
                        <td>
                            <input type="hidden"
                                   name="showField[]"
                                   value="off"/>

                            <input type="checkbox"
                                   value="on"
                                   class="checkbox-hide-label"/>
                        </td>
                        <td>
                            <select name="fieldType[]" class="input" data-validation="required">
                                <option value="">Please Select</option>
                                <option selected="selected" value="text">Text</option>
                                <option value="textarea">Textarea</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden"
                                   name="isRequired[]"
                                   value="off"/>

                            <input type="checkbox"
                                   class="checkbox-is-required"/>
                        </td>
                        <td><input type="button" value="Remove" class="button wcuf-remove"/></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <input class="button button-success button-large" type="button" value="Add+" id="wcuf-add-button">
            <div id="save-button-container" style="margin-top: 20px">
                <input class="button button-primary button-large" type="submit" value="Save" id="save-button">
            </div>
        </form>
    </div>

</div>
<script>
    jQuery(document).ready(function () {
        //remove row function
        jQuery('.wcuf-remove').on('click', function () {
            if (jQuery('#wcuf-entries-table > tbody tr').length > 1) {
                jQuery(this).parents('tr').remove();
            }
        });

        jQuery('#wcuf-add-button').on('click', function () {
            var tr = jQuery('#wcuf-entries-table tr:last').clone(true, true);
            jQuery(tr).appendTo('#wcuf-entries-table > tbody');
        });

        jQuery('.wcfu-shortcode-class').on('click', function () {
            jQuery(this).select();
        });

        jQuery('.checkbox-hide-label, .checkbox-is-required').on('change', function () {
            if (jQuery(this).prop('checked')) {
                jQuery(this).siblings().val('on');
            } else {
                jQuery(this).siblings().val('off');
            }
        });

        // ON save show success message
        var params = [
            "page=woo-file-dropzone-options",
            "success=1"
        ];

        jQuery.validate({
            form: '#woo-file-dropzone-form',
            modules: 'date, security',
            onSuccess: function (e) {
                var $button = jQuery('#save-button');
                $button.attr('disabled', 'disabled');
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        'action': 'wc_file_dropzone_ajax_actions',
                        'do_action': 'save_options',
                        'form_data': jQuery('#woo-file-dropzone-form').serialize(),
                    },
                    success: function (resp) {
                        window.location.href = window.location.protocol + "//" +
                            window.location.host + window.location.pathname + '?' + params.join('&')
                    }
                });
                return false;
            }
        });
    });


</script>