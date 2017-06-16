jQuery( document ).ready(function($) {

	console.log("TESTING");
		
		function move() {
			alert("hello");
		  var elem = $("#myBar");   
		  var width = 1;
		  var id = setInterval(frame, 10);
		  function frame() {
			if (width >= 100) {
			  clearInterval(id);
			} else {
			  width++; 
			  elem.style.width = width + '%'; 
			}
		  }
		}
		
        /*var $formNotice = $('.form-notice');
        var $imgForm    = $('.image-form');
        var $imgNotice  = $imgForm.find('.image-notice');
        var $imgPreview = $imgForm.find('.image-preview');
        var $imgFile    = $imgForm.find('.image-file');
	console.log($imgFile);
        var $imgId      = $imgForm.find('[name="image_id"]');*/

        if ( $imgForm.length ) {
            $imgFile.on('click', function() {
                $(this).val('');
                $imgId.val('');
            });

            $imgForm.on( 'click', '.btn-change-image', function(e) {
                e.preventDefault();
                $imgNotice.empty().hide();
                $imgFile.val('').show();
                $imgId.val('');
                $imgPreview.empty().hide();
            });

            

            $imgForm.on('submit', function(e) {
                e.preventDefault();

                var data = $(this).serialize();

                $.post( su_config.ajax_url, data, function(resp) {
                    if ( resp.success ) {
                        $formNotice.css('color', 'green');
                        $imgForm[0].reset();
                        $imgNotice.empty().hide();
                        $imgPreview.empty().hide();
                        $imgId.val('');
                        $imgFile.val('').show();
                    } else {
                        $formNotice.css('color', 'red');
                    }

                    $formNotice.html( resp.data.msg );
                });
            });
        }
    
});


	var $formNotice = jQuery('.form-notice');
        var $imgForm    = jQuery('.image-form');
        var $imgNotice  = $imgForm.find('.image-notice');
        var $imgPreview = $imgForm.find('.image-preview');
        var $imgFile    = $imgForm.find('.image-file');
	console.log($imgFile);
        var $imgId      = $imgForm.find('[name="image_id"]');


jQuery('.image-file').on('change', function(e) {
		var clicked = e.target;
                e.preventDefault();		
                var formData = new FormData();

                formData.append('action', 'upload-attachment');
                formData.append('async-upload', clicked.files[0]);
                formData.append('name', clicked.name);
                formData.append('_wpnonce', su_config.nonce);

                jQuery.ajax({
                    url: su_config.upload_url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    xhr: function() {

			
                        var myXhr = jQuery.ajaxSettings.xhr();

                        if ( myXhr.upload ) {
				console.log(myXhr.upload);
			    //move();	
                            myXhr.upload.addEventListener( 'progress', function(e) {
                                if ( e.lengthComputable ) {
                                    var perc = ( e.loaded / e.total ) * 100;
                                    console.log(perc);  
                                    perc = perc.toFixed(2);
                                    //jQuery('.image-notice').html('Uploading&hellip;(' + perc + '%)');
				    jQuery('#myBar').LineProgressbar({
					percentage:perc,
					radius: '0px',
					height: '5px',
					ShowProgressCount: false,
					fillBackgroundColor: '#c50102',
                                        backgroundColor: '#e6e6e6',
                                        duration: 'slow'
					});
                                }
                            }, false );
                        }
						
                        return myXhr;
						
						
                    },
                    type: 'POST',
                    beforeSend: function() {
                        $imgFile.hide();
                        $imgNotice.html('Uploading&hellip;').show();
                    },
                    success: function(resp) {	
                        if ( resp.success ) {
                            console.log(resp);
                            $imgNotice.html('Successfully uploaded. <a href="#" class="btn-change-image">Change?</a>');
				
                            var img = jQuery('<img>', {
                                src: resp.data.url
                            });

                            $imgId.val( resp.data.id );
                            jQuery('.image-preview').html( img ).show();

                        } else {
                            console.log('failed');
                            $imgNotice.html('Fail to upload image. Please try again.');
                            $imgFile.show();
                            $imgId.val('');
                        }
                    }
                });
            });
