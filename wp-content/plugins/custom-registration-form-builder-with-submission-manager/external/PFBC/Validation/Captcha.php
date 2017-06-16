<?php
class Validation_Captcha extends Validation {
	protected $message = '';
	protected $privateKey;

	public function __construct($privateKey, $message = "") {
		$this->privateKey = $privateKey;
		//if(!empty($message))
		$this->message = RM_UI_Strings::get('ERROR_INVALID_RECAPTCHA');
	}

	public function isValid($value) {
		require_once(dirname(__FILE__) . "/../Resources/recaptchalib.php");

		$resp = recaptcha_check_answer ($this->privateKey, $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]);
		if($resp->is_valid)
			return true;
		else	
			return false;
	}
}
