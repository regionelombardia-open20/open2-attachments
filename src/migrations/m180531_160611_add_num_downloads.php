<?php

use yii\db\Migration;
use yii\db\Schema;

class m180531_160611_add_num_downloads extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('attach_file','num_downloads', Schema::TYPE_INTEGER." DEFAULT 0");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('attach_file','num_downloads');
    }
}
