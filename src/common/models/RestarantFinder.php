<?php
namespace common\models;

use app\models\Restaurant;

class RestarantFinder extends FinderInterface{
	
	public function getNearbyRestaurants() {
		$restaurants = $this->getRestaurants();
		
		$result = array();
		
		if (count($restaurants) == 0) {
			return $result;
		}
		
		foreach ($restaurants as $triple) {
			$restaurant = Restaurant::findOne($triple[0]);
			if ($restaurant == null) {
				$restaurant = new Restaurant();
				$restaurant->id = $triple[0];
				$restaurant->name = $triple[1];
				$restaurant->address = $triple[2];
				$restaurant->save();
			}
			$result[] = $restaurant;
		}
		
		return $result;
	}
}
?>