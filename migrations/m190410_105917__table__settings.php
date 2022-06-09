<?php

use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 */
class m190410_105917__table__settings extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(64)->notNull()->unique(),
            'value' => $this->text()->notNull(),
            'comment' => $this->text(),
            'is_json' => $this->boolean(),
            'view' => $this->string(64)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()')
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
