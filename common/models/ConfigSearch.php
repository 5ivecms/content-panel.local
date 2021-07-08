<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Config;

/**
 * ConfigSearch represents the model behind the search form of `common\models\Config`.
 */
class ConfigSearch extends Config
{
    public $domainName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'domain_id', 'cron_keywords_enabled', 'cron_keywords_limit'], 'integer'],
            [['domainName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Config::find();
        $query->joinWith(['domain']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['domainName'] = [
            'asc' => [Domain::tableName() . '.domain' => SORT_ASC],
            'desc' => [Domain::tableName() . '.domain' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'domain_id' => $this->domain_id,
            'cron_keywords_enabled' => $this->cron_keywords_enabled,
            'cron_keywords_limit' => $this->cron_keywords_limit,
        ]);

        $query->andFilterWhere(['like', Domain::tableName() . '.domain', $this->domainName]);

        return $dataProvider;
    }
}
