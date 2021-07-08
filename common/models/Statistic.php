<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "statistic".
 *
 * @property int $id
 * @property int|null $domain_id
 * @property int|null $countArticles
 * @property int|null $countKeywords
 * @property int|null $countNewKeywords
 *
 * @property Domain $domain
 */
class Statistic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain_id', 'countArticles', 'countKeywords', 'countNewKeywords'], 'integer'],
            [['domain_id'], 'unique'],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domain::className(), 'targetAttribute' => ['domain_id' => 'id']],
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
            'countArticles' => 'Count Videos',
            'countKeywords' => 'Count Keywords',
            'countNewKeywords' => 'Count New Keywords',
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
}
