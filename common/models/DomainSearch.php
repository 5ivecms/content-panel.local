<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Domain;

/**
 * DomainSearch represents the model behind the search form of `common\models\Domain`.
 */
class DomainSearch extends Domain
{
    public $countArticles;
    public $countKeywords;
    public $countNewKeywords;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['domain', 'comment', 'countArticles', 'countKeywords', 'countNewKeywords'], 'safe'],
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
        $query = Domain::find();
        $query->joinWith(['statistic']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['countArticles'] = [
            'asc' => [Statistic::tableName() . '.countArticles' => SORT_ASC],
            'desc' => [Statistic::tableName() . '.countArticles' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['countKeywords'] = [
            'asc' => [Statistic::tableName() . '.countKeywords' => SORT_ASC],
            'desc' => [Statistic::tableName() . '.countKeywords' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['countNewKeywords'] = [
            'asc' => [Statistic::tableName() . '.countNewKeywords' => SORT_ASC],
            'desc' => [Statistic::tableName() . '.countNewKeywords' => SORT_DESC],
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
        ]);

        $query->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', Statistic::tableName() . '.countVideos', $this->countArticles])
            ->andFilterWhere(['like', Statistic::tableName() . '.countKeywords', $this->countKeywords])
            ->andFilterWhere(['like', Statistic::tableName() . '.countNewKeywords', $this->countNewKeywords])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
