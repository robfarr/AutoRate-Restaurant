<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 * @property integer $id
 * @property string $image
 * @property integer $restaurant
 * @property integer $user
 * @property integer $anger
 * @property integer $contempt
 * @property integer $disgust
 * @property integer $fear
 * @property integer $happiness
 * @property integer $neutral
 * @property integer $sadness
 *
 * @property Restaurant $restaurant0
 * @property User $user0
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'restaurant', 'user'], 'required'],
            [['restaurant', 'user', 'anger', 'contempt', 'disgust', 'fear', 'happiness', 'neutral', 'sadness'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'restaurant' => 'Restaurant',
            'user' => 'User',
            'anger' => 'Anger',
            'contempt' => 'Contempt',
            'disgust' => 'Disgust',
            'fear' => 'Fear',
            'happiness' => 'Happiness',
            'neutral' => 'Neutral',
            'sadness' => 'Sadness',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant0()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

    /**
     * @return string
     */
    public function getMaxEmotion() {

	    $rankings = [
		    'anger'     => $this->anger,
		    'contempt'  => $this->contempt,
		    'disgust'   => $this->disgust,
		    'fear'      => $this->fear,
		    'happiness' => $this->happiness,
		    'neutral'   => $this->neutral,
		    'sadness'   => $this->sadness,
	    ];

	    return array_keys($rankings, max($rankings))[0];

    }

	/**
	 * @return integer
	 */
    public function getMaxWeight() {

    	$rankings = [
		    'anger'     => $this->anger,
		    'contempt'  => $this->contempt,
		    'disgust'   => $this->disgust,
		    'fear'      => $this->fear,
		    'happiness' => $this->happiness,
		    'neutral'   => $this->neutral,
		    'sadness'   => $this->sadness,
	    ];

    	return $rankings[$this->getMaxEmotion()];

    }

}
