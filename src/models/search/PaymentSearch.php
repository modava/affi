<?php

namespace modava\affiliate\models\search;

use modava\affiliate\models\Customer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use modava\affiliate\models\Payment;

/**
 * PaymentSearch represents the model behind the search form of `modava\affiliate\models\Payment`.
 */
class PaymentSearch extends Payment
{
    public $partner_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'created_by', 'updated_at', 'updated_by', 'partner_id', 'status'], 'integer'],
            [['title', 'slug', 'description', 'created_at'], 'safe'],
            [['amount'], 'number'],
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
        $query = Payment::find();

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

        $query->joinWith('customer');

        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName() . '.id' => $this->id,
            self::tableName() . '.customer_id' => $this->customer_id,
            self::tableName() . '.amount' => $this->amount,
            self::tableName() . '.status' => $this->status,
            self::tableName() . '.updated_at' => $this->updated_at,
            self::tableName() . '.created_by' => $this->created_by,
            self::tableName() . '.updated_by' => $this->updated_by,
        ]);

        if ($this->created_at) {
            $query->andWhere('FROM_UNIXTIME(' . self::tableName() . '.created_at' . ', "%d-%m-%Y" ) = :created_at', [
                ':created_at' => $this->created_at
            ]);
        }

        $query->andFilterWhere(['like', self::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', Customer::tableName() . '.partner_id', $this->partner_id])
            ->andFilterWhere(['like', self::tableName() . '.description', $this->description]);

        return $dataProvider;
    }
}
