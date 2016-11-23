<?php
namespace common\models;

use app\models\Restaurant;

class RestarantFinder extends ZomatoWrapper {
	
	public function getNearbyRestaurants() {
		$restaurants = $this->getRestaurants();
		
		$result = array();
		
		if (count($restaurants) == 0) {
			return $result;
		}
		
		foreach ($restaurants as $restaurant) {
			$localRestaurant = Restaurant::findOne($restaurant->id);
			if ($localRestaurant == null) {
				$localRestaurant = new Restaurant();
				$localRestaurant->id = $restaurant->id;
				$localRestaurant->name = $restaurant->name;
				$localRestaurant->address = $restaurant->address;
				$localRestaurant->save();
			}
			$result[] = $localRestaurant;
		}
		
		return $result;
	}
}
?>