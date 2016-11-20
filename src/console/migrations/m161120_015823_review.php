<?php

use yii\db\Migration;

class m161120_015823_review extends Migration
{
    public function up()
    {

	    $this->createTable('review', [
		    'id' => $this->primaryKey(),
		    'image' => $this->string()->notNull(),
		    'restaurant' => $this->integer()->notNull(),
		    'user' => $this->integer()->notNull(),
	        'score' => $this->integer()->notNull(),
		    'emotion' => $this->string()->notNull(),
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
