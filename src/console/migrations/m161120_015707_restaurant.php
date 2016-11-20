<?php

use yii\db\Migration;

class m161120_015707_restaurant extends Migration
{
    public function up()
    {

	    $this->createTable('restaurant', [
		    'id' => $this->primaryKey(),
		    'name' => $this->string()->notNull(),
		    'address' => $this->string(),
	    ]);

    }

    public function down()
    {
        echo "m161120_015707_restaurant cannot be reverted.\n";

        return false;
    }

}
