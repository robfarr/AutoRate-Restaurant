<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_table".
 *
 * @property integer $idtest_table
 * @property string $name
 * @property string $dob
 */
class TestTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtest_table', 'name', 'dob'], 'required'],
            [['idtest_table'], 'integer'],
            [['dob'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idtest_table' => 'Idtest Table',
            'name' => 'Name',
            'dob' => 'Dob',
        ];
    }
}
