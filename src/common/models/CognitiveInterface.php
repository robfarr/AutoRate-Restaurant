<?php
namespace common\models;
use GuzzleHttp\Client;

class CognitiveInterface {
	private $client;
	private $image_url;
	private $cognition_response;

	public function __construct($image_url) {
		$this->image_url = $image_url;
		$this->establishCognition();
	}

	private function establishCognition() {
		$url = 'https://api.projectoxford.ai/emotion/v1.0/recognize';

		$headers = array(
			// Request headers
			'Content-Type' => 'application/json',
			'Ocp-Apim-Subscription-Key' => \Yii::$app->params['emotionAPIKey'],
		);

		$body = array(
			'json' => array('url' => $this->image_url)
		);

		$this->client = new Client(array('headers' => $headers));

		$request = $this->client->post($url, $body);

		try {
			$this->cognition_response = json_decode($request->getBody()->getContents())[0]->scores;
			
			foreach($this->cognition_response as $key => $value) {
				$this->cognition_response->{$key} = (float) $value;
			}
		}
		catch (HttpException $ex) {
			$this->cognition_response = null;
		}
		catch(\InvalidArgumentException $ex) {
			$this->cognition_response = null;
		}
	}

	public function getImageURL() {
		return $this->image_url;
	}
	
	public function getEmotionValues() {
		return $this->cognition_response;
	}

	public function getPercentileScore() {

		if(is_null($this->cognition_response)) {
			echo "Cognition has not yet been established. ";
			return -1;
		}

		$scores = $this->cognition_response;
		$anger = $scores->anger;
		$contempt = $scores->contempt;
		$disgust = $scores->disgust;
		$fear = $scores->fear;
		$happiness = $scores->happiness;
		$neutral = $scores->neutral;
		$sadness = $scores->sadness;
		$surprise = $scores->surprise;


	}
	
	public function getDominantEmotion() {
		$dominant = array(
			"emotion" => "anger",
			"value" => $this->cognition_response->anger
		);

		foreach($this->cognition_response as $key => $value) {

			if($value > $dominant["value"]) {

				$dominant = array(
					"emotion" => $key,
					"value" => $value
				);
			}
		}

		return $dominant;
	}
}
?>
