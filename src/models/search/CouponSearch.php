<?php

namespace modava\affiliate\models\search;

use modava\affiliate\models\Coupon;
use modava\affiliate\models\Customer;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * CouponSearch represents the model behind the search form of `modava\affiliate\models\Coupon`.
 */
class CouponSearch extends Coupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'quantity', 'customer_id', 'coupon_type_id', 'quantity_used', 'partner_id', 'promotion_type', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'slug', 'coupon_code', 'expired_date', 'description', 'created_at'], 'safe'],
            [['promotion_value'], 'number'],
        ];
    }

//    public function behaviors()
//    {
//        return array_merge(parent::behaviors(), [
//            [
//                'class' => AttributeBehavior::class,
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['created_at']
//                ],
//                'value' => function ($event) {
//                    return strtotime($this->created_at);
//                },
//            ],
//        ]);
//    }

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
        $query = Coupon::find();

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
            'id' => $this->id,
            self::tableName() . '.quantity' => $this->quantity,
            self::tableName() . '.expired_date' => $this->expired_date,
            self::tableName() . '.customer_id' => $this->customer_id,
            self::tableName() . '.coupon_type_id' => $this->coupon_type_id,
            self::tableName() . '.quantity_used' => $this->quantity_used,
            self::tableName() . '.promotion_type' => $this->promotion_type,
            self::tableName() . '.promotion_value' => $this->promotion_value,
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
            ->andFilterWhere(['like', self::tableName() . '.slug', $this->slug])
            ->andFilterWhere(['like', self::tableName() . '.coupon_code', $this->coupon_code])
            ->andFilterWhere(['like', Customer::tableName() . '.partner_id', $this->partner_id])
            ->andFilterWhere(['like', self::tableName() . '.description', $this->description]);

        return $dataProvider;
    }
}
