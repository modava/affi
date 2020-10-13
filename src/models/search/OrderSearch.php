<?php

namespace modava\affiliate\models\search;

use modava\affiliate\models\Coupon;
use modava\affiliate\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form of `modava\affiliate\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coupon_id', 'created_at', 'updated_at', 'created_by', 'updated_by', ], 'integer'],
            [['title', 'slug', 'description', 'status'], 'safe'],
            [['pre_total', 'discount', 'final_total'], 'number'],
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
    public function search($params, $customerId = null, $isForApi = false)
    {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if ($isForApi) {
            $this->loadFromApi($params);
        } else {
            $this->load($params);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'coupon_id' => $this->coupon_id,
            'pre_total' => $this->pre_total,
            'discount' => $this->discount,
            'final_total' => $this->final_total,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'description', $this->description]);

        if ($customerId) {
            $query->joinWith('coupon')
                ->andFilterWhere([Coupon::tableName() . '.customer_id' => $customerId]);
        }

        return $dataProvider;
    }
}
