<?php
namespace common\models;
use GuzzleHttp\Client;

class FinderInterface {
	private $latitude;
	private $longitude;
	private $entityID;
	private $entityType;
	private $client;
	
	public function __construct($latitude, $longitude) {
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->client = new Client();
		$this->find_location();
	}
	
	public function establish_client() {
		$url = 'https://api.projectoxford.ai/emotion/v1.0/recognize';
		
		$headers = array(
			'Content-Type' => 'application/json',
			'user-key' => \Yii::$app->params['zomatoAPIKey']
		);
		
		$this->client = new Client(array('headers' => $headers));
	}
	
	public function find_location() {
		
	}
}
?>