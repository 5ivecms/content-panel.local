<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "domain".
 *
 * @property int $id
 * @property string|null $domain
 * @property string|null $comment
 * @property string|null $token
 *
 * @property Statistic $statistic
 * @property Config $config
 */
class Domain extends \yii\db\ActiveRecord
{
    const CLEAR_CACHE_URL = '/api/cache/clear';
    const ADD_KEYWORD_URL = '/api/v1/keywords';
    const GET_STATISTIC_URL = '/api/v1/statistics';
    const SETTINGS_URL = '/api/v1/settings';
    const PARSING_URL = '/api/v1/parsings';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'domain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment', 'token'], 'string'],
            [['domain'], 'string', 'max' => 255],
            [['domain'], 'unique'],
            ['domain', 'trim'],
            ['domain', 'trimSlashes']
        ];
    }

    public function trimSlashes()
    {
        $this->domain = trim($this->domain, '/');
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Домен',
            'comment' => 'Комментарий',
            'token' => 'Токен',
        ];
    }

    public function getStatistic()
    {
        return $this->hasOne(Statistic::className(), ['domain_id' => 'id']);
    }

    public function getConfig()
    {
        return $this->hasOne(Config::className(), ['domain_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $statistic = new Statistic();
            $statistic->domain_id = $this->id;
            $statistic->save();

            $config = new Config();
            $config->domain_id = $this->id;
            $config->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
