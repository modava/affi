<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\ReceiptTable;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\helpers\MyHelper;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "affiliate_receipt".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property int $order_id Mã đơn hàng
 * @property string $total Số tiền
 * @property int $status 0: Thanh toán, 1: Đặt cọc, 2: Hoàn cọc
 * @property string $payment_method Phương thức thanh toán
 * @property string $partner_code Phương thức thanh toán
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Order $order
 */
class Receipt extends ReceiptTable
{
    public $toastr_key = 'receipt';

    const STATUS_THANH_TOAN = 0;
    const STATUS_DAT_COC = 1;
    const STATUS_HOAN_COC = 2;

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
            [['slug', 'title', 'order_id', 'total', 'partner_code'], 'required'],
            [['order_id', 'status',], 'integer'],
            [['total'], 'number'],
            [['slug', 'title', 'payment_method'], 'string', 'max' => 255],
            [['slug', 'partner_code'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'slug' => Yii::t('backend', 'Slug'),
            'title' => Yii::t('backend', 'Title'),
            'order_id' => Yii::t('backend', 'Đơn hàng'),
            'total' => Yii::t('backend', 'Số tiền'),
            'status' => Yii::t('backend', 'Tình trạng'),
            'payment_method' => Yii::t('backend', 'Phương thức thanh thoán'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'partner_code' => Yii::t('backend', 'Mã phiếu thanh toán ở hệ thống tích hợp'),
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

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }


    public function loadFromApi($params) {
        $formName = $this->formName();
        $paramsPrepare = [];

        if (array_key_exists('order_code', $params)) {
            $order = Order::findOne(['partner_order_code' => $params['order_code']]);

            if ($order) {
                $params['order_id'] = $order->primaryKey;
            }
        }

        foreach ($params as $k => $v) {
            $paramsPrepare[$formName][$k] = $v;
        }

        return $this->load($paramsPrepare);
    }
}
