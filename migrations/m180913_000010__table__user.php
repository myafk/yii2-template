<?php

use yii\db\Migration;

/**
 * Class m180913_110324__table__user
 */
class m180913_000010__table__user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}',[
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull()->unique(),
            'password_hash' => $this->string(128)->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string(250)->notNull()->unique(),
            'auth_key' => $this->string(32)->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP()'),
            'last_name' => $this->string(128),
            'first_name' => $this->string(128),
            'patronymic' => $this->string(128),
            'function' => $this->string(128),
            'phone' => $this->string(128),
            'avatar_id' => $this->integer()->defaultValue(null),
            'last_visit_at' => $this->timestamp()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
