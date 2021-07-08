<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%config}}`.
 */
class m210706_184510_create_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey(),
            'domain_id' => $this->integer(),
            'cron_keywords_enabled' => $this->integer(),
            'cron_keywords_limit' => $this->integer(),
        ]);

        $this->createIndex(
            '{{%idx-config-domain_id}}',
            '{{%config}}',
            'domain_id',
            true
        );

        $this->addForeignKey(
            '{{%fk-config-domain_id}}',
            '{{%config}}',
            'domain_id',
            '{{%domain}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
}
