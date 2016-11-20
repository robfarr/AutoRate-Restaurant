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
 * @property integer $score
 * @property string $emotion
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
            [['image', 'restaurant', 'user', 'score', 'emotion'], 'required'],
            [['restaurant', 'user', 'score'], 'integer'],
            [['image', 'emotion'], 'string', 'max' => 255],
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
            'score' => 'Score',
            'emotion' => 'Emotion',
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
	 * @return number
	 */
    public function getScoreMagnitude()
    {
    	return abs($this->score);
    }

	/**
	 * @return string
	 */
    public function getColour()
    {
	    $colour = 'info';
	    if($this->score > 0) $colour = 'success';
	    if($this->score < 0) $colour = 'danger';
	    return $colour;

    }

}
