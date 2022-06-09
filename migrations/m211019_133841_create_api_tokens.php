<?php

use yii\db\Migration;

/**
 * Class m211019_133841_create_api_tokens
 */
class m211019_133841_create_api_tokens extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%api_tokens}}', [
            'id' => $this->primaryKey(),
            'access_token' => $this->string(64)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%api_tokens}}');
    }
}
