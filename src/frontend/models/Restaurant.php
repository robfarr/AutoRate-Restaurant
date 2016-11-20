<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "restaurant".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 *
 * @property Review[] $reviews
 */
class Restaurant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'restaurant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['restaurant' => 'id']);
    }

	/**
	 * @return number
	 */
    public function getAggregateScore()
    {

	    $reviews = $this->reviews;

	    $score = 0;

	    if(count($reviews) > 0) {

		    foreach($reviews as $r) {
			    $score += $r->score;
		    }

		    $score /= count($reviews);

	    }

	    return $score;

    }

	/**
	 * @return string
	 */
    public function getMostFrequentEmotion()
    {

	    $emotions = [];
	    foreach($this->reviews as $review) {
	    	if(isset($emotions[$review->emotion])) {
	    		$emotions[$review->emotion]++;
		    }else {
			    $emotions[$review->emotion] = 1;
		    }
	    }

	    return array_keys($emotions, max($emotions))[0];

    }


}
