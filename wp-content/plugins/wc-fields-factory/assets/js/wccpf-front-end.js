(function($) {	
	
	var wcff_front_end = function(){
		this.ajaxFlaQ = true;
		this.prepareRequest = function( _request, _method, _data ) {
			this.request = {
				request 	: _method,
				context 	: _request,
				post 		: "",
				post_type 	: "wccpf",
				payload 	: _data,
			};
		};
		
		this.prepareResponse = function( _status, _msg, _data ) {
			this.response = {
				status : _status,
				message : _msg,
				res : _data
			};
		};
		if( typeof wccpf_opt_cart != "undefined" ){
			this.getFieldinfo = function( target, _check ){
				var product 		= target.closest( "tr.cart_item" ).find( ".wcff_get_referance_data" );					
				var prodId 			= product.data( "product_id" );
				var field_name 		= target.data( "field" );
				var product_cart_id = product.data( "cart_id" );					
				var value			= target.find( ".wcff-color-picker-color-show" ).length != 0 ? target.find( ".wcff-color-picker-color-show" ).css( "background-color" ) : $.trim( target.find( "p" ).text() );
				if( target.is( "[data-cloned]" ) ){
					field_name = target.data( "cloned" );
				}
				if( _check ){
					field_name = target.prev().text().toLowerCase().slice(0, -1).replace( " ", "_" ).trim()
				}
				var data 			= { product_id : prodId, product_cart_id : product_cart_id, check_edit : _check, data : { name : field_name, value : value } };
				this.prepareRequest( "wcff_cart_field_render", "post", data );
				this.dock( "update_cart_item", target );
			};
			
			this.getPharamInhtml = function( _target ){
				var target = _target;
				var prodId 			= target.closest( "tr.cart_item" ).find( ".wcff_get_referance_data" ).data( "product_id" );
				var product_cart_id = target.closest( "tr.cart_item" ).find( ".wcff_get_referance_data" ).data( "cart_id" );
				var targetField 	= target.closest( ".wcff_field_cart_update_value" ).find( ".wccpf-field" );
				var value			= targetField.val();
				var wcff_validator_obj = new wcffValidator();
				var field_type = target.attr( "data-field_type" );
				wcff_validator_obj.doValidate( targetField );
				if( !wcff_validator_obj.isValid ){
					return false;
				}		
				if( field_type == "checkbox" || field_type == "radio" ){				
					if( field_type == "checkbox" ) {
						var value_field = target.closest( ".wcff_field_cart_update_value" ).find( ".wccpf-field:checked" );
						if( value_field.length >= 2 ){
							value = "";
							for( var i = 0; i < value_field.length; i++ ){
								value += $( value_field[i] ).val();
								value += i != value_field.length-1 ? ", " : "";
							}
						} else {
							value = value_field.val();
						}
					} else{
						value = _target.closest( ".wcff_field_cart_update_value" ).find( "input:checked" ).val();
					}
				} else if( field_type == "colorpicker" ){
					value = encodeURI( value );
				} else if( field_type == "file" ) {
					
				}  else if( field_type == "email" ){
					value = value;
					var validate = target.closest( ".wcff_field_cart_update_value" ).find( ".wccpf-field" )[0].checkValidity();
					if( !validate ){
						targetField.next().html( "Invalid email.!" ).show();
						return false;
					} else{
						targetField.next().html( "This field can't be Empty" ).hide();
					}
				}
				var data 	= { product_id : prodId, product_cart_id : product_cart_id, data : { name : target.closest( ".wcff_field_cart_update_value" ).data( "field" ), value : value, field_type : field_type, color_showin : target.attr( "data-color_show" ) } };
				this.prepareRequest( "update_cart_item_from_session", "post", data );
				this.dock( "update_cart_item_from_session", target );
			};
		}
		
		this.dock = function( _action, _target, is_file ) {		
			var me = this;
			/* see the ajax handler is free */
			if( !this.ajaxFlaQ ) {
				return;
			}		
			
			$.ajax({  
				type       : "POST",  
				data       : { action : "wcff_ajax", wcff_param : JSON.stringify( this.request ) },  
				dataType   : "json",  
				url        : woocommerce_params.ajax_url,  
				beforeSend : function(){  				
					/* enable the ajax lock - actually it disable the dock */
					me.ajaxFlaQ = false;				
				},  
				success    : function(data) {				
					/* disable the ajax lock */
					me.ajaxFlaQ = true;				
					me.prepareResponse( data.status, data.message, data.data );		               
	
					/* handle the response and route to appropriate target */
					if( me.response.status ) {
						me.responseHandler( _action, _target );
					} else {
						/* alert the user that some thing went wrong */
						//me.responseHandler( _action, _target );
					}				
				},  
				error      : function(jqXHR, textStatus, errorThrown) {                    
					/* disable the ajax lock */
					me.ajaxFlaQ = true;
				},
				complete   : function() {
					
				}   
			});		
		};
		
		this.responseHandler = function( _action, _target ){
			if( _action == "update_cart_item" ){
				if( this.response.res == true && this.request.payload.check_edit ){
					_target.addClass( "wcff_field_cart_update_value" ).attr( { "title" : "Double click to edit", "data-field" : _target.prev().text().toLowerCase().slice(0, -1).replace( " ", "_" ).trim() } );
					return;
				} else if( this.request.payload.check_edit ) {
					_target.addClass( "edit_checked" );
					return;
				}				
				if( this.response.res == false ){
					return;
				}
				var html = $( this.response.res.html ).find( "td:last" ).html();
				var header = $( "head" );
				if( this.response.res.field_type != "file" ){					
					html = $( html + '<button data-field_type="'+this.response.res.field_type+'" data-color_show="'+ this.response.res.color_showin +'" class="button wcff_update_curent_field_data">Update</button>' );
					html.addClass( "wcff_cart_item_update" );
					_target.html( html );
				}				
				if( this.response.res.field_type == "email" || this.response.res.field_type == "text" || this.response.res.field_type == "number" || this.response.res.field_type == "textarea" ){
					_target.find( ".wccpf-field" ).val( this.request.payload.data.value );
					html.trigger( "focus" );
				} else if( this.response.res.field_type == "select" ){					
					//html.val( this.request.payload.data.value.toLowerCase() );
				}  else if( this.response.res.field_type == "colorpicker" ){
					header.append( this.response.res.script );
				} else if( this.response.res.field_type == "checkbox" ){
					var checked_item = this.request.payload.data.value.split( ", " );
					for( var i = 0; i < checked_item.length; i++ ){
						html.find( "input[value="+checked_item[i]+"]" ).prop( "checked", true );
					}
				} else if( this.response.res.field_type == "radio" ){
					html.find( "input[value="+ this.request.payload.data.value +"]" ).prop( "checked", true );
				} else if( this.response.res.field_type == "file" ){
					return;
				} else if( this.response.res.field_type == "datepicker" ){
					_target.find( ".wccpf-field" ).val( this.request.payload.data.value );
					if( header.find( "script[data-type=wpff-datepicker-script]" ).length == 0 ){
						header.append( this.response.res.script );
					}
					header.append( $( this.response.res.html )[2] );
				}
			} else if( _action == "update_cart_item_from_session" ){
				if( this.response.res.status ){
					if( this.response.res.field_type == "colorpicker" ){
						_target.closest( ".wcff_field_cart_update_value" ).html( '<p>'+ decodeURI( this.response.res.value ) +'</p>' );
					} else{
						_target.closest( ".wcff_field_cart_update_value" ).html( '<p>'+ this.response.res.value +'</p>' );
					}
					_target.next().hide();
				} else {
					_target.next().html( this.response.res.message ).show();
				}
			}
		};
		
	};
	
	$(document).on( "submit", "form.cart", function(){				
		if( typeof( wccpf_opt.location ) != "undefined" && 
				wccpf_opt.location != "woocommerce_before_add_to_cart_button" && 
				wccpf_opt.location != "woocommerce_after_add_to_cart_button" ) {			
			var me = $(this);			
			me.find(".wccpf_fields_table").each(function(){
				$(this).remove();	
			});		
			
			$(".wccpf_fields_table").each(function(){
				var cloned = $(this).clone( true );
				cloned.css("display", "none");
				me.append( cloned );	
			});
			
		}
	});
	
	var wcffCloner = function(){
		this.initialize = function(){
			$( document ).on( "change", "input[name=quantity]", function() {
				var product_count = $(this).val();
				var fields_count = parseInt( $("#wccpf_fields_clone_count").val() );
				$("#wccpf_fields_clone_count").val( product_count );
				
				if( fields_count < product_count ) {
					for( var i = fields_count + 1; i <= product_count; i++ ) {
						var cloned = $('.wccpf-fields-group:first').clone( true );
						cloned.find("script").remove();				
						cloned.find("div.sp-replacer").remove();
						cloned.find("span.wccpf-fields-group-title-index").html( i );
						cloned.find( ".hasDatepicker" ).attr( "id", "" );
						cloned.find( ".hasDatepicker" ).removeClass( "hasDatepicker" );						
						cloned.find(".wccpf-field").each(function(){
							var name_attr = $(this).attr("name");					
							if( name_attr.indexOf("[]") != -1 ) {
								var temp_name = name_attr.substring( 0, name_attr.lastIndexOf("_") );							
								name_attr = temp_name + "_" + i + "[]";						
							} else {
								name_attr = name_attr.slice( 0, -1 ) + i;
							}
							$(this).attr( "name", name_attr );
						});
						
						$("#wccpf-fields-container").append( cloned );		
						
						setTimeout( function(){ if( typeof( wccpf_init_color_pickers ) == 'function' ) { wccpf_init_color_pickers(); } }, 500 );
					}					
				} else {					
					$("div.wccpf-fields-group:eq("+ ( product_count - 1 ) +")").nextAll().remove();
				}
				
				if( $(this).val() == 1 ) {
		            $(".wccpf-fields-group-title-index").hide();
		        } else {
		            $(".wccpf-fields-group-title-index").show();
		        }
				
			});			
			/* Trigger to change event - fix for min product quantity */
			setTimeout( function(){ $( "input[name=quantity]" ).trigger("change"); }, 300 );
		};
	};
	
	var wcffValidator = function() {		
		this.isValid = true;		
		this.initialize = function(){
			var me = this;
			if( wccpf_opt.validation_type == "blur" ) {
				$( document ).on( "blur", ".wccpf-field", me, function(e) {				
					e.data.doValidate( $(this) );
				});
			}			
			$( document ).on( "submit", "form.cart", me, function(e) {
				var me = e.data; 
				e.data.isValid = true;
				$( ".wccpf-field" ).each(function(){
					me.doValidate( $(this) );
				});				
				return e.data.isValid;
			});
		};
		
		this.doValidate = function( field ) {
			if( field.attr("wccpf-type") != "radio" && field.attr("wccpf-type") != "checkbox" ) {				
				if( field.attr("wccpf-mandatory") == "yes" ) {					
					if( this.doPatterns( field.attr("wccpf-pattern"), field.val() ) ) {						
						field.next().hide();
					} else {						
						this.isValid = false;
						field.next().show();
					}
				}
			} else if( field.attr("wccpf-type") == "radio" ) {
				if( field.attr("wccpf-mandatory") == "yes" ) {	
					if( $("input[name="+ field.attr("name") +"]").is(':checked') ) {
						field.next().show();
					} else {
						this.isValid = false;
						field.next().hide();
					}	 
				}
			} else if( field.attr("wccpf-type") == "checkbox" ) {
				if( field.attr("wccpf-mandatory") == "yes" ) {	
					var values = $("input[name="+ field.attr("name") +"]").serializeArray();
					if( values.length == 0 ) {
						field.next().show();
					} else {
						this.isValid = false;
						field.next().hide();
					}
				}
			} else if( field.attr("wccpf-type") == "file" ) {
				if( field.attr("wccpf-mandatory") == "yes" ) {	
					if( field.val() == "" ) {
						field.next().show();
					} else {
						field.next().hide();
					}						
				}
			}
		}
		
		this.doPatterns = function( patt, val ){
			var pattern = {
				mandatory	: /\S/, 
				number		: /^\d+\.\d{0,2}$/,
				email		: /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,	      	
			};			    
		    return pattern[ patt ].test(val);	
		};
		
	};
	
	$(document).ready(function(){
		var wcff_fe_dock = new wcff_front_end();
		$(document).on( "change", ".wccpf-field", function( e ){
			var target = $( this ),
				prevExt = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];

			if( target.is( "input[type=file]" ) ){
				 if ( $.inArray( target.val().split('.').pop().toLowerCase(), prevExt ) != -1 ) {
			          if( !target.next().is( ".wcff_image_prev_shop_continer" ) ){
			        	   target.after( '<div class="wcff_image_prev_shop_continer"></div>' );
			           }		          
		        	   var html = "";
		        	   for( var i = 0; i < target[0].files.length; i++ ){
		        		   html += '<img class="wcff-prev-shop-image" src="'+ URL.createObjectURL( target[0].files[i] ) +'">';
		        		   target[0].files[i].name = target[0].files[i].name.replace(/'|$|,/g, '');
		        		   target[0].files[i].name = target[0].files[i].name.replace('$', '');
		        	   }
		        	   target.next( ".wcff_image_prev_shop_continer" ).html( html );			           
			     }
			}
		});		
		
		if( typeof wccpf_opt != "undefined" ){
			if( typeof( wccpf_opt.cloning ) !== "undefined" && wccpf_opt.cloning == "yes" ) {
				var wcff_cloner_obj = new wcffCloner();
				wcff_cloner_obj.initialize();
			}
			if( typeof( wccpf_opt.validation ) !== "undefined" && wccpf_opt.validation == "yes" ) {			
				var wcff_validator_obj = new wcffValidator();
				wcff_validator_obj.initialize();
			}
		}
		if( typeof wccpf_opt_cart == "undefined" ){
			$( ".wcff_field_cart_update_value" ).removeClass( "wcff_field_cart_update_value" );
		}
		if( typeof wccpf_opt_cart != "undefined" ){
			if( wccpf_opt_cart.is_edit_cart_value == "no" ){
				$( ".wcff_field_cart_update_value" ).removeClass( "wcff_field_cart_update_value" );
			}
			//wccpf color picker value change html
			$( document ).on( "change", ".wccpf-color, .wccaf-color", function(){
				var colorField = $( this );
				if( colorField.val().search( "," ) == -1 && colorField.attr( "hex_color_show_in_color" ) == "yes" ){
					colorField.val( '<span class="wcff-color-picker-color-show" code="'+ colorField.val() +'" style="padding: 0px 15px;background-color: '+ colorField.val() +'"></span>' );
				}
			});
			$(document).on( "dblclick", "dd[class*=variation], li.wcff_field_cart_update_value", function(e){		
				if( wccpf_opt_cart.is_edit_cart_value  == "yes" && wc_add_to_cart_params.is_cart == "1"  ){
					var target = $( this );					
					if(  !target.find( "input, select, textarea, label" ).length != 0 && target.is( ".wcff_field_cart_update_value" ) ){					
						wcff_fe_dock.getFieldinfo( target, false );
					}
				}
			});
			$(document).on( "hover", "dd[class*=variation]", function(e){
				var target = $( this );	
				if( !target.is( ".wcff_field_cart_update_value" ) && wccpf_opt_cart.is_edit_cart_value == "yes" && !target.is( ".edit_checked" ) ) {
					wcff_fe_dock.getFieldinfo( target, true );				
				}
			});			
			$(document).on( "click", ".wcff_update_curent_field_data", function( e ){
				wcff_fe_dock.getPharamInhtml( $( this ) );
				e.preventDefault();
			});
		}
		
	});
	
})(jQuery);