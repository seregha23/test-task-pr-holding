<?php

use yii\db\Migration;

/**
 * Class m220819_160846_apples
 */
class m220819_160846_apples extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%apples}}', [
            'id'          => $this->primaryKey(),
            'color_id'    => $this->integer(10),
            'status_id'   => $this->integer(10),
            'size'        => $this->decimal(10, 1),
            'create_date' => $this->dateTime(),
            'fall_date'   => $this->dateTime()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%apples}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220819_160846_apples cannot be reverted.\n";

        return false;
    }
    */
}
