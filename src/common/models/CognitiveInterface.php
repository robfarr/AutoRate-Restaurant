<?php
namespace common\models;

class CognitiveInterface {
	private $subscription_key;
	private $image_url;
	private $cognition_response;
	
	public function __construct($subscription_key, $image_url) {
		$this->subscription_key = $subscription_key;
		$this->image_url = $image_url;
		$this->establish_cognition();
	}
	
	private function establish_cognition() {
		require_once 'HTTP/Request2.php';
		
		$request = new Http_Request2('https://api.projectoxford.ai/emotion/v1.0/recognize');
		$url = $request->getUrl();
		
		$headers = array(
			// Request headers
			'Content-Type' => 'application/json',
			'Ocp-Apim-Subscription-Key' => '{' . $this->get_subscription_key() . '}',
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
			$this->cognition_response = json_decode($request->send());
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
		
		$anger = $this->cognition_response->anger;
		$contempt = $this->cognition_response->contempt;
		$disgust = $this->cognition_response->disgust;
		$fear = $this->cognition_response->fear;
		$happiness = $this->cognition_response->happiness;
		$neutral = $this->cognition_response->neutral;
		$sadness = $this->cognition_response->sadness;
		$surprise = $this->cognition_response->surprise;
		
		$anger_weight = -2.0;
		$contempt_weight = -2.0;
		$disgust_weight = -3.0;
		$fear_weight = -3.0;
		$happiness_weight = 2.0;
		$neutral_weight = 2.0;
		$sadness_weight = -1.0;
		$surprise_weight = 1.0;
	}
	
	public function get_dominant_emotion() {
		$emotions_map = $this->get_emotions_map();
		$dominant = array(
			"emotion" => "anger", 
			"value" => $this->cognition_response->anger	
		);
		
		foreach($arr as $key => $value) {
			
			if($value > $dominant[$key]) {
				
				$dominant = array(
					"emotion" => $key,
					"value" => $value
				);
			}
			else if($value == $dominant[$key]) {
				$dominant[$key] = $value;
			}
		}
		
		return $dominant;
	}
	
	private function get_emotions_map() {
		return array(
			"anger" => 	$this->cognition_response->anger,
			"contempt" => $this->cognition_response->contempt,
			"disgust" => $this->cognition_response->disgust,
			"fear" => $this->cognition_response->fear,
			"happiness" => $this->cognition_response->happiness,
			"neutral" => $this->cognition_response->neutral,
			"sadness" => $this->cognition_response->sadness,
			"surprise" => $this->cognition_response->surprise
		);
	}
}
?>