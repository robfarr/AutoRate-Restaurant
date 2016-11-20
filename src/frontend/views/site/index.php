<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;

$this->title = 'Rate a Restaurant - Rate your restaurant meal by uploading a selfie.';
?>

<div class="site-index">
    <select name= "restaurant">
        <?php
            foreach ($restaurant as $r) {
               <option value="<?= $r->id ?>"><?= $r->name ?></option>
            }
        ?>
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
            <button class="btn btn-lg btn-success"><span class="glyphicon glyphicon-camera"></span>Rate Restaurant</button>

        <?php ActiveForm::end() ?>

    </div>

    <div class="body-content">

        <div class="row">

            <table class="table table-hover">
                <tbody>

                    <tr>
                        <th>Restaurant Name</th>
                        <td width="75%">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" style="width: 55%">
                                    <span>55% Excited</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="#"><span class="glyphicon glyphicon-chevron-right"></span></a></td>
                    </tr>

                    <tr>
                        <th>Restaurant Name</th>
                        <td width="75%">
                            <div class="progress">
                                <div class="progress-bar progress-bar-info" role="progressbar" style="width: 64%">
                                    <span>64% Happy</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="#"><span class="glyphicon glyphicon-chevron-right"></span></a></td>
                    </tr>

                    <tr>
                        <th>Restaurant Name</th>
                        <td width="75%">
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" style="width: 12%">
                                    <span>12% Surprised</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="#"><span class="glyphicon glyphicon-chevron-right"></span></a></td>
                    </tr>

                    <tr>
                        <th>Restaurant Name</th>
                        <td width="75%">
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 32%">
                                    <span>32% Unhappy</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="#"><span class="glyphicon glyphicon-chevron-right"></span></a></td>
                    </tr>

                </tbody>
            </table>

        </div>

    </div>
</div>
