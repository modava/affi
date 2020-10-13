<?php

namespace modava\affiliate\models;

use common\helpers\MyHelper;
use common\models\User;
use modava\affiliate\models\search\CustomerPartnerSearch;
use modava\affiliate\models\table\OrderTable;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "affiliate_order".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $coupon_id Mã coupon
 * @property string $pre_total Số tiền trên đơn hàng
 * @property string $discount Số tiền được chiết khấu
 * @property string $final_total Số tiền còn lại
 * @property string $other_discount Số tiền còn lại
 * @property string $description Mô tả
 * @property int $date_create Ngày tạo
 * @property int $status Tình trạng đơn hàng
 * @property string $partner_order_code Mã code order hệ thống partner
 * @property string $partner_customer_id Mã KH hệ thống partner
 * @property number $commision_for_coupon_owner Hoa hồng cho chủ coupon
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Coupon $coupon
 * @property User $createdBy
 * @property User $updatedBy
 */
class Order extends OrderTable
{
    const CHUA_HOAN_THANH = 0;
    const HOAN_THANH = 1;
    const HUY = 2;
    const KE_TOAN_DUYET = 3;
    const DA_THANH_TOAN = 4;
    public $toastr_key = 'affiliate-order';

    public static function getListOrderUsedCoupon($customerId)
    {
        $list = self::find()
            ->joinWith('coupon')
            ->where([Coupon::tableName() . '.customer_id' => $customerId]);

        return $list;
    }

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
                        ActiveRecord::EVENT_BEFORE_INSERT => ['discount'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['discount'],
                    ],
                    'value' => function ($event) {
                        $coupon = $this->coupon;
                        $promotion_type = $coupon->promotion_type;

                        if ($promotion_type === Coupon::DISCOUNT_PERCENT) {
                            $discountValue = ($coupon->promotion_value / 100) * $this->pre_total;
                        } else {
                            $discountValue = $coupon->promotion_value;
                        }

                        return $discountValue;
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['commision_for_coupon_owner'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['commision_for_coupon_owner'],
                    ],
                    'value' => function ($event) {
                        $coupon = $this->coupon;
                        $promotion_type = $coupon->promotion_type;

                        if ($promotion_type === Coupon::DISCOUNT_PERCENT) {
                            $commissionValue = ($coupon->commission_for_owner / 100) * $this->pre_total;
                        } else {
                            $commissionValue = $coupon->commission_for_owner;
                        }

                        return $commissionValue;
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['final_total'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['final_total'],
                    ],
                    'value' => function ($event) {
                        $this->final_total = (float)$this->pre_total - (float)$this->discount - (float)$this->other_discount;

                        return $this->final_total > 0 ? $this->final_total : 0;
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
            [['title', 'slug', 'coupon_id', 'pre_total', 'date_create', 'status',], 'required'],
            [['coupon_id', 'status',], 'integer'],
            [['pre_total', 'discount', 'final_total', 'other_discount', 'commision_for_coupon_owner'], 'number'],
            [['description',], 'string'],
            [['date_create', 'partner_order_code', 'partner_customer_id'], 'safe'],
            [['title', 'slug', 'partner_order_code'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['coupon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coupon::class, 'targetAttribute' => ['coupon_id' => 'id']],
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
            'coupon_id' => Yii::t('backend', 'Coupon ID'),
            'pre_total' => Yii::t('backend', 'Pre Total'),
            'discount' => Yii::t('backend', 'Discount'),
            'final_total' => Yii::t('backend', 'Final Total'),
            'description' => Yii::t('backend', 'Description'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'date_create' => Yii::t('backend', 'Ngày đơn hàng'),
            'status' => Yii::t('backend', 'Tình trạng'),
            'partner_order_code' => Yii::t('backend', 'Mã đơn hàng hệ thống partner'),
            'partner_customer_id' => Yii::t('backend', 'Mã KH hệ thống partner'),
            'other_discount' => Yii::t('backend', 'Giảm giá khác'),
            'commision_for_coupon_owner' => Yii::t('backend', 'Chiết khấu cho chủ Coupon'),
        ];
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

    public function getCoupon()
    {
        return $this->hasOne(Coupon::class, ['id' => 'coupon_id']);
    }

    public function loadFromApi($params)
    {
        $formName = $this->formName();
        $paramsPrepare = [];

        if (array_key_exists('coupon_code', $params)) {
            $coupon = Coupon::checkCoupon($params['coupon_code']);

            if ($coupon) {
                $params['coupon_id'] = $coupon->primaryKey;
            }
        }

        foreach ($params as $k => $v) {
            $paramsPrepare[$formName][$k] = $v;
        }

        return $this->load($paramsPrepare);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->updateCouponUses();
        $this->updateCommissionForCustomer();
        $this->initCustomerPartnerForCache();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     *  Cập nhật giá trị đã sử dụng của coupon
     *  Số lượng đã sử dụng của coupon bằng số lương đơn hàng khác hủy
     */
    public function updateCouponUses()
    {
        $countOrder = self::find()
            ->where(['coupon_id' => $this->coupon_id])
            ->andWhere(['!=', 'status', self::HUY])
            ->count();

        $coupon = Coupon::findOne($this->coupon_id);
        $coupon->quantity_used = $countOrder;
        $coupon->save();
    }

    /*
     * Cập nhật tổng hoa hổng của KH
     * Tổng hoa hổng của KH bằng tổng chiết khấu cho chủ coupon trên các đơn hàng đã được kế toán duyệt
     * */
    public function updateCommissionForCustomer()
    {
        $sumCommission = self::find()
            ->select([self::tableName() . '.commision_for_coupon_owner'])
            ->joinWith(['coupon'])
            ->where([
                'status' => [self::KE_TOAN_DUYET, self::DA_THANH_TOAN],
                Coupon::tableName() . '.customer_id' => $this->coupon->customer_id
            ])->sum('commision_for_coupon_owner');

        $customer = Customer::findOne($this->coupon->customer_id);
        $customer->total_commission = $sumCommission;
        $customer->save();
    }

    public function initCustomerPartnerForCache()
    {
        CustomerPartnerSearch::getCustomerById($this->partner_customer_id);
    }

    public function afterDelete()
    {
        $this->updateCouponUses();
        $this->updateCommissionForCustomer();
        return parent::afterDelete();
    }
}
