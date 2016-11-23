<?php
namespace common\models;
use GuzzleHttp\Client;

class EmotionWrapper {
	private $client;
	private $image_url;
	private $emotionResponse;
	private $numFaces;

	public function __construct($image_url) {
		$this->image_url = $image_url;
		$this->numFaces = 0;
		$this->establishCognition();
	}

	private function establishCognition() {

		$headers = [
			'Content-Type' => 'application/json',
			'Ocp-Apim-Subscription-Key' => \Yii::$app->params['emotionAPIKey'],
		];

		$body = [
			'json' => [
				'url' => $this->image_url
			]
		];

		$this->client = new Client([
			'base_uri' => 'https://api.projectoxford.ai/emotion/v1.0/',
			'headers' => $headers
		]);

		try {
			$request = $this->client->post('recognize', $body);
			
			$response = json_decode($request->getBody()->getContents());
			
			if(empty($response)) {
				return;
			}
			
			$this->emotionResponse = new \stdClass();
			
			foreach($response[0]->scores as $key => $value) {
				$this->emotionResponse->{$key} = (float) 0;
			}
			
			foreach($response as $face) {
				$scores = $face->scores;
				
				foreach($scores as $key => $value) {
					$this->emotionResponse->{$key} += (float) $value;
				}
			}
			
			$this->numFaces = sizeof($response);
			
			foreach($this->emotionResponse as $key => $value) {
				$this->emotionResponse->{$key} = $value/$this->numFaces;
			}
		}
		catch(\InvalidArgumentException $ex) {
			$this->emotionResponse = null;
		}
		catch(\Exception $ex) {
			$this->emotionResponse = null;
		}
	}

	public function getImageURL() {
		return $this->image_url;
	}
	
	public function getEmotionValues() {
		return $this->emotionResponse;
	}
	
	public function getNumFaces() {
		return $this->numFaces;
	}

	public function getPercentileScore() {

		if(is_null($this->emotionResponse)) {
			return 0.0;
		}

		$values = [
			"anger" => -100.0,
			"contempt" => -100.0,
			"disgust" => -100.0,
			"fear" => -100.0,
			"happiness" => 100.0,
			"neutral" => 0.0,
			"sadness" => -100.0,
			"surprise" => 0.0
		];
		
		$total = 0.0;
		
		foreach($values as $key => $value) {
			
			if($key == "surprise") {
				$sign = $total/abs($total);
				$total += (($total + ($sign * 100.0))/2.0) * $this->emotionResponse->surprise;
			}
			else {
				$total += $value * $this->emotionResponse->{$key};
			}
		}
		
		return $total;
	}
	
	public function getDominantEmotion() {
		if(is_null($this->emotionResponse)) return "";
		
		$dominant = [
			"emotion" => "anger",
			"value" => $this->emotionResponse->anger
		];

		foreach($this->emotionResponse as $key => $value) {

			if($value > $dominant["value"]) {

				$dominant = [
					"emotion" => $key,
					"value" => $value
				];
			}
		}

		return $dominant;
	}
}
?>
