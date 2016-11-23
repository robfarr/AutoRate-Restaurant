<?php
namespace common\models;

require ('factual-php-driver/Factual.php');

class FactualWrapper {
	
	const RADIUS = 1000;
	const RESTAURANT_ID = 347;
	
	private $client;
	
	public function __construct() {
		$this->client = new \Factual(\Yii::$app->params['factualKey'], \Yii::$app->params['factualSecret']);
	}
	
	public function search($latitiude, $longitude) {
		$query = new \FactualQuery();
		$query->sortAsc("\$distance");
		$query->select(["name", "address", "country", "factual_id", "locality", "post_town", "region", "postcode"]);
		$query->field("category_ids")->includes(FactualWrapper::RESTAURANT_ID);
		$query->within(new \FactualCircle($latitiude, $longitude, FactualWrapper::RADIUS));
		$result = $this->client->fetch("places", $query);
		return $this->combine($result->getData());
	}
	
	private function combine($response) {
		$result = [];
		foreach($response as $restaurantUnprocessed) {
			$restaurant = new \stdClass();
			$restaurant->name = $restaurantUnprocessed['name'];
			$restaurant->address = 	(array_key_exists('address', $restaurantUnprocessed) ? $restaurantUnprocessed['address'] . ", " : "").
									(array_key_exists('post_town', $restaurantUnprocessed) ? $restaurantUnprocessed['post_town'] . ", " : "").
									(array_key_exists('region', $restaurantUnprocessed) ? $restaurantUnprocessed['region'] . ", " : "") .
									(array_key_exists('postcode', $restaurantUnprocessed) ? ($restaurantUnprocessed['postcode'] . ", ") : "").
									(array_key_exists('country', $restaurantUnprocessed) ? Countries::getCountry($restaurantUnprocessed['country']) : "");
			$restaurant->id = $restaurantUnprocessed['factual_id'];
			$restaurant->distance = $restaurantUnprocessed['$distance'];
			$result[] = $restaurant;
		}
		return $result;
	}
}
?>
