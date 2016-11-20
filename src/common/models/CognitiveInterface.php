<?php
namespace common\models;
use GuzzleHttp\Client;

class CognitiveInterface {
	private $client;
	private $image_url;
	private $cognition_response;
	
	public function __construct($image_url) {
		$this->image_url = $image_url;
		$this->establish_cognition();
	}
	
	private function establish_cognition() {
		$url = 'https://api.projectoxford.ai/emotion/v1.0/recognize';
		
		$headers = array(
			// Request header
			'Content-Type' => 'application/json',
			'Ocp-Apim-Subscription-Key' => \Yii::$app->params['emotionAPIKey'],
		);
		
		$body = array(
			'json' => array('url' => $this->image_url)
		);
		
		$this->client = new Client(array('headers' => $headers));
		
		$request = $this->client->post($url, $body);
		
		try {
			$this->cognition_response = json_decode($request->getBody()->getContents());
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
		
		$scores = $this->cognition_response->scores;
		$anger = (float) $scores->anger;
		$contempt = (float) $scores->contempt;
		$disgust = (float) $scores->disgust;
		$fear = (float) $scores->fear;
		$happiness = (float) $scores->happiness;
		$neutral = (float) $scores->neutral;
		$sadness = (float) $scores->sadness;
		$surprise = (float) $scores->surprise;
		
		
	}
	
	public function get_dominant_emotion() {
		$dominant = array(
			"emotion" => "anger", 
			"value" => (float) $this->cognition_response->scores->anger
		);
		
		foreach($this->cognition_response->scores as $key => $value) {
			
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