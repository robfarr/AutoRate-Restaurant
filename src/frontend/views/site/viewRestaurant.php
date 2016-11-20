<?php

/* @var $this yii\web\View */
/* @var $model \app\models\Restaurant */
/* @var $reviews array */
/* @var $review \app\models\Review */
/* @var $mostCommon string */

$this->title = 'Rate a Restaurant - ' . $model->name;
?>
<div class="site-index">

    <div class="jumbotron">

        <h1><?= $model->name ?></h1>
        <p><?= $model->address ?></p>

        <!-- Display most popular aggregate emotion -->
        <p>Most people are <?= $mostCommon ?> about this restaurant.</p>

        <!-- Show aggregate bar here -->
        <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" style="width: 32%">
                <span>32% Excited</span>
            </div>
            <div class="progress-bar progress-bar-danger" role="progressbar" style="width: 12%">
                <span>12% Unhappy</span>
            </div>
        </div>

    </div>

    <div class="body-content">

        <div class="row">

            <!-- Loop and display each rating image & emotion bars -->
            <table class="table table-hover">
                <tbody>

                    <?php foreach($reviews as $review){ ?>
                    <tr>
                        <th width="100"><img src="<?= $review->image ?>" alt="Review Selfie"
                                               class="img-thumbnail"></th>
                        <td style="vertical-align: middle">
                            <div class="progress">


                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?=
    $review->anger ?>%">
                                    <span><?= $review->anger ?>% Anger</span>
                                </div>

                                <div class="progress-bar progress-bar-info" role="progressbar" style="width: <?=
                                    $review->contempt ?>%">
                                    <span><?= $review->contempt ?>% Contempt</span>
                                </div>

                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?=
                                    $review->disgust ?>%">
                                    <span><?= $review->disgust ?>% Disgust</span>
                                </div>

                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width: <?=
                                    $review->fear ?>%">
                                    <span><?= $review->fear ?>% Fear</span>
                                </div>

                                <div class="progress-bar progress-bar-success" role="progressbar" style="width: <?=
                                    $review->happiness ?>%">
                                    <span><?= $review->happiness ?>% Happiness</span>
                                </div>

                                <div class="progress-bar progress-bar-info" role="progressbar" style="width: <?=
                                    $review->neutral ?>%">
                                    <span><?= $review->neutral ?>% Neutral</span>
                                </div>

                                <div class="progress-bar progress-bar-warning" role="progressbar" style="width: <?=
                                    $review->sadness ?>%">
                                    <span><?= $review->sadness ?>% Sadness</span>
                                </div>

                            </div>
                        </td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>

        </div>

    </div>
</div>
