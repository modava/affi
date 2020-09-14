<?php

namespace modava\affiliate\models;

use common\models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\CouponTable;
use modava\website\models\table\KeyValueTable;
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
 * @property int $count_sms_sent
 * @property int $min_discount giảm giá tối thiểu
 * @property int $max_discount giẩm giá tối đa
 * @property int $commission_for_owner hoa hồng cho chủ coupon
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
            [['title', 'slug', 'coupon_code', 'quantity', 'customer_id', 'coupon_type_id', 'promotion_type', 'promotion_value',], 'required'],
            [['quantity', 'customer_id', 'coupon_type_id', 'quantity_used', 'promotion_type', 'count_sms_sent'], 'integer'],
            [['quantity', 'promotion_value'], 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number'],
            [['max_discount', 'commission_for_owner'], 'number'],
            [['expired_date'], 'safe'],
            [['description'], 'string'],
            [['promotion_value', 'min_discount', 'max_discount'], 'number'],
            [['title', 'slug', 'coupon_code'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['coupon_code'], 'unique'],
            [['coupon_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CouponType::class, 'targetAttribute' => ['coupon_type_id' => 'id']],
            ['quantity_used', 'compare', 'compareAttribute' => 'quantity', 'operator' => '<=', 'type' => 'number'],
            ['promotion_value', 'compare', 'compareAttribute' => 'max_discount', 'operator' => '<=', 'type' => 'number', 'when' => function ($model) {
                return $model->promotion_type == self::DISCOUNT_PERCENT;
            }, 'whenClient' => "function (attribute, value) {
                return $('#promotion-type').val() == " . self::DISCOUNT_PERCENT . ";
            }"],
            ['promotion_value', 'compare', 'compareAttribute' => 'min_discount', 'operator' => '>=', 'type' => 'number', 'when' => function ($model) {
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
            'count_sms_sent' => Yii::t('backend', 'Số lần gửi SMS'),
            'max_discount' => Yii::t('backend', 'Chiết khấu tối đa'),
            'commission_for_owner' => Yii::t('backend', 'Chiết khấu cho chủ Coupon'),
            'min_discount' => Yii::t('backend', 'Chiết khấu tối thiểu'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function beforeSave($insert)
    {
        $this->commission_for_owner = $this->max_discount - $this->promotion_value;
        return parent::beforeSave($insert);
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

    public function getCouponType()
    {
        return $this->hasOne(CouponType::class, ['id' => 'coupon_type_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public static function countByCustomer($customerId)
    {
        return (int)self::find()
            ->where(['customer_id' => $customerId])
            ->count();
    }

    public static function checkCoupon($code)
    {
        return self::find()
            ->where(['coupon_code' => $code])
            ->andWhere('now() <= expired_date')
            ->andWhere('quantity_used < quantity')
            ->one();
    }

    public function couponCanUse() {
        if ($this->quantity_used >= $this->quantity) return false;
        if (date('Y-m-d h:i:s') > $this->expired_date) return false;
        return true;
    }

    public function sendSmsToCustomer()
    {
        if ($this->count_sms_sent >= 3) {
            $this->addError('count_sms_sent', Yii::t('backend', 'Quá số lần gửi tin nhắn'));
            return false;
        }

        $myauris_config = \Yii::$app->getModule('affiliate')->params['myauris_config'];
        $url = $myauris_config['url_end_point'] . $myauris_config['endpoint']['send_sms_coupon'];

        $arrayName = explode(' ', $this->customer->full_name);

        $client = new Client();
        $params = [
            'phone' => $this->customer->phone,
            'promotions_code' => $this->coupon_code,
            'promotions_name' => $this->title,
            'promotions_expired' => Yii::$app->formatter->asDatetime($this->expired_date),
            'name' => array_pop($arrayName),
            'promotions_qty' => $this->quantity,
        ];

        try {
            $res = $client->request('POST', $url, [
                'headers' => Yii::$app->getModule('affiliate')->params['myauris_config']['headers'],
                'form_params' => $params
            ]);

            $response = \GuzzleHttp\json_decode($res->getBody(), true);

            if ($res->getStatusCode() == 200 && $response['code'] == 200) {
                $this->count_sms_sent = $this->count_sms_sent + 1;
                $this->save();
                return true;
            }

            if (array_key_exists('msg', $response)) {
                $this->addError('count_sms_sent', $response['msg']);
            } else {
                Yii::warning($response);
                $this->addError('count_sms_sent', Yii::t('backend', 'Đã có lỗi xảy ra'));
            }
            return false;
        } catch (GuzzleException $exception) {
            $this->addError('count_sms_sent', Yii::t('backend', $exception->getMessage()));
            return false;
        }
    }
}
