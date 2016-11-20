<?php
namespace common\models;

class CognitiveInterface {
	private $subscription_key;
	private $image_url;
	private $cognition_response;
	
	public function __construct($subscription_key, $image_url) {
		$this->subscription_key = $subscription_key;
		$this->image_url = $image_url;
		establish_cognition();
	}
	
	private function establish_cognition() {
		require_once 'HTTP/Request2.php';
		
		$request = new Http_Request2('https://api.projectoxford.ai/emotion/v1.0/recognize');
		$url = $request->getUrl();
		
		$headers = array(
			// Request headers
			'Content-Type' => 'application/json',
			'Ocp-Apim-Subscription-Key' => '{' . get_subscription_key() . '}',
		);
		
		$request->setHeader($headers);
		
		$parameters = array(
			// Request parameters
		);
		
		$url->setQueryVariables($parameters);
		
		$request->setMethod(HTTP_Request2::METHOD_POST);
		
		// Request body
		$request->setBody("{" . $this->image_url . "}");
		
		try {
			$this->cognition_response = json_decoe($request->send());
		}
		catch (HttpException $ex) {
			echo $ex;
		}
	}
	
	public function get_subscription_key() {
		return $this->subscription_key;
	}
	
	public function get_image_url() {
		return $this->image_url;
	}
	
	public function get_percentile_score() {
		
		if(is_null($this->cognition_response)) {
			echo "Cognition has not yet been established. ";
			return -1;
		}
		
	}
}
?>