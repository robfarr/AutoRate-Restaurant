<?php
namespace common\models;
use GuzzleHttp\Client;

class GeocodeInterface {
	private $latitude;
	private $longitude;
	private $locality;
	private $country;
	private $client;
	
	public function __construct($latitude, $longitude) {
		$this->latitude = latitude;
		$this->longitude = longitude;
		reverse_geocode();
	}
	
	private function reverse_geocode() {
		$url = "http://maps.googleapis.com/maps/api/geocode/json";
	
		$params = array (
			"latlng" => $this->latitude . "," . $this->longitude,
			"sensor" => true
		);
		
		$this->client = new Client();
		
		try {
			$response = $this->client->get($url, $params)->getBody()->getContents();
			$this->parseLocationDetails(json_decode($response));
		}
		catch(Exception $ex) {
			$this->locality = "";
			$this->country = "";
		}
	}
	
	private function parseLocationDetails($location_details) {
		$address_components = $location_details->results[0]->address_components;
		
		foreach($address_components as $key => $value) {
			
			if(in_array("locality", $value->types)) {
				$this->locality = $value->locality;
			}
			else if(in_array("country", $value->types)) {
				$this->country = $value->country;
			}
		}
	}
	
	public function getLocality() {
		return $this->locality;
	}
	
	public function getCountry() {
		return $this->country;
	}
	
	public function getAddress() {
		
		if(strlen($this->locality) == 0 ||
				strlen($this->country) == 0) {
			return "";
		}
		
		return $this->locality . ", " . $this->country;
	}
}
?>