<?php
// comments made to make changes
defined('ABSPATH') or die('No script kiddies please!');
?>
<div class="bootstrap-iso">
    <div class="popover-markup<?php echo $product_id . $variation_id ?>">
        <button type="button"
                class="trigger btn btn-default">
            <?php echo $uploadButtonTitle ?>
        </button>
        <?php
        if ($isFileAlreadyUploadedForThisProduct):
            ?>
            <input type='hidden'
                   id='woo_file_dropzone_file_sent<?php echo $product_id . $variation_id ?>'
                   value="<?php echo $variation_id ?>"
                   name="woo_file_dropzone_file_sent<?php echo $product_id . $variation_id ?>"/>
            <?php
        endif;
        ?>
        <div class="head hide">
            <button type="button"
                    class="btn btn-default btn-md pull-right"
                    id="trigger<?php echo $product_id . $variation_id; ?>"
                    disabled="disabled">
                <?php echo $sendButtonTitle ?>
            </button>
        </div>
        <div class="content hide">
            <div name="wooFileDropzoneForm<?php echo $product_id . $variation_id ?>"
                 id="wooFileDropzoneForm<?php echo $product_id . $variation_id ?>"
                 class="dropzone"
                 style="
                 height: 200px;
                 width:245px;
                 overflow-y: auto;
                 overflow-x: hidden">
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
<script>
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
        $(".popover-markup<?php echo $product_id . $variation_id ?> >.trigger")
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
                $('#wooFileDropzoneForm<?php echo $product_id . $variation_id ?>').dropzone({
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
                        //Load files in box from server
                        if ($('#woo_file_dropzone_file_sent<?php echo $product_id . $variation_id ?>').length) {
                            $('.dz-message').text('Loading...');
                            $.ajax({
                                url: wooDropFilesAjaxUrl,
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    'action': 'woo_file_dropzone_user_ajax_actions',
                                    'do_action': 'getUploadedFiles',
                                    'woo_file_dropzone_upload_nonce': jQuery('input[name="woo_file_dropzone_upload_nonce"]').val(),
                                    'product_id': '<?php echo $product_id ?>',
                                    'variation_id': '<?php echo $variation_id ?>',
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
                                    $('.dz-message').text(messageAllowedNumberOfFiles);
                                }
                            });
                        }

                        this.on('removedfile', function (file) {
                            $("#trigger<?php echo $product_id . $variation_id ?>").attr('disabled', 'disabled');
                            if ($('#woo_file_dropzone_file_sent<?php echo $product_id . $variation_id ?>').length) {
                                $.ajax({
                                    url: wooDropFilesAjaxUrl,
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {
                                        'action': 'woo_file_dropzone_user_ajax_actions',
                                        'do_action': 'deleteUploadedFile',
                                        'woo_file_dropzone_upload_nonce': jQuery('input[name="woo_file_dropzone_upload_nonce"]').val(),
                                        'product_id': '<?php echo $product_id ?>',
                                        'variation_id': '<?php echo $variation_id ?>',
                                        'fileName': file.name
                                    },
                                    success: function (resp) {
                                        if (self.files.length) {
                                            $("#trigger<?php echo $product_id . $variation_id ?>").removeAttr('disabled');
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
                            $("#trigger<?php echo $product_id . $variation_id ?>").removeAttr('disabled');
                        });

                        this.on("maxfilesexceeded", function (file) {
                            this.removeFile(file);
                        });

                        var variationID = "<?php echo $variation_id ?>";
                        if ($('#woo_file_dropzone_file_sent<?php echo $product_id . $variation_id ?>').length) {
                            variationID = $('#woo_file_dropzone_file_sent<?php echo $product_id . $variation_id ?>').val();
                        }
                        sendDataObject.variation_id = variationID;
                        sendDataObject.product_id = '<?php echo $product_id ?>';
                        $(document).on('click', "#trigger<?php echo $product_id . $variation_id ?>", function () {
                            var _innerFieldsReturn = false;
                            // Textfield if rquired then empty check
                            if ($('#wooFileDropzoneForm<?php echo $product_id . $variation_id ?> input[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('#wooFileDropzoneForm<?php echo $product_id . $variation_id ?> input[id^="woo_file_dropzone_field_"]:visible')
                                    .each(function () {
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
                            if ($('#wooFileDropzoneForm<?php echo $product_id . $variation_id ?> textarea[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('#wooFileDropzoneForm<?php echo $product_id . $variation_id ?> textarea[id^="woo_file_dropzone_field_"]:visible')
                                    .each(function () {
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

                            var fields = new Object();
                            //Get field values
                            // Textfield
                            if ($('#wooFileDropzoneForm<?php echo $product_id . $variation_id ?> input[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('input[id^="woo_file_dropzone_field_"]:visible')
                                    .each(function () {
                                        fields[$(this).attr('name')] = $(this).val();
                                    });
                            }
                            // Textarea
                            if ($('#wooFileDropzoneForm<?php echo $product_id . $variation_id ?> textarea[id^="woo_file_dropzone_field_"]:visible').length) {
                                $('textarea[id^="woo_file_dropzone_field_"]:visible')
                                    .each(function () {
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
                                $("<input type='hidden' id='woo_file_dropzone_file_sent<?php echo $product_id ?>" + variationID + "' value='" + variationID + "'/>")
                                    .insertBefore(".trigger");
                                $(".popover-markup<?php echo $product_id . $variation_id ?> >.trigger").popover('hide');
                                $(".box-heading-msg-<?php echo $product_id . $variation_id ?>").removeClass('hide');
                            })
                        })
                    }
                });
            });
    });


</script>