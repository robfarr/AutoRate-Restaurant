<?php

/* @var $this yii\web\View */
/* @var $model \app\models\Restaurant */
/* @var $reviews array */
/* @var $review \app\models\Review */
/* @var $mostCommon string */
/* @var $aggregate array */

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

            <?php

                $weight = max($aggregate);
                $emotion = array_keys($aggregate, $weight)[0];

                $colour = 'info';
                if(in_array($emotion, ['anger', 'disgust', 'fear', 'sadness'])) $colour = 'danger';
                if($emotion == 'happiness') $colour = 'success';

            ?>

            <div class="progress-bar progress-bar-<?= $colour ?>" role="progressbar" style="width: <?=
                $weight ?>%">
                <span><?= $weight ?>% <?= $emotion ?></span>
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
                                    <span><?= $weight ?>% <?= $emotion ?></span>
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
