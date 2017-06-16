<?php
defined('ABSPATH') or die('No script kiddies please!');
?>
<div class="bootstrap-iso">
    <div class="popover-markup<?php echo $product_id ?>">
        <button type="button"
            <?php
            $_product = wc_get_product($product_id);
            if ($_product->is_type('variable')) {
                echo "disabled='disabled'";
            }
            ?> class="trigger btn btn-default"><?php echo $uploadButtonTitle ?></button>
        <?php
        if ($isFileAlreadyUploadedForThisProduct):
            if (count($variation_ids)):
                for ($i = 0; $i < count($variation_ids); $i++):
                    ?>
                    <input type='hidden'
                           class='woo_file_dropzone_file_sent<?php echo $product_id ?>'
                           value="<?php echo $variation_ids[$i] ?>"
                           name="woo_file_dropzone_file_sent<?php echo $product_id ?>[]"/>
                    <?php
                endfor;
            else:
                ?>
                <input type='hidden'
                       class='woo_file_dropzone_file_sent<?php echo $product_id ?>'
                       value="0"
                       name="woo_file_dropzone_file_sent<?php echo $product_id ?>"/>
                <?php
            endif;
        endif;
        ?>
        <div class="head hide">
            <button type="button"
                    class="btn btn-default btn-md pull-right trigger<?php echo $product_id; ?>"
                    disabled="disabled">
                <?php echo $sendButtonTitle ?>
            </button>

        </div>
        <div class="content hide">
            <div name="wooFileDropzoneForm<?php echo $product_id ?>"
                 id="wooFileDropzoneForm<?php echo $product_id ?>"
                 class="dropzone"
                 style="
                         height: <?php echo (strcmp($popupoverPosition, 'top') === 0) ? '200px' : '250px' ?>;
                         width: <?php echo (strcmp($popupoverPosition, 'top') === 0) ? '250px' : '250px' ?>;
                         overflow-y: auto;
                         overflow-x: hidden;
                         text-align: center;">
                <p class="already-uploaded-msg" style="display:none">
                    <?php echo $alreadyUploadedMsg ?>
                </p>
                <div class="form-group fields-div">
                    <?php wp_nonce_field('woo_file_dropzone_upload', 'woo_file_dropzone_upload_nonce') ?>
                    <?php
                    $html = "";
                    foreach ($fields as $row) {
                        $fieldId = $fieldName = $row['fieldName'];
                        $fieldLabel = $row['fieldLabel'];
                        $required = ($row['isRequired'] == 'on') ? 'required' : false;
                        if (strcmp($row['showField'], "on") === 0) {
                            if ($row['fieldType'] == "text") {
                                $html .= "<input type='text' placeholder='{$fieldLabel}' name='{$fieldName}' id='{$fieldId}' class='form-control wcfu-textfield' data-validation='{$required}' />";
                            } elseif ($row['fieldType'] == 'textarea') {
                                $html .= "<textarea placeholder='{$fieldLabel}' name='{$fieldName}' id='{$fieldId}' class='form-control wcfu-textarea' data-validation='{$required}'></textarea>";
                            } elseif ($row['fieldType'] == 'checkbox') {
                                $html .= "<input type='checkbox' name='{$fieldName}' class='form-control wcfu-checkbox' id='{$fieldId}' data-validation='{$required}' />";
                            }
                        }
                    }
                    echo $html;
                    ?>
                </div>
                <div class="form-group">
                    <input type="file" name="file" class="hide">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var isFileUploadMandatory = "<?php echo $isFileUploadMandatory ?>";
    var isFileAlreadyUploadedForThisProduct = "<?php echo $isFileAlreadyUploadedForThisProduct ?>";
    var productID = "<?php echo $product_id ?>";
    var wooDropFilesAjaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
    var popupoverPostion = "<?php echo $popupoverPosition ?>";
    var maxFileSize = <?php echo $maxFileSize ?>;
    var allowedFileTypes = "<?php echo $allowedFileTypes ?>";
    var allowedNumberOfFiles = <?php echo $allowedNumberOfFiles ?>;
    var messageAllowedNumberOfFiles = "Drop files here and press Send. You can upload " + allowedNumberOfFiles + " file(s)";
    var sendDataObject = {
        'action': 'woo_file_dropzone_user_ajax_actions',
        'do_action': 'uploadFile',
        'woo_file_dropzone_upload_nonce': jQuery('input[name="woo_file_dropzone_upload_nonce"]').val()
    };

    jQuery(document).ready(function ($) {
        Dropzone.autoDiscover = false;
        if (isFileUploadMandatory == "1") {
            if (isFileAlreadyUploadedForThisProduct !== "1") {
                $('.single_add_to_cart_button')
                    .attr('disabled', true)
                    .attr('title', 'Please upload file before adding to cart');
            }

            $(".variations_form").on("woocommerce_variation_has_changed", function (e) {

                if ($('input[name="variation_id"][type="hidden"]').length) {
                    $('.trigger').attr('disabled', 'disabled');
                    if ($('input[name="variation_id"][type="hidden"]').val() !== '') {
                        $('.trigger').removeAttr('disabled');
                    }
                    if ($('.woo_file_dropzone_file_sent' + productID).length) {
                        $('.single_add_to_cart_button')
                            .attr('disabled', true)
                            .attr('title', 'Please upload file before adding to cart');
                        $('.woo_file_dropzone_file_sent' + productID)
                            .each(function (i, v) {
                                if ($(this).val() == $('input[name="variation_id"][type="hidden"]').val()) {
                                    $('.single_add_to_cart_button').removeAttr('disabled');
                                    return false;
                                }
                            });
                    } else {
                        $('.single_add_to_cart_button')
                            .attr('disabled', true)
                            .attr('title', 'Please upload file before adding to cart');
                    }
                }
            });
        }

        $(".popover-markup" + productID + " >.trigger")
            .popover(
                {
                    html: true,
                    title: function () {
                        return $(this).parent().find('.head').html();
                    },
                    content: function () {
                        return $(this).parent().find('.content').html();
                    },
                    placement: popupoverPostion
                }
            )
            .on('shown.bs.popover', function () {
                $('.fields-div').show();
                $(".trigger" + productID).removeClass('hide');
                $('#wooFileDropzoneForm' + productID).dropzone({
                    url: wooDropFilesAjaxUrl,
                    maxFilesize: maxFileSize,
                    addRemoveLinks: true,
                    params: sendDataObject,
                    acceptedFiles: allowedFileTypes,
                    maxFiles: allowedNumberOfFiles,
                    autoProcessQueue: false,
                    parallelUploads: allowedNumberOfFiles,
                    uploadMultiple: true,
                    dictDefaultMessage: messageAllowedNumberOfFiles,
                    init: function () {
                        var self = this;
                        var variation_id = null;
                        if ($('input[name="variation_id"][type="hidden"]').length) {
                            $('.woo_file_dropzone_file_sent' + productID).each(function () {
                                if ($(this).val() == $('input[name="variation_id"][type="hidden"]').val()) {
                                    $('.dz-message').text('Loading...');
                                    $.ajax({
                                        url: wooDropFilesAjaxUrl,
                                        type: 'POST',
                                        dataType: 'JSON',
                                        data: {
                                            'action': 'woo_file_dropzone_user_ajax_actions',
                                            'do_action': 'getUploadedFiles',
                                            'woo_file_dropzone_upload_nonce': jQuery('input[name="woo_file_dropzone_upload_nonce"]').val(),
                                            'product_id': productID,
                                            'variation_id': jQuery('input[name="variation_id"][type="hidden"]').val(),
                                        },
                                        success: function (resp) {
                                            $.map(resp.file_urls, function (uri) {
                                                var mockFile = {
                                                    name: uri,
                                                    size: 12345,
                                                    type: 'image/png'
                                                };
                                                self.addFile.call(self, mockFile);
                                                self.options.thumbnail.call(self, mockFile, resp.image_uri + uri);
                                                mockFile.previewElement.classList.add('dz-success');
                                                mockFile.previewElement.classList.add('dz-complete');
                                            });
                                        }
                                    });
                                    return false;
                                }
                                //Set the message to upload files
                                else {
                                    $('.dz-message').text(messageAllowedNumberOfFiles).show();
                                }

                            });
                        }
                        else if ($('.woo_file_dropzone_file_sent' + productID).length) {
                            $('.dz-message').text('Loading...');
                            $.ajax({
                                url: wooDropFilesAjaxUrl,
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    'action': 'woo_file_dropzone_user_ajax_actions',
                                    'do_action': 'getUploadedFiles',
                                    'woo_file_dropzone_upload_nonce': jQuery('input[name="woo_file_dropzone_upload_nonce"]').val(),
                                    'product_id': productID,
                                    'variation_id': jQuery('input[name="variation_id"][type="hidden"]').val(),
                                },
                                success: function (resp) {
                                    $.map(resp.file_urls, function (uri) {
                                        var mockFile = {
                                            name: uri,
                                            size: 12345,
                                            type: 'image/png'
                                        };
                                        self.options.addedfile.call(self, mockFile);
                                        self.options.thumbnail.call(self, mockFile, resp.image_uri + uri);
                                        mockFile.previewElement.classList.add('dz-success');
                                        mockFile.previewElement.classList.add('dz-complete');
                                    });
                                }
                            });

                        }
                        this.on('removedfile', function (file) {
                            $('.trigger' + productID).attr('disabled', 'disabled');
                            if ($(".woo_file_dropzone_file_sent" + productID).length) {
                                var deleteFileRequest = {
                                    'action': 'woo_file_dropzone_user_ajax_actions',
                                    'do_action': 'deleteUploadedFile',
                                    'woo_file_dropzone_upload_nonce': jQuery('input[name="woo_file_dropzone_upload_nonce"]').val(),
                                    'product_id': productID,
                                    'variation_id': "0",
                                    'fileName': file.name
                                };
                                // variation available then get value of variation and send
                                var varitionIdValue = jQuery('input[name="variation_id"][type="hidden"]').val();
                                if ( typeof  varitionIdValue !== 'undefined') {
                                    deleteFileRequest.variation_id = varitionIdValue;
                                }
                                $.ajax({
                                    url: wooDropFilesAjaxUrl,
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: deleteFileRequest,
                                    success: function (resp) {
                                        if (deleteFileRequest.variation_id !== '0') {
                                            $(".woo_file_dropzone_file_sent" + productID + "[value='" + deleteFileRequest.variation_id + "']").remove();
                                        } else {
                                            $(".woo_file_dropzone_file_sent" + productID).remove();
                                        }

                                        if (self.files.length) {
                                            $('.trigger' + productID).removeAttr('disabled');
                                        }
                                    }
                                });
                            }
                            //if there last file remove then add message to add more files
                            if (!this.files.length) {
                                $('.dz-message').text(messageAllowedNumberOfFiles).show();
                            }
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                        });

                        this.on("addedfile", function (file) {
                            $('.trigger' + productID).removeAttr('disabled');
                        });

                        this.on("maxfilesexceeded", function (file) {
                            this.removeFile(file);
                        });

                        if ($('input[name="variation_id"][type="hidden"]').val() != '') {
                            variation_id = $('input[name="variation_id"][type="hidden"]').val();
                        }
                        sendDataObject.variation_id = variation_id;
                        sendDataObject.product_id = productID;
                        $(document).on('click', ".trigger" + productID, function () {
                            var _innerFieldsReturn = false;
                            // Textfield if required then empty check
                            if ($('input[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('input[id^="woo_file_dropzone_field_"]:visible').each(function (i, v) {
                                    if ($(this).attr('data-validation') == 'required') {
                                        if ($(this).val() == '') {
                                            $(this).focus();
                                            _innerFieldsReturn = true;
                                            return true;
                                        }
                                    }
                                });
                            }
                            // Textarea if required then empty check
                            if ($('textarea[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('textarea[id^="woo_file_dropzone_field_"]:visible').each(function (i, v) {
                                    if ($(this).attr('data-validation') == 'required') {
                                        if ($.trim($(this).val()) == '') {
                                            $(this).focus();
                                            _innerFieldsReturn = true;
                                            return true;
                                        }
                                    }
                                });
                            }

                            if (_innerFieldsReturn) {
                                return true;
                            }
                            $(this).attr('disabled', 'disabled');
                            var fields = new Object();
                            //Get field values
                            // Textfield
                            if ($('input[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('input[id^="woo_file_dropzone_field_"]:visible').each(function (i, v) {
                                    fields[$(this).attr('name')] = $(this).val();
                                });
                            }
                            // Textarea
                            if ($('textarea[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('textarea[id^="woo_file_dropzone_field_"]:visible').each(function (i, v) {
                                    fields[$(this).attr('name')] = $(this).val();
                                });
                            }
                            sendDataObject.fields = JSON.stringify(fields);
                            self.processQueue();
                            var isServerErrorOccurred = false;
                            self.on("success", function (file, response) {
                                if (response.success === false) { // failed
                                    isServerErrorOccurred = true;
                                    var node, _i, _len, _ref, _results;
                                    var message = '';
                                    $(response.errors).each(function (i, errMsg) {
                                        message += errMsg;
                                    });
                                    file.previewElement.classList.add("dz-error");
                                    _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                                    _results = [];
                                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                                        node = _ref[_i];
                                        _results.push(node.textContent = message);
                                    }
                                    return _results;
                                }
                            });
                            self.on("completemultiple", function () {
                                if (isServerErrorOccurred) {
                                    return true;
                                }
                                $('.single_add_to_cart_button').removeAttr('disabled');
                                $("<input type='hidden' class='woo_file_dropzone_file_sent" + productID + "' value='" + variation_id + "'/>")
                                    .insertBefore(".trigger");
                                $(".trigger" + productID).addClass('hide');
                                $(".popover-markup" + productID + " >.trigger").popover('hide');
                            })
                        })
                    }
                });
            });
    });
</script>