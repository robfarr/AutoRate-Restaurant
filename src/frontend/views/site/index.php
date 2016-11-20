<?php

/* @var $this yii\web\View */
/* @var $restaurants array */
/* @var $restaurant \app\models\Restaurant */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Rate a Restaurant - Rate your restaurant meal by uploading a selfie.';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Rate a Restaurant</h1>

        <p class="lead">Rate your restaurant meal by uploading a selfie.</p>

        <button class="btn btn-lg btn-success" data-toggle="modal" data-target="#submit-modal">
            <span class="glyphicon glyphicon-camera"></span>
            Rate Restaurant
        </button>

    </div>

    <div class="body-content">

        <div class="row">

            <table class="table table-hover">
                <tbody>

                    <?php
                        foreach($restaurants as $restaurant){
                            $value = $restaurant->getAggregateScore();
                            $colour = 'info';
                            if($value > 0) $colour = 'success';
                            if($value < 0) $colour = 'danger';
                    ?>
                    <tr>
                        <th><?= $restaurant->name ?></th>
                        <td width="75%">
                            <?php if($restaurant->getReviews()->count() > 0) { ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-<?= $colour ?>" role="progressbar"
                                         style="width:
                                        <?= abs($value) ?>%">
                                        <span><?= abs($value) ?>%</span>
                                    </div>
                                </div>
                            <?php }else{ ?>
                                <p>No reviews yet, be the first to add yours.</p>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($restaurant->getReviews()->count() > 0) { ?>
                            <a href="<?= Url::to(['site/view-restaurant', 'restaurant' => $restaurant->id]) ?>"><span class="glyphicon glyphicon-chevron-right"></span></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>

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
                        Rate Restaurant
                    </h4>
                </div>

                <?php if(Yii::$app->user->isGuest) { ?>

                    <div class="modal-body">
                        <p>You need to sign in before you can add reviews.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="<?= Url::to(['site/login', 'restaurant' => $restaurant->id]) ?>" title="Sign In"
                           class="btn btn-success">Sign In</a>
                    </div>

                <?php }else{ ?>

                    <?php $form = ActiveForm::begin(['layout' => 'horizontal','options' => ['enctype' => 'multipart/form-data']]) ?>

                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-sm-3" for="restaurant">Select Restaurant</label>
                            <div class="col-sm-9">
                                <select name="restaurant" class="form-control">
                                    <?php foreach ($restaurants as $r) { ?>
                                        <option value="<?= $r->id ?>"><?= $r->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*'])->label("Take 
                            Selfie") ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-camera"></span>
                            Rate Restaurant
                        </button>
                    </div>

                    <?php ActiveForm::end() ?>

                <?php } ?>

            </div>
        </div>
    </div>

</div>
