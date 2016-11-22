<?php

/* @var $this yii\web\View */
/* @var $restaurants array */
/* @var $restaurant \app\models\Restaurant */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JqueryAsset;

$this->registerAssetBundle(yii\web\JqueryAsset::className(), View::POS_HEAD);

$this->title = 'AutoRate a Restaurant - Rate your restaurant meal by uploading a selfie.';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>AutoRate a Restaurant</h1>

        <p class="lead">Upload a selfie and we'll read your faces to see how you felt!</p>

        <?php if (count($restaurants)): ?>

        <button class="btn btn-lg btn-success" data-toggle="modal" data-target="#submit-modal">
            <span class="glyphicon glyphicon-camera"></span>
            AutoRate Restaurant
        </button>

        <?php else: ?>

        <form method="get">
        	<input id='latitude' type="hidden" name="latitude">
        	<input id='longitude' type="hidden" name="longitude">
	        <input type="submit" value="Find Nearby" class="btn btn-lg btn-info">
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

        <?php endif; ?>

    </div>
    
    <?php if (count($restaurants)) : ?>

       <h2>Nearby To You: <a href="<?= Url::toRoute(['/site/view-all'])?>" class="btn btn-info">View All <span class="glyphicon glyphicon-arrow-right"></span></a></h2><br/>

    <?php endif; ?>

    <?php require('restaurantList.php'); ?>

    <div id="submit-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button class="close" data-dismiss="modal"></button>
                    <h4 class="modal-title">
                        <span class="glyphicon glyphicon-camera"></span>
                        AutoRate Restaurant
                    </h4>
                </div>

                <?php if(Yii::$app->user->isGuest) : ?>

                    <div class="modal-body">
                        <p>You need to sign in before you can add reviews.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="<?= Url::to(['site/login']) ?>" title="Sign In"
                           class="btn btn-success">Sign In</a>
                    </div>

                <?php else: ?>

                    <?php $form = ActiveForm::begin(['layout' => 'horizontal','options' => ['enctype' => 'multipart/form-data']]) ?>

                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-sm-3" for="restaurant">Select Restaurant</label>
                            <div class="col-sm-9">
                                <select name="restaurant" class="form-control">
                                    <?php foreach ($restaurants as $r): ?>
                                        <option value="<?= $r->id ?>"><?= $r->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*'])->label("Take
                            Selfie") ?>
                        </div>

                        <p>We'll analyse your selfie to automatically determine a rating for this meal based on the
                           emotions that we can detect in your photo. Photos uploaded will be publicly accessible.</p>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-camera"></span>
                            AutoRate Restaurant
                        </button>
                    </div>

                    <?php ActiveForm::end() ?>

                <?php endif; ?>

            </div>
        </div>
    </div>

</div>
