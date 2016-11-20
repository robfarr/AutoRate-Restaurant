<?php
namespace common\models;
use GuzzleHttp\Client;

class FinderInterface {
	private $latitude;
	private $longitude;
	private $entityID;
	private $entityType;
	private $client;
	private $nearby_restaurants;
	private $restaurants;
	
	public function __construct($latitude, $longitude) {
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->client = new Client();
		$this->find_location();
	}
	
	public function establish_client() {
		$url = 'https://www.zomato.com/api/v2.1/';
		
		$headers = array(
			'Content-Type' => 'application/json',
			'user-key' => \Yii::$app->params['zomatoAPIKey']
		);
		
		$this->client = new Client(array('headers' => $headers));
	}
	
	public function get_restaurants() {
		return $this->restaurants;
	}
	
	private function find_location() {
		
	}
	
	private function parse_location($location_details) {
		$this->restaurants=array();
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