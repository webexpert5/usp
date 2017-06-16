<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$data [] = 
$curr_arr = array('USD' => 'US Dollars',
    'EUR' => 'Euros',
    'GBP' => 'Pounds Sterling',
    'AUD' => 'Australian Dollars',
    'BRL' => 'Brazilian Real',
    'CAD' => 'Canadian Dollars',
    'CZK' => 'Czech Koruna',
    'DKK' => 'Danish Krone',
    'HKD' => 'Hong Kong Dollar',
    'HUF' => 'Hungarian Forint',
    'ILS' => 'Israeli Shekel',
    'JPY' => 'Japanese Yen',
    'MYR' => 'Malaysian Ringgits',
    'MXN' => 'Mexican Peso',
    'NZD' => 'New Zealand Dollar',
    'NOK' => 'Norwegian Krone',
    'PHP' => 'Philippine Pesos',
    'PLN' => 'Polish Zloty',
    'SGD' => 'Singapore Dollar',
    'SEK' => 'Swedish Krona',
    'CHF' => 'Swiss Franc',
    'TWD' => 'Taiwan New Dollars',
    'THB' => 'Thai Baht',
    'INR' => 'Indian Rupee',
    'TRY' => 'Turkish Lira',
    'RIAL' => 'Iranian Rial',
    'RUB' => 'Russian Rubles');

$options_s_api = array("id" => "rm_s_api_key_tb", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_STRP_API_KEY'), "disabled" => true);
$options_s_pub = array("id" => "rm_s_publish_key_tb", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_STRP_PUBLISH_KEY') . RM_UI_Strings::get('MSG_BUY_PRO_INLINE'), "disabled" => true);
$options_pp_test_cb = array("id" => "rm_pp_test_cb", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_TESTMODE'));
$options_pp_email = array("id" => "rm_pp_email_tb", "value" => $data['paypal_email'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_PP_EMAIL'));
$options_pp_pstyle = array("id" => "rm_pp_style_tb", "value" => $data['paypal_page_style'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_PP_PAGESTYLE'));

if($data['paypal_test_mode'] == 'yes')
    $options_pp_test_cb['value'] = 'yes';
    
?>

<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
//PFBC form
        $form = new RM_PFBC_Form("options_payment");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_PAYMENT') . '</div>'));
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_PAYMENT_PROCESSOR'), "payment_gateway", array("paypal" => "<img src='" . RM_IMG_URL . "/paypal-logo.png" . "'></img>", "stripe" => "<img src='" . RM_IMG_URL . "/stripe-logo.png" . "'></img>"), array("value" => $data['payment_gateway'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_PROCESSOR'))));
        
        $form->addElement(new Element_HTML('<div class="childfieldsrow rmchildstripe">'));
        $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_STRIPE_API_KEY'), "", $options_s_api));
        $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_STRIPE_PUBLISH_KEY'), "", $options_s_pub));

        $form->addElement(new Element_HTML('</div><div class="childfieldsrow rmchildpaypal">'));
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_TEST_MODE'), "paypal_test_mode", array("yes" => ''), $options_pp_test_cb));
        $form->addElement(new Element_Email(RM_UI_Strings::get('LABEL_PAYPAL_EMAIL'), "paypal_email", $options_pp_email));
        $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_PAYPAL_STYLE'), "paypal_page_style", $options_pp_pstyle));
        $form->addElement(new Element_HTML('</div>'));


        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_CURRENCY'), "currency", $curr_arr, array("value" => $data['currency'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_PYMNT_CURRENCY'))));
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_CURRENCY_SYMBOL'), "currency_symbol_position", array("before" => "Before amount (Eg.: $10)", "after" => "After amount (Eg.: 10$)"), array("value" => $data['currency_symbol_position'], "longDesc" => RM_UI_Strings::get("LABEL_CURRENCY_SYMBOL_HELP"))));

        $form->addElement(new Element_HTMLL('&#8592; &nbsp; Cancel', '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));

        $form->render();
        ?>

    </div>
    <?php 
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    ?>
</div>
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#options_payment-element-1-0').click(function () {
            checkbox_disable_elements(this, 'rm_pp_test_cb-0,rm_pp_email_tb,rm_pp_style_tb', 0);
        });
        jQuery('#options_payment-element-1-1').attr("disabled", true);
    });
</script></pre>

<?php   
