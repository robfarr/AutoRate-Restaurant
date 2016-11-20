<?php

/* @var $this yii\web\View */
/* @var $model \app\models\Restaurant */
/* @var $reviews array */
/* @var $review \app\models\Review */
/* @var $mostCommon string */

$this->title = 'Rate a Restaurant - ' . $model->name;

$verbs = [
    'anger'     =>  'angry',
    'contempt'  =>  'contempt',
    'disgust'   =>  'disgusted',
    'fear'      =>  'fearful',
    'happiness' =>  'happy',
    'neutral'   =>  'neutral',
    'sadness'   =>  'sad',
];
$mostCommon = $verbs[$model->getMostFrequentEmotion()];

?>
<div class="site-index">

    <div class="jumbotron">

        <h1><?= $model->name ?></h1>
        <p><?= $model->address ?></p>

        <?php if($model->getReviews()->count() > 0){ ?>
        <?php
            $aggregateScore = $model->getAggregateScore();

            $word = 'are neutral about';
            if($aggregateScore > 5) $word = 'like';
            if($aggregateScore < -5) $word = 'dislike';

        ?>
        <p>Most people <?= $word ?> this restaurant.</p>

        <div class="progress">
            <?php

                $colour = 'info';
                if($aggregateScore > 0) $colour = 'success';
                if($aggregateScore < 0) $colour = 'danger';
            ?>
            <div class="progress-bar progress-bar-<?= $colour ?>" role="progressbar" style="width: <?= abs
            ($aggregateScore) ?>%">
                <span><?= round(abs($aggregateScore)) ?>%</span>
            </div>
        </div>

        <?php }else{ ?>
            <p>No reviews yet, be the first to add yours.</p>
        <?php } ?>

    </div>

    <div class="body-content">

        <div class="row">

            <table class="table table-hover">
                <tbody>

                    <?php foreach($reviews as $review){ ?>
                    <tr>
                        <th width="100">
                            <a href="<?= $review->image ?>" title="Large Preview" target="_blank">
                                <img src="<?= $review->image ?>" alt="Review Selfie" class="img-thumbnail" />
                            </a>
                        </th>
                        <td style="vertical-align: middle">
                            <div class="progress">
                                <div class="progress-bar progress-bar-<?= $review->getColour() ?>" role="progressbar"
                                     style="width:
                                    <?=
                                    $review->getScoreMagnitude() ?>%">
                                    <span><?= round(abs($review->getScoreMagnitude())) ?>%</span>
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
