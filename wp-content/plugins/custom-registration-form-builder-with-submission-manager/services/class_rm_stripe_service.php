<?php

class RM_Stripe_Service implements RM_Gateway_Service
{
    private $paypal;
    private $options;
    private $currency;

    function __construct() {
        $this->options= new RM_Options();
        $this->currency = $this->options->get_value_of('currency');
    }



    function setOptions($options) {
        $this->options = $options;
    }

    public function cancel() {

    }

    public function charge($data,$pricing_details) {
        $curr_date = RM_Utilities::get_current_time();
        $stripe_api_key = $this->options->get_value_of('stripe_api_key');
        
        if($stripe_api_key == null)
            return false;
        
        if($pricing_details->total_price <=0.0)
            return true;            //Zero amount case.
        
        $global_options= new RM_Options();
        // Get the credit card details submitted by the form
        $error = '';
        $success = '';
        // Create the charge on Stripe's servers - this will charge the user's card
        $items= array();
        foreach($pricing_details->billing as $detail){
            $items[]= $detail->label." x ".$detail->qty;
        }
        $response='';
        $items_str= implode(', ',$items);
        try{
        	\Stripe\Stripe::setApiKey($stripe_api_key); //sk_test_GsT4d690JZzbFk48w0GhsrIX
        	$charge = \Stripe\Charge::create(
            array(
                "amount" => $pricing_details->total_price*100, // amount in cents
                "currency" => strtolower($this->currency),
                "source" => $data->stripeToken,
                "description" => $items_str
            ));
	        $response= $charge->getLastResponse();
	        $response_body= json_decode($response->body);
        }
        
        catch (Stripe_InvalidRequestError $e) {
		  return false;
		} 
		catch(\Stripe\Error\Card $e){
			//echo 'test'; die;
			$log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $data->submission_id,
                'form_id' => $data->form_id,
                'txn_id' => '',
                'status' => 'Card Declined',
                'total_amount' => $pricing_details->total_price,
                'currency' => $this->currency,
                'posted_date' => $curr_date), array('%d', '%d', '%s', '%s', '%f', '%s', '%s'));
            return false;
		}

        if($response->code=="200"){
            $log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $data->submission_id,
                'form_id' => $data->form_id,
                'txn_id' => $response_body->balance_transaction,
                'status' => $response_body->status,
                'total_amount' => $pricing_details->total_price,
                'currency' => $this->currency,
                'posted_date' => $curr_date), array('%d', '%d', '%s', '%s', '%f', '%s', '%s'));
            return true;
        }


        return false;


    }

    public function refund() {
        
    }

    public function subscribe() {
        
    }

}

