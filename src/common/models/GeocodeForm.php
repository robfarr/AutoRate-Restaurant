<?php
namespace common\models;

use yii\base\Model;

class GeocodeForm extends Model {
	public $latitude;
	public $longitude;
	
	public function found_location() {
		$gi = new FinderInterface($latitude, $longitude);
		return $gi->getRestaurants();
	}
}
?>