<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistic}}`.
 */
class m210706_174553_create_statistic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%statistic}}', [
            'id' => $this->primaryKey(),
            'domain_id' => $this->integer(),
            'countArticles' => $this->integer()->defaultValue(0),
            'countKeywords' => $this->integer()->defaultValue(0),
            'countNewKeywords' => $this->integer()->defaultValue(0),
        ]);

        $this->createIndex(
            '{{%idx-statistic-domain_id}}',
            '{{%statistic}}',
            'domain_id',
            true
        );

        $this->addForeignKey(
            '{{%fk-statistic-domain_id}}',
            '{{%statistic}}',
            'domain_id',
            '{{%domain}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-statistic-countVideos}}',
            '{{%statistic}}',
            'countArticles'
        );

        $this->createIndex(
            '{{%idx-statistic-countKeywords}}',
            '{{%statistic}}',
            'countKeywords'
        );

        $this->createIndex(
            '{{%idx-statistic-countNewKeywords}}',
            '{{%statistic}}',
            'countNewKeywords'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%statistic}}');
    }
}
