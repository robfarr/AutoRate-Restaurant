<?php

/* @var $this yii\web\View */
/* @var $restaurants array */
/* @var $restaurant \app\models\Restaurant */

use yii\helpers\Url;
use yii\web\View;
use yii\web\JqueryAsset;

$this->registerAssetBundle(yii\web\JqueryAsset::className(), View::POS_HEAD);
?>
    <div class="body-content">
        <div class="row">
            <table class="table table-hover">
                <tbody>

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
