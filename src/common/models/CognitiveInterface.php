<?php
namespace common\models;

class CognitiveInterface {
	private $image_url;
	private $cognition_response;
	
	public function __construct($image_url) {
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
			'Ocp-Apim-Subscription-Key' => '{' . \Yii::$app->params['emotionAPIKey'] . '}',
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
			$this->cognition_response = null;
		}
		catch(\InvalidArgumentException $ex) {
			$this->cognition_response = null;
		}
	}
	
	public function get_image_url() {
		return $this->image_url;
	}
	
	public function get_percentile_score() {
		
		if(is_null($this->cognition_response)) {
			echo "Cognition has not yet been established. ";
			return -1;
		}
		
		$anger = (float) $this->cognition_response->anger;
		$contempt = (float) $this->cognition_response->contempt;
		$disgust = (float) $this->cognition_response->disgust;
		$fear = (float) $this->cognition_response->fear;
		$happiness = (float) $this->cognition_response->happiness;
		$neutral = (float) $this->cognition_response->neutral;
		$sadness = (float) $this->cognition_response->sadness;
		$surprise = (float) $this->cognition_response->surprise;
		
		
	}
	
	public function get_dominant_emotion() {
		$dominant = array(
			"emotion" => "anger", 
			"value" => (float) $this->cognition_response->anger	
		);
		
		foreach($this->cognition_response as $key => $value) {
			
			if($value > $dominant["value"]) {
				
				$dominant = array(
					"emotion" => $key,
					"value" => (float) $value
				);
			}
		}
		
		return $dominant;
	}
}
?>