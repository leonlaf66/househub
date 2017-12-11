<?php

use yii\db\Schema;
use yii\db\Migration;

class m160303_144710_table_user_init extends Migration
{
    public function safeUp()
    {
        $sql = file_get_contents(__DIR__.'/sqls/tbl_user.sql');
        $this->execute($sql);
    }
    
    public function safeDown()
    {
    }
}
