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
	 * @return array
	 */
    private function getAggregateRankings()
    {

	    $reviews = $this->reviews;

	    $rankings = [
		    'anger'     => 0,
		    'contempt'  => 0,
		    'disgust'   => 0,
		    'fear'      => 0,
		    'happiness' => 0,
		    'neutral'   => 0,
		    'sadness'   => 0,
	    ];

	    if(count($reviews) > 0) {

		    foreach($reviews as $r) {
			    $rankings['anger'] += $r->anger;
			    $rankings['contempt'] += $r->contempt;
			    $rankings['disgust'] += $r->disgust;
			    $rankings['fear'] += $r->fear;
			    $rankings['happiness'] += $r->happiness;
			    $rankings['neutral'] += $r->neutral;
			    $rankings['sadness'] += $r->sadness;
		    }

		    foreach($rankings as $emotion => $value) {
			    $rankings[$emotion] = $value / count($reviews);
		    }

	    }

	    return $rankings;

    }

    public function getAggregateMostImportantEmotion() {
	    $rankings = $this->getAggregateRankings();
	    return array_keys($rankings, max($rankings))[0];
    }

    public function getAggregateMostImportantEmotionValue() {
	    return max($this->getAggregateRankings());
    }

}
