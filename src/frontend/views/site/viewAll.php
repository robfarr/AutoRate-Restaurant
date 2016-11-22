<?php

/* @var $this yii\web\View */
/* @var $restaurants array */
/* @var $restaurant \app\models\Restaurant */

use yii\web\View;
use yii\web\JqueryAsset;

$this->registerAssetBundle(yii\web\JqueryAsset::className(), View::POS_HEAD);

$this->title = 'AutoRate a Restaurant - Rate your restaurant meal by uploading a selfie.';
?>

<div class="site-view-all">

    <div class="jumbotron">
        <h1>AutoRate a Restaurant</h1>

        <p class="lead">Upload a selfie and we'll read your faces to see how you felt!</p>

        <form action="" method="get">
        	<input id='latitude' type="hidden" name="latitude">
        	<input id='longitude' type="hidden" name="longitude">
	        <input type="submit" value="View Nearby" class="btn btn-lg btn-info">
        </form>
        
        <script type="text/javascript">
		$(document).ready(function(){
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(addGeo);
			}
			function addGeo(position) {
				document.getElementById('latitude').value = position.coords.latitude;
				document.getElementById('longitude').value = position.coords.longitude;
			}
		});
		</script>
    </div>

    <?php require('restaurantList.php'); ?>

</div>