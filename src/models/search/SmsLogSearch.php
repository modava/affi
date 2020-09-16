<?php

namespace modava\affiliate\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use modava\affiliate\models\SmsLog;

/**
 * SmsLogSearch represents the model behind the search form of `modava\affiliate\models\SmsLog`.
 */
class SmsLogSearch extends SmsLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'created_at', 'created_by'], 'integer'],
            [['message', 'to_number', 'status', 'response_log', 'request_log'], 'safe'],
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
        $query = SmsLog::find();

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
            'customer_id' => $this->customer_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'to_number', $this->to_number])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'response_log', $this->response_log])
            ->andFilterWhere(['like', 'request_log', $this->request_log]);

        return $dataProvider;
    }
}
