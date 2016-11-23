<?php
namespace common\models;

require ('factual-php-driver/Factual.php');

class FactualWrapper {
	
	const SEARCH_RADIUS_METERS = 1000;
	const RESTAURANT_CATEGORY_ID = 347;
	
	public function search($latitiude, $longitude) {
		$factual = new \Factual(\Yii::$app->params['factualKey'], \Yii::$app->params['factualSecret']);
		$query = new \FactualQuery();
		$query->sortAsc('$distance');
		$query->select(["name", "address", "locality", "region", "postcode", "country", "factual_id"]);
		$query->field("category_ids")->includes(FactualWrapper::RESTAURANT_CATEGORY_ID);
		$query->within(new \FactualCircle($latitiude, $longitude, FactualWrapper::SEARCH_RADIUS_METERS));
		return $this->combine($factual->fetch("places", $query)->getData());
	}
	
	private function combine($response) {
		$result = [];
		foreach($response as $restaurantUnprocessed) {
			$restaurant = new \stdClass();
			$restaurant->name = $restaurantUnprocessed['name'];
			$restaurant->address = 	(array_key_exists('address', $restaurantUnprocessed) ? $restaurantUnprocessed['address'] . ", " : "").
									(array_key_exists('locality', $restaurantUnprocessed) ? $restaurantUnprocessed['locality'] . ", " : "").
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
