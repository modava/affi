<?php

namespace modava\affiliate\models\search;

use modava\affiliate\models\Coupon;
use modava\affiliate\models\Customer;
use modava\affiliate\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form of `modava\affiliate\models\Order`.
 */
class OrderSearch extends Order
{
    public $button;
    public $coupon;
    public $id_customer;
    public $order_date_from;
    public $order_date_to;
    public $date_approval_reception_from;
    public $date_approval_reception_to;
    public $created_at_from;
    public $created_at_to;
    public $coupon_of_sales;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coupon_id', 'updated_at', 'created_by', 'updated_by', 'coupon_of_sales'], 'integer'],
            [['title', 'slug', 'description', 'status', 'created_at', 'date_create', 'date_approval_reception', 'partner_order_code'], 'safe'],
            [['pre_total', 'discount', 'final_total'], 'number'],
            ['coupon', 'string'],
            ['id_customer', 'string'],
            [['order_date_from', 'order_date_to', 'date_approval_reception_from', 'date_approval_reception_to', 'created_at_from', 'created_at_to', 'button'], 'safe'],
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
        // add conditions that should always apply here;
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
            self::tableName() . '.coupon_id' => $this->coupon_id,
            self::tableName() . '.pre_total' => $this->pre_total,
            self::tableName() . '.discount' => $this->discount,
            self::tableName() . '.final_total' => $this->final_total,
            self::tableName() . '.status' => $this->status,
            self::tableName() . '.updated_at' => $this->updated_at,
            self::tableName() . '.created_by' => $this->created_by,
            self::tableName() . '.updated_by' => $this->updated_by,
            self::tableName() . '.partner_order_code' => $this->partner_order_code,
        ]);

        $query->andFilterWhere(['like', self::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', self::tableName() . '.description', $this->description]);

        if (is_null($this->created_at_from) && !$isForApi) {
            $this->created_at_from = date('01-m-Y');
        }

        if ($this->created_at_from) {
            $query->andWhere(self::tableName() . '.created_at >= :created_at_from', [
                ':created_at_from' => strtotime($this->created_at_from),
            ]);
        }

        if ($this->created_at_to) {
            $query->andWhere(self::tableName() . '.created_at <= :created_at_to', [
                ':created_at_to' => strtotime($this->created_at_to) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }

        if ($this->order_date_from) {
            $query->andWhere(self::tableName() . '.date_create >= :order_date_from', [
                ':order_date_from' => strtotime($this->order_date_from),
            ]);
        }

        if ($this->order_date_to) {
            $query->andWhere(self::tableName() . '.date_create <= :order_date_to', [
                ':order_date_to' => strtotime($this->order_date_to) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }

        if ($this->date_approval_reception_from) {
            $query->andWhere(self::tableName() . '.date_approval_reception >= :date_approval_reception_from', [
                ':date_approval_reception_from' => strtotime($this->date_approval_reception_from),
            ]);
        }

        if ($this->date_approval_reception_to) {
            $query->andWhere(self::tableName() . '.date_approval_reception <= :date_approval_reception_to', [
                ':date_approval_reception_to' => strtotime($this->date_approval_reception_to) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }

        if ($customerId || $this->coupon_of_sales) {
            $query->joinWith('coupon');

            if ($customerId) {
                $query->andFilterWhere([Coupon::tableName() . '.customer_id' => $customerId]);
            }
            if ($this->coupon_of_sales) {
                $query->andFilterWhere([Coupon::tableName() . '.commission_for' => $this->coupon_of_sales]);
            }
        }

        if ($this->coupon) {
            $query->joinWith('coupon');
            $query->andFilterWhere(['like', Coupon::tableName() . '.coupon_code', $this->coupon]);
        }

        if ($this->id_customer) {
            $query->joinWith([
                'coupon' => function ($query) {
                    $query->joinWith('customer');
                }
            ]);
            $query->andFilterWhere([Customer::tableName() . '.id' => $this->id_customer]);
        }

        return $dataProvider;
    }

    public function getTotal($params, $customerId = null, $isForApi = false)
    {
        $query = Order::find();
        // add conditions that should always apply here;
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
            Order::tableName() . '.status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'partner_order_code' => $this->partner_order_code,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        if (is_null($this->created_at_from) && !$isForApi) {
            $this->created_at_from = date('01-m-Y');
        }

        if ($this->created_at_from) {
            $query->andWhere(self::tableName() . '.created_at >= :created_at_from', [
                ':created_at_from' => strtotime($this->created_at_from),
            ]);
        }

        if ($this->created_at_to) {
            $query->andWhere(self::tableName() . '.created_at <= :created_at_to', [
                ':created_at_to' => strtotime($this->created_at_to) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }

        if ($this->order_date_from) {
            $query->andWhere(self::tableName() . '.date_create >= :order_date_from', [
                ':order_date_from' => strtotime($this->order_date_from),
            ]);
        }

        if ($this->order_date_to) {
            $query->andWhere(self::tableName() . '.date_create <= :order_date_to', [
                ':order_date_to' => strtotime($this->order_date_to) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }

        if ($this->date_approval_reception_from) {
            $query->andWhere(self::tableName() . '.date_approval_reception >= :date_approval_reception_from', [
                ':date_approval_reception_from' => strtotime($this->date_approval_reception_from),
            ]);
        }

        if ($this->date_approval_reception_to) {
            $query->andWhere(self::tableName() . '.date_approval_reception <= :date_approval_reception_to', [
                ':date_approval_reception_to' => strtotime($this->date_approval_reception_to) + 23 * 60 * 60 + 59 * 60 + 59,
            ]);
        }

        if ($customerId) {
            $query->joinWith('coupon')
                ->andFilterWhere([Coupon::tableName() . '.customer_id' => $customerId]);
        }

        if ($this->coupon) {
            $query->joinWith('coupon');
            $query->andFilterWhere(['like', Coupon::tableName() . '.coupon_code', $this->coupon]);
        }

        if ($this->id_customer) {
            $query->joinWith([
                'coupon' => function ($query) {
                    $query->joinWith('customer');
                }
            ]);
            $query->andFilterWhere([Customer::tableName() . '.id' => $this->id_customer]);
        }

        $sumPreTotal = $query->sum('pre_total');
        $sumDiscount = $query->sum('discount');
        $sumCommision = $query->sum('commision_for_coupon_owner');
        $sumOtherDiscount = $query->sum('other_discount');
        $sumFinalTotal = $query->sum('final_total');
        $result = [
            'sumPreTotal' => $sumPreTotal,
            'sumDiscount' => $sumDiscount,
            'sumCommision' => $sumCommision,
            'sumOtherDiscount' => $sumOtherDiscount,
            'sumFinalTotal' => $sumFinalTotal
        ];

        return $result;
    }

    /**
     * Convert to new array with getArrayColumn() + createColumnsArray() =>
     *      [A] => partner_name
     *      [B] => partner_customer_code
     * Original array field:
     * [
     *      'partner_name' =>  'Khách hàng',
     *      'partner_customer_code' => 'Mã KH',
     * ]
     * Array Alpha
     * [
     *      0 => 'A',
     *      1 => 'B',
     * ]
     */
    public function getArrayColumn()
    {
        //        $alphas = range('B', 'Z');
        $alphas = $this->createColumnsArray('AZ');
        $arr = $this->getlistField();
        $listColumn = $setListColumn = [];
        foreach ($arr as $key => $val) {
            $listColumn[$key] = 1;
        }
        foreach ($listColumn as $key => $value) {
            $setListColumn[] = $key;
        }
        $alphas = array_slice($alphas, 0, count($setListColumn));
        return array_combine($setListColumn, $alphas);
    }

    public function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column) {
                return $columns;
            }
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }

    // A => ZZ https://stackoverflow.com/questions/14278603/php-range-from-a-to-zz

    /**
     *  getlistField() use for getArrayColumn => to create array with alpha column + attribute field such as:
     * [
     *      [A] => partner_name
     *      [B] => partner_customer_code
     * ]
     */
    public function getlistField()
    {
        return [
            'partner_name' => 'Khách hàng',
            'partner_customer_code' => 'Mã KH',
            'title' => 'Đơn hàng',
            'coupon_id' => 'Mã Affiliate',
            'pre_total' => 'Tổng tiền',
            'final_total' => 'Doanh thu',
            'date_create' => 'Từ ngày',
            'date_approval_reception' => 'Đến ngày',
            'partner_receipted' => 'Đã thu',
            'status' => 'Tình trạng',
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'coupon_of_sales' => 'Coupon của sales',
        ]);
    }
}
