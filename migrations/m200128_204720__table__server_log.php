<?php

use yii\db\Migration;

/**
 * Class m200128_204720__table__server_log
 */
class m200128_204720__table__server_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%server_logs}}', [
            'id' => $this->primaryKey(),
            'command' => $this->string(255)->notNull(),
            'action' => $this->string(255)->notNull(),
            'status' => $this->tinyInteger(),
            'custom_data' => $this->string()->defaultValue(null),
            'request' => $this->string()->defaultValue(null),
            'response' => $this->string()->defaultValue(null),
            'created_at' => $this->string()->defaultValue(null),
        ]);
        $this->createIndex('index__command',
            '{{%server_logs}}',
            'command'
        );
        $this->createIndex('index__status',
            '{{%server_logs}}',
            'status'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%server_logs}}');
    }

}
