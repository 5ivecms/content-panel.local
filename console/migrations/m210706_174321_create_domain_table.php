<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%domain}}`.
 */
class m210706_174321_create_domain_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%domain}}', [
            'id' => $this->primaryKey(),
            'domain' => $this->string(191),
            'token' => $this->string(),
            'comment' => $this->text(),
            'created_at' => $this->integer(),
        ]);

        $this->createIndex(
            '{{%idx-domain-domain}}',
            '{{%domain}}',
            'domain',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%domain}}');
    }
}
