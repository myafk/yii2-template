<?php

use yii\db\Migration;

class m180913_000020__table__attachment extends Migration
{
    public function up()
    {
        $this->createTable('{{%attachments}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(null),
            'model' => $this->string(256),
            'model_attribute' => $this->string(64),
            'object_id' => $this->integer()->unsigned(),
            'title' => $this->string(256)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
            'mime' => $this->string(32)->defaultValue(null),
            'path' => $this->string(256)->notNull(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultValue(new \yii\db\Expression('CURRENT_TIMESTAMP')),
        ]);

        $this->addForeignKey('attachment__user_id',
            '{{%attachments}}',
            'user_id',
            '{{%users}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey('user__avatar_id',
            '{{%users}}',
            'avatar_id',
            '{{%attachments}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('attachment__user_id', '{{%attachments}}');

        $this->dropForeignKey('user__avatar_id', '{{%users}}');

        $this->dropTable('{{%attachments}}');
    }

}
