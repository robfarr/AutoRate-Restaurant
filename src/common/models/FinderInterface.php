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
		$this->establish_client();
		$this->findRestaurants();
	}
	
	public function establish_client() {
		
		$this->client = new Client([
			'base_uri' => 'https://developers.zomato.com/api/v2.1/',
			'headers' => [
				'Accept' => 'application/json',
				'user-key' => \Yii::$app->params['zomatoAPIKey']
			]
		]);
	}
	
	public function getRestaurants() {
		return $this->restaurants;
	}
	
	private function findRestaurants() {
		try {
			$request = $this->client->get("geocode", [
				'query' => [
						'lat' => $this->latitude,
						'lon' => $this->longitude
					]
			]);
			$this->restaurants = array();
			$response = json_decode($request->getBody()->getContents());
			$this->parse_location($response);
		} catch (\Exception $e) {
			$this->restaurants = array();
		}
	}
	
	private function parse_location($location_details) {
		if (!isset($location_details->nearby_restaurants)) {
			return;
		}
		$nearby_restaurants = $location_details->nearby_restaurants;
		foreach($nearby_restaurants as $value) {
			$restaurant = $value->restaurant;
			$id = $restaurant->id;
			$name = $restaurant->name;
			$address = $restaurant->location->address;
			$this->restaurants[] = array($id, $name, $address);
		}
	}
}
?>