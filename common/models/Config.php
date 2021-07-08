<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property int|null $domain_id
 * @property int|null $cron_keywords_enabled
 * @property int|null $cron_keywords_limit
 *
 * @property Domain $domain
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain_id', 'cron_keywords_enabled', 'cron_keywords_limit'], 'integer'],
            [['domain_id'], 'unique'],
            [['domainName'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain_id' => 'Domain ID',
            'cron_keywords_enabled' => 'Парсинг keywords',
            'cron_keywords_limit' => 'Кол-во keywords',
        ];
    }

    /**
     * Gets query for [[Domain]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    public function getDomainName()
    {
        return $this->getDomain()->domain;
    }
}
