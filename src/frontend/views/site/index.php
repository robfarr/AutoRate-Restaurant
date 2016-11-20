<?php

/* @var $this yii\web\View */
/* @var $restaurants array */
/* @var $restaurant \app\models\Restaurant */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Rate a Restaurant - Rate your restaurant meal by uploading a selfie.';
?>

<div class="site-index">
    <select name= "restaurant">
        <?php foreach ($restaurants as $r) { ?>
               <option value="<?= $r->id ?>"><?= $r->name ?></option>
        <?php } ?>
    </select>
    <div class="jumbotron">
        <h1>Rate a Restaurant</h1>

        <p class="lead">Rate your restaurant meal by uploading a selfie.</p>
        

        <?php $form = ActiveForm::begin(['layout' => 'horizontal','options' => ['enctype' => 'multipart/form-data']]) ?> 
            <div class="row">
                <div class="col-md-12">
                    <span style="display: inline-block;">
                        <?= $form->field($model, 'imageFile')->fileInput() ?>
                    </span>
                </div>
            
            </div>
            <button class="btn btn-lg btn-success"><span class="glyphicon glyphicon-camera"></span> Rate
                                                                                                    Restaurant</button>

        <?php ActiveForm::end() ?>

    </div>

    <div class="body-content">

        <div class="row">

            <table class="table table-hover">
                <tbody>

                    <?php
                        foreach($restaurants as $restaurant){

                            $emotion = $restaurant->getAggregateMostImportantEmotion();
                            $value = $restaurant->getAggregateMostImportantEmotionValue();

                            $colour = 'info';
                            if(in_array($emotion, ['anger', 'disgust', 'fear', 'sadness'])) $colour = 'danger';
                            if($emotion == 'happiness') $colour = 'success';

                    ?>
                    <tr>
                        <th><?= $restaurant->name ?></th>
                        <td width="75%">
                            <?php if($value > 0) { ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-<?= $colour ?>" role="progressbar" style="width:
                                        <?=
                                        $value ?>%">
                                        <span><?= $value ?>% <?= ucfirst($emotion) ?></span>
                                    </div>
                                </div>
                            <?php }else{ ?>
                                <p>No reviews yet, be the first to add yours.</p>
                            <?php } ?>
                        </td>
                        <td><a href="<?= Url::to(['site/view-restaurant', 'restaurant' => $restaurant->id]) ?>"><span
                                    class="glyphicon
                            glyphicon-chevron-right"></span></a></td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>

        </div>

    </div>
</div>
