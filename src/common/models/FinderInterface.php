<?php
namespace common\models;
use GuzzleHttp\Client;

class FinderInterface {
	private $latitude;
	private $longitude;
	private $client;
	private $restaurants;
	
	public function __construct($latitude, $longitude) {
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->client = new Client();
		$this->establish_client();
		$this->findLocation();
	}
	
	public function establish_client() {
		$url = 'https://www.zomato.com/api/v2.1/';
		
		$headers = array(
			'Content-Type' => 'application/json',
			'user-key' => \Yii::$app->params['zomatoAPIKey']
		);
		
		$this->client = new Client(array('headers' => $headers));
	}
	
	public function getRestaurants() {
		return $this->restaurants;
	}
	
	private function findLocation() {
		try {
			$request = $this->client->get("/geocode", [
				'lat' => $this->latitude,
				'lon' => $this->longitude
			]);
			$this->restaurants = array();
			$response = json_decode($request->getBody()->getContents());
			$this->parse_location($response);
		} catch (\Exception $e) {}
	}
	
	private function parse_location($location_details) {
		if (!isset($location_details->nearby_restaurants)) {
			return;
		}
		$all_restaurants = $location_details->nearby_restaurants;
		foreach($all_restaurants as $value) {
			$id = $all_restaurants->id;
			$name = $all_restaurants->name;
			$address = $all_restaurants->location->address;
			$this->restaurants[] = array($id, $name, $address);
		}
	}
}
?>