<?php

/* @var $this yii\web\View */
/* @var $model \app\models\Restaurant */
/* @var $reviews array */
/* @var $review \app\models\Review */
/* @var $mostCommon string */
/* @var $aggregate array */

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
$mostCommon = $verbs[$mostCommon];

$weight = max($aggregate);
$emotion = array_keys($aggregate, $weight)[0];

?>
<div class="site-index">

    <div class="jumbotron">

        <h1><?= $model->name ?></h1>
        <p><?= $model->address ?></p>

        <?php if($weight > 0){ ?>
        <!-- Display most popular aggregate emotion -->
        <p>Most people are <?= $mostCommon ?> about this restaurant.</p>

        <!-- Show aggregate bar here -->
        <div class="progress">

            <?php

                $colour = 'info';
                if(in_array($emotion, ['anger', 'disgust', 'fear', 'sadness'])) $colour = 'danger';
                if($emotion == 'happiness') $colour = 'success';

            ?>

            <div class="progress-bar progress-bar-<?= $colour ?>" role="progressbar" style="width: <?=
                $weight ?>%">
                <span><?= $weight ?>% <?= ucfirst($emotion) ?></span>
            </div>

        </div>

        <?php }else{ ?>
            <p>No reviews yet, be the first to add yours.</p>
        <?php } ?>

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

                                <?php

                                    $emotion = $review->getMaxEmotion();
                                    $weight = $review->getMaxWeight();

                                    $colour = 'info';
                                    if(in_array($emotion, ['anger', 'disgust', 'fear', 'sadness'])) $colour = 'danger';
                                    if($emotion == 'happiness') $colour = 'success';

                                ?>

                                <div class="progress-bar progress-bar-<?= $colour ?>" role="progressbar" style="width:
                                    <?=
                                    $weight ?>%">
                                    <span><?= $weight ?>% <?= ucfirst($emotion) ?></span>
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
