<?php

use yii\db\Migration;

class m161120_015707_restaurant extends Migration
{
    public function up()
    {

	    $this->createTable('restaurant', [
		    'id' => Schema::TYPE_PK,
		    'name' => Schema::TYPE_STRING . ' NOT NULL',
		    'address' => Schema::TYPE_STRING,
	    ]);

    }

    public function down()
    {
        echo "m161120_015707_restaurant cannot be reverted.\n";

        return false;
    }

}
