<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\CouponTable;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\helpers\MyHelper;
use yii\db\ActiveRecord;
use Yii;

/**
* This is the model class for table "coupon".
*
    * @property int $id
    * @property string $title
    * @property string $slug
    * @property string $coupon_code
    * @property int $quantity
    * @property string $expired_date
    * @property string $description
    * @property int $customer_id
    * @property int $coupon_type_id
    * @property int $quantity_used
    * @property int $promotion_type
    * @property string $promotion_value
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by
    * @property int $updated_by
    *
            * @property CouponType $couponType
    */
class Coupon extends CouponTable
{
    public $toastr_key = 'coupon';

    const DISCOUNT_AMOUNT = 1;
    const DISCOUNT_PERCENT = 2;

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
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['expired_date'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['expired_date'],
                    ],
                    'value' => function ($event) {
                        return date('Y-m-d H:i:s', strtotime($this->expired_date));
                    },
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
			[['title', 'slug','coupon_code', 'quantity', 'customer_id', 'coupon_type_id', 'promotion_type', 'promotion_value',], 'required'],
			[['quantity', 'customer_id', 'coupon_type_id', 'quantity_used', 'promotion_type',], 'integer'],
            ['quantity_used', 'validateQuantityUsed'],
            [['quantity', 'promotion_value'], 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number'],
			[['expired_date'], 'safe'],
			[['description'], 'string'],
			[['promotion_value'], 'number'],
			[['title', 'slug', 'coupon_code'], 'string', 'max' => 255],
			[['slug'], 'unique'],
			[['coupon_code'], 'unique'],
			[['coupon_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CouponType::class, 'targetAttribute' => ['coupon_type_id' => 'id']],
            ['promotion_value', 'compare', 'compareValue' => 100, 'operator' => '<=', 'type' => 'number', 'when' => function ($model) {
                return $model->promotion_type == self::DISCOUNT_PERCENT;
                    }, 'whenClient' => "function (attribute, value) {
                return $('#promotion-type').val() == " . self::DISCOUNT_PERCENT . ";
            }"]
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
            'coupon_code' => Yii::t('backend', 'Coupon Code'),
            'quantity' => Yii::t('backend', 'Quantity'),
            'expired_date' => Yii::t('backend', 'Expired Date'),
            'description' => Yii::t('backend', 'Description'),
            'customer_id' => Yii::t('backend', 'Customer ID'),
            'coupon_type_id' => Yii::t('backend', 'Coupon Type ID'),
            'quantity_used' => Yii::t('backend', 'Quantity Used'),
            'promotion_type' => Yii::t('backend', 'Promotion Type'),
            'promotion_value' => Yii::t('backend', 'Promotion Value'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    function validateQuantityUsed () {
        if((int) $this->quantity_used > (int) $this->quantity){
            $this->addError('quantity_used',Yii::t('backend', 'Quantity Used must be less than or equal quantity'));
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

    public function getCouponType() {
        return $this->hasOne(CouponType::class, ['id' => 'coupon_type_id']);
    }

    public function getCustomer() {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public static function countByCustomer ($customerId) {
        return (int) self::find()
            ->where(['customer_id' => $customerId])
            ->count();
    }

    public static function checkCoupon($code) {
        return self::find()
            ->where(['coupon_code' => $code])
            ->andWhere('now() <= expired_date')
            ->andWhere('quantity_used < quantity')
            ->one();
    }
}
