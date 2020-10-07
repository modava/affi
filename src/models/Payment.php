<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\models\table\PaymentTable;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\helpers\MyHelper;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Html;

/**
* This is the model class for table "affiliate_payment".
*
    * @property int $id
    * @property string $title Chi cho việc gì
    * @property string $slug
    * @property int $customer_id Khách hàng
    * @property string $amount Số tiền chi
    * @property int $status Tình trạng
    * @property string $description Mô tả
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by
    * @property int $updated_by
    *
            * @property Customer $customer
            * @property User $createdBy
            * @property User $updatedBy
    */
class Payment extends PaymentTable
{
    const STATUS_DRAFT = 1;
    const STATUS_PAID = 2;

    public $toastr_key = 'payment';
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'slug' => [
                    'class' => SluggableBehavior::class,
                    'immutable' => false,
                    'ensureUnique' => true,
                    'value' => function () {
                        return MyHelper::createAlias($this->title);
                    }
                ],
                [
                    'class' => BlameableBehavior::class,
                    'createdByAttribute' => 'created_by',
                    'updatedByAttribute' => 'updated_by',
                ],
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'preserveNonEmptyValues' => false,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                    ],
                ],
            ]
        );
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
			[['title', 'slug', 'customer_id', 'status'], 'required'],
			[['customer_id', 'status'], 'integer'],
			[['amount'], 'number'],
            ['amount', 'validateAmount'],
            [['amount',], 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number'],
			[['description'], 'string'],
			[['title', 'slug'], 'string', 'max' => 255],
			[['slug'], 'unique'],
			[['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
			[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
			[['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
		];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'slug' => Yii::t('backend', 'Slug'),
            'customer_id' => Yii::t('backend', 'Customer ID'),
            'amount' => Yii::t('backend', 'Số tiền'),
            'status' => Yii::t('backend', 'Tình trạng'),
            'description' => Yii::t('backend', 'Description'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function validateAmount() {
        if ($this->customer != null && $this->amount > $this->customer->total_commission_remain && $this->status == self::STATUS_PAID) {
            $this->addError('amount', Yii::t('backend', 'Số tiền không được lớn hơn số tiền còn lại phải trả cho KH'));
        }
    }

    /**
    * Gets query for [[User]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getUserCreated()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
    * Gets query for [[User]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getUserUpdated()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->updateCommissionPaidForCustomer();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        $this->updateCommissionPaidForCustomer();
        return parent::afterDelete();
    }

    /*
     * Cập nhật tổng hoa hổng đã chi trả cho KH
     * Tổng hoa hổng đã chi trả KH bằng tổng tiền của các phiếu chi có tình trạng là đã chi
     * */
    public function updateCommissionPaidForCustomer()
    {
        $total = self::find()
            ->select('amount')
            ->where('status = :status AND customer_id = :customer_id', [':status' => self::STATUS_PAID, 'customer_id' => $this->customer_id])
            ->sum('amount');

        $customer = Customer::findOne($this->customer_id);
        $customer->total_commission_paid = $total;
        $customer->save();
    }

    public function loadFromApi($params)
    {
        $formName = $this->formName();
        $paramsPrepare = [];

        foreach ($params as $k => $v) {
            $paramsPrepare[$formName][$k] = $v;
        }

        return $this->load($paramsPrepare);
    }

    public static function findByCustomer($customerid) {
        return self::find()->where(['customer_id' => $customerid])->all();
    }

    public function getDisplayImages() {
        if (!$this->description) return '';
        $as = '';
        $imgs = json_decode($this->description);

        foreach ($imgs as $img) {
            $imgDOM = Html::img($img, [
                'class' => "img-fluid mx-1 rounded",
                'width' => '100px'
            ]);

            $as = Html::a($imgDOM, $img) . $as;
        }

        return "<div class='light-gallery-container'>{$as}</div>";
    }
}
