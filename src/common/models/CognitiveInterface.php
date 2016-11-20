<?php
namespace common\models;
use GuzzleHttp\Client;

class CognitiveInterface {
	private $client;
	private $image_url;
	private $cognition_response;
	private $numFaces;

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
			$response = json_decode($request->getBody()->getContents());
			$this->cognition_response = new \stdClass();
			
			foreach($response[0]->scores as $key => $value) {
				$this->cognition_response->{$key} = (float) 0;
			}
			
			foreach($response as $face) {
				$scores = $face->scores;
				
				foreach($scores as $key => $value) {
					$this->cognition_response->{$key} += (float) $value;
				}
			}
			
			$this->numFaces = sizeof($response);
			
			foreach($this->cognition_response as $key => $value) {
				$this->cognition_response->{$key} = $value/$this->numFaces;
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
	
	public function getNumFaces() {
		return $this->numFaces;
	}

	public function getPercentileScore() {

		if(is_null($this->cognition_response)) {
			echo "Cognition has not yet been established. ";
			return -1;
		}

		$weights = array(
			"anger" => -2.0,
			"contempt" => -2.0,
			"disgust" => -3.0,
			"fear" => -4.0,
			"happiness" => 3.0,
			"neutral" => 0.0,
			"sadness" => -1.0,
			"surprise" => 0
		);
		
		$total = 0.0;
		$sum = 0.0;
		
		foreach($weights as $key => $value) {
			$val = $value * $this->cognition_response->{$key};
			$high = max(0, $value);
			$low = min(0, $value);
			$val = $this->map($val, $low, $high, -100.0, 100.0);
			$total += $val;
			$sum += abs($val);
		}
		
		return ($total/$sum);
	}
	
	private function map($value, $fromLow, $fromHigh, $toLow, $toHigh) {
		$fromRange = $fromHigh - $fromLow;
		$toRange = $toHigh - $toLow;
		$scaleFactor = $toRange / $fromRange;
	
		// Re-zero the value within the from range
		$tmpValue = $value - $fromLow;
		// Rescale the value to the to range
		$tmpValue *= $scaleFactor;
		// Re-zero back to the to range
		return $tmpValue + $toLow;
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
