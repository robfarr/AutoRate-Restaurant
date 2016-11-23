<?php
namespace common\models;

require ('factual-php-driver/Factual.php');

class FactualWrapper {
	
	const SEARCH_RADIUS_METERS = 1000;
	const RESTAURANT_CATEGORY_ID = 347;
	
	private $factual;
	private $query;
	
	public function __construct() {
		$this->factual = new \Factual(\Yii::$app->params['factualKey'], \Yii::$app->params['factualSecret']);
		$this->resetQuery();
	}
	
	public function addSearchString($string) {
		$this->query->search($string);
		return $this;
	}
	
	public function addGeoQuery($latitiude, $longitude) {
		$this->query->sortAsc('$distance');
		$this->query->within(new \FactualCircle($latitiude, $longitude, FactualWrapper::SEARCH_RADIUS_METERS));
		return $this;
	}
	
	public function resetQuery() {
		$this->query = new \FactualQuery();
		$this->query->limit(10);
		$this->query->offset(0);
		$this->query->includeRowCount();
		$this->query->select(["name", "address", "locality", "region", "postcode", "country", "factual_id"]);
		$this->query->field("category_ids")->includes(FactualWrapper::RESTAURANT_CATEGORY_ID);
		return $this;
	}
	
	public function fetchResults() {
		$data = $this->factual->fetch("places", $this->query);
		return $this->combine($data);
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
			if (array_key_exists('$distance', $restaurantUnprocessed)) {
				$restaurant->distance = $restaurantUnprocessed['$distance'];
			}
			$result[] = $restaurant;
		}
		return $result;
	}
}
?>
