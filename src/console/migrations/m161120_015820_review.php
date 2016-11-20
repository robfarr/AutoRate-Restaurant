<?php

use yii\db\Migration;

class m161120_015820_review extends Migration
{
    public function up()
    {

	    $this->createTable('review', [
		    'id' => Schema::TYPE_PK,
		    'restaurant' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'user' => Schema::TYPE_INTEGER . ' NOT NULL',
	        'anger' => Schema::TYPE_INTEGER,
		    'contempt' => Schema::TYPE_INTEGER,
		    'disgust' => Schema::TYPE_INTEGER,
		    'fear' => Schema::TYPE_INTEGER,
		    'happiness' => Schema::TYPE_INTEGER,
		    'neutral' => Schema::TYPE_INTEGER,
		    'sadness' => Schema::TYPE_INTEGER,
	    ]);

	    $this->addForeignKey(
		    'fk-review-restaurant',
		    'review',
		    'restaurant',
		    'restaurant',
		    'id',
		    'CASCADE'
	    );

	    $this->addForeignKey(
		    'fk-review-user',
		    'review',
		    'user',
		    'user',
		    'id',
		    'CASCADE'
	    );

    }

    public function down()
    {
        echo "m161120_015820_review cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
