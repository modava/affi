<?php

namespace modava\affiliate\models\search;

use modava\affiliate\models\Customer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use modava\affiliate\models\Note;

/**
 * NoteSearch represents the model behind the search form of `modava\affiliate\models\Note`.
 */
class NoteSearch extends Note
{
    public $recall_time_start;
    public $recall_time_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'updated_at', 'created_by', 'updated_by', 'partner_id', 'is_recall'], 'integer'],
            [['title', 'slug', 'call_time', 'recall_time', 'description', 'created_at'], 'safe'],
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
        $query = Note::find();

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
            'customer_id' => $this->customer_id,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'is_recall' => $this->is_recall,
        ]);

        if ($this->created_at) {
            $query->andWhere('FROM_UNIXTIME(' . self::tableName() . '.created_at' . ', "%d-%m-%Y" ) = :created_at', [
                ':created_at' => $this->created_at
            ]);
        }

        if ($this->recall_time) {
            $query->andWhere('DATE(recall_time) = :recall_time', [
                ':recall_time' => date('Y-m-d', strtotime($this->recall_time))
            ]);
        }

        if ($this->call_time) {
            $query->andWhere('DATE(call_time) = :call_time', [
                ':call_time' => date('Y-m-d', strtotime($this->call_time))
            ]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', Customer::tableName() . '.partner_id', $this->partner_id])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
