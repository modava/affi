<?php

namespace modava\affiliate\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use modava\affiliate\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `modava\affiliate\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $keyword;
    public $need_to_pay; // Cần trả hoa hồng
    public $has_commission; // Có hoa hồng
    public $paid_commission; // Đã có trả hoa hồng
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'created_at', 'updated_at', 'created_by', 'updated_by', ], 'integer'],
            [['slug', 'full_name', 'phone', 'email', 'face_customer', 'description', 'sex', 'birthday', 'phone', 'keyword', 'need_to_pay'], 'safe'],
            [['need_to_pay', 'has_commission', 'paid_commission'], 'in', 'range' => ['0', '1']]
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
    public function search($params, $forApi = false)
    {
        $query = Customer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if ($forApi) {
            $this->loadFromApi($params);
        } else {
            $this->load($params);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'OR',
            ['like', 'full_name', $this->keyword],
            ['like', 'phone', $this->keyword],
            ['like', 'id_card_number', $this->keyword],
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'partner_id' => $this->partner_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'face_customer', $this->face_customer])
            ->andFilterWhere(['like', 'description', $this->description]);

        if ($this->need_to_pay && $this->need_to_pay == '1') {
            $query->andWhere(['>', 'total_commission_remain', 0]);
        }

        if ($this->has_commission && $this->has_commission == '1') {
            $query->andWhere(['>', 'total_commission', 0]);
        }

        if ($this->paid_commission && $this->paid_commission == '1') {
            $query->andWhere(['>', 'total_commission_paid', 0]);
        }

        return $dataProvider;
    }
}
