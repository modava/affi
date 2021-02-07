<?php

namespace modava\affiliate\models\search;

use modava\affiliate\models\Order;
use modava\affiliate\models\Receipt;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReceiptSearch represents the model behind the search form of `modava\affiliate\models\Receipt`.
 */
class ReceiptSearch extends Receipt
{
    public $order;
    public $button;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'status', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['slug', 'title', 'payment_method'], 'safe'],
            [['total'], 'number'],
            [['button'], 'safe'],
            [['order', 'created_at'], 'string'],
            [['receipt_date'], 'string']
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
        $query = Receipt::find();
        
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
            'order_id' => $this->order_id,
            'total' => $this->total,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
        
        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'payment_method', $this->payment_method]);
        $lastDateOfMonth = date('t', time());
        
        if (is_null($this->created_at)) {
            $this->created_at = date('01-m-Y - ' . $lastDateOfMonth . '-m-Y');
        }
        
        if (is_null($this->receipt_date)) {
            $this->receipt_date = date('01-m-Y - ' . $lastDateOfMonth . '-m-Y');
        }
        
        if ($this->receipt_date) {
            $receiptDateArr = explode(' - ', $this->receipt_date);
            $receiptDateFrom = $receiptDateArr[0];
            $receiptDateTo = $receiptDateArr[1];
            $query->andWhere(self::tableName() . '.receipt_date >= :receipt_date_from', [
                ':receipt_date_from' => strtotime($receiptDateFrom),
            ]);
            $query->andWhere(self::tableName() . '.receipt_date <= :receipt_date_to', [
                ':receipt_date_to' => strtotime($receiptDateTo) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }
        
        if ($this->created_at) {
            $createdAtArr = explode(' - ', $this->created_at);
            $createdAtFrom = $createdAtArr[0];
            $createdAtTo = $createdAtArr[1];
            $query->andWhere(self::tableName() . '.created_at >= :created_at_from', [
                ':created_at_from' => strtotime($createdAtFrom),
            ]);
            $query->andWhere(self::tableName() . '.created_at <= :created_at_to', [
                ':created_at_to' => strtotime($createdAtTo) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }
        
        if ($this->order) {
            $query->joinWith('order');
            $query->andFilterWhere(['like', Order::tableName() . '.title', $this->order]);
        }
        
        return $dataProvider;
    }
    
    public function getTotalReceipt($params)
    {
        $query = Receipt::find();
        
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
            'order_id' => $this->order_id,
            'total' => $this->total,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
        
        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'payment_method', $this->payment_method]);
        $lastDateOfMonth = date('t', time());
        
        if (is_null($this->receipt_date)) {
            $this->receipt_date = date('01-m-Y - ' . $lastDateOfMonth . '-m-Y');
        }
        
        if ($this->receipt_date) {
            $receiptDateArr = explode(' - ', $this->receipt_date);
            $receiptDateFrom = $receiptDateArr[0];
            $receiptDateTo = $receiptDateArr[1];
            $query->andWhere(self::tableName() . '.receipt_date >= :receipt_date_from', [
                ':receipt_date_from' => strtotime($receiptDateFrom),
            ]);
            $query->andWhere(self::tableName() . '.receipt_date <= :receipt_date_to', [
                ':receipt_date_to' => strtotime($receiptDateTo) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
            
        }
        
        if ($this->created_at) {
            $createdAtArr = explode(' - ', $this->created_at);
            $createdAtFrom = $createdAtArr[0];
            $createdAtTo = $createdAtArr[1];
            $query->andWhere(self::tableName() . '.created_at >= :created_at_from', [
                ':created_at_from' => strtotime($createdAtFrom),
            ]);
            $query->andWhere(self::tableName() . '.created_at <= :created_at_to', [
                ':created_at_to' => strtotime($createdAtTo) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
            
        }
        
        if ($this->order) {
            $query->joinWith('order');
            $query->andFilterWhere(['like', Order::tableName() . '.title', $this->order]);
        }
        
        $result = [
            'total' => $query->sum('total')
        ];
        return $result;
    }
}
