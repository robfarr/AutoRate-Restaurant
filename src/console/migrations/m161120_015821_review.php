<?php

use yii\db\Migration;

class m161120_015821_review extends Migration
{
    public function up()
    {

	    $this->createTable('review', [
		    'id' => $this->primaryKey(),
		    'image' => $this->string()->notNull(),
		    'restaurant' => $this->integer()->notNull(),
		    'user' => $this->integer()->notNull(),
	        'anger' => $this->integer(),
		    'contempt' => $this->integer(),
		    'disgust' => $this->integer(),
		    'fear' => $this->integer(),
		    'happiness' => $this->integer(),
		    'neutral' => $this->integer(),
		    'sadness' => $this->integer(),
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
