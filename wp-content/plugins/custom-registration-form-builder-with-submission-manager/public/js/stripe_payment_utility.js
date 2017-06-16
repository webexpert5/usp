Stripe.setPublishableKey(rm_stripe_obj.stripe_pub_key);

    function add_input_names() {
        // Not ideal, but jQuery's validate plugin requires fields to have names
        // so we add them at the last possible minute, in case any javascript
        // exceptions have caused other parts of the script to fail.
        jQuery(".card-number").attr("name", "card-number")
        jQuery(".card-cvc").attr("name", "card-cvc")
        jQuery(".card-expiry-year").attr("name", "card-expiry-year")
    }
    function remove_inout_names() {
        jQuery(".card-number").removeAttr("name")
        jQuery(".card-cvc").removeAttr("name")
        jQuery(".card-expiry-year").removeAttr("name")
    }
    function rm_stripe_authorization(form_uid) {
        // remove the input field names for security
        // we do this *before* anything else which might throw an exception
        remove_inout_names(); // THIS IS IMPORTANT!
        // given a valid form, submit the payment details to stripe
       // jQuery(form['submit-button']).attr("disabled", "disabled")
        Stripe.createToken({
            number: jQuery('#rm_stripe_card_number'+form_uid).val(),
            cvc: jQuery('#rm_stripe_card_cvv'+form_uid).val(),
            exp_month: jQuery('#rm_stripe_card_exp_month'+form_uid).val(),
            exp_year: jQuery('#rm_stripe_card_exp_year'+form_uid).val()
        }, function(status, response) {
            if (response.error) {
                // re-enable the submit button
                //jQuery(form['submit-button']).removeAttr("disabled")

                // show the error
                jQuery("#rm_stripe_payment_errors"+form_uid).html(response.error.message);

                // we add these names back in so we can revalidate properly
                add_input_names();
               // e.preventDefault();
               jQuery("#rm_next_form_page_button"+form_uid).prop('disabled',false);
                return false;
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the stripe token
                var input = jQuery("<input name='stripeToken' id='stripeToken' value='" + token + "' style='display:none;' />");
                jQuery("#payment-tk"+form_uid).html(input[0]);
                document.getElementById('form'+form_uid).submit();
                //jQuery(".payment-errors").html("Details accepted");
               return true;
                
            }
        });
        
        jQuery("#rm_next_form_page_button").prop('disabled',false);
        return false;
    }
    // adding the input field names is the last step, in case an earlier step errors
    add_input_names();


jQuery(document).ready(function(){
    var form = jQuery('form[name="rm_form"]');
    form.submit(function(e){ 
        if(jQuery("input[name='rm_payment_method']:checked").val()== 'stripe')
            return rm_stripe_authorization(e);
        else
            return true; 
        
        jQuery("#rm_next_form_page_button").prop('disabled',false);
        return false;
        
    });
    /*submitBtn= jQuery('form[name="rm_form"]').find(":submit");
    jQuery(submitBtn).attr("type","button");
    jQuery(submitBtn).click(function(){
        if(jQuery("input[name='rm_payment_method']:checked").val()== 'stripe')
            rm_stripe_authorization();
        else
            document.rm_form.submit();


    });*/
});
