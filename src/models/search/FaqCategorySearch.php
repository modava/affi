<?php

namespace modava\affiliate\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use modava\affiliate\models\FaqCategory;

/**
 * FaqCategorySearch represents the model behind the search form of `modava\affiliate\models\FaqCategory`.
 */
class FaqCategorySearch extends FaqCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'publish', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'slug', 'description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = FaqCategory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'publish' => $this->publish,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
