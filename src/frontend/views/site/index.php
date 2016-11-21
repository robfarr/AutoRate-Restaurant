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

	<script type="text/javascript">
	$(document).ready(function(){
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(addGeo);
		}
		function addGeo(position) {
			document.getElementById('latInput').value = position.coords.latitude;
			document.getElementById('lonInput').value = position.coords.longitude;
// 			var json = "longitude=" + position.coords.longitude + "&latitude=" + position.coords.latitude;
// 			$.ajax({
// 				url : 'index.php?r=site/index',
// 				type : 'POST',
// 				data : json,
// 				success : function(response) {
// 					console.log('worked');
// 				},
// 				error : function(e) {
// 					console.log(e);
// 				}
// 			});
		}
	});
	</script>

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
        	<input id='latInput' type="hidden" name="latitude">
        	<input id='lonInput' type="hidden" name="longitude">
	        <input type="submit" value="Find Nearby" class="btn btn-lg btn-info">
        </form>

        <?php endif; ?>

    </div>

    <div class="body-content">
        <div class="row">
            <table class="table table-hover">
                <tbody>

                    <?php if (count($restaurants)) : ?>

                    <h2>Nearby To You:</h2><br/>

                    <?php endif; ?>

                    <?php foreach($restaurants as $restaurant):

                    $value = $restaurant->getAggregateScore();
                    $colour = 'info';
                    if($value > 0) $colour = 'success';
                    if($value < 0) $colour = 'danger';

                    ?>

                    <tr>
                        <th><?= $restaurant->name ?></th>

                        <?php if($restaurant->getReviews()->count() > 0) : ?>

                        <td width="75%" onclick="window.location = '<?= Url::to(['site/view-restaurant', 'restaurant' => $restaurant->id]) ?>';">
                        	<div class="progress">
								<div class="progress-bar progress-bar-<?= $colour ?>" role="progressbar" style="width: <?= abs($value) ?>%">
									<span><?= round(abs($value)) ?>%</span>
								</div>
                        	</div>
                        </td>
                        <td>
                        	<a href="<?= Url::to(['site/view-restaurant', 'restaurant' => $restaurant->id]) ?>"><span class="glyphicon glyphicon-chevron-right"></span></a>
                        </td>

                        <?php else: ?>

                        <td width="75%">
                        	<p>No reviews yet, be the first to add yours.</p>
                        </td>
                        <td></td>

                        <?php endif; ?>

                    </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

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
