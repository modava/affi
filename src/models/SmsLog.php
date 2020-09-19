<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\models\table\SmsLogTable;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "affiliate_sms_log".
 *
 * @property int $id
 * @property string $message
 * @property string $to_number
 * @property int $customer_id
 * @property string $status
 * @property string $response_log
 * @property string $request_log
 * @property int $created_at Thời gian gửi
 * @property int $created_by
 *
 * @property User $createdBy
 * @property Customer $customer
 */
class SmsLog extends SmsLogTable
{
    public $toastr_key = 'sms-log';

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 2;

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'preserveNonEmptyValues' => false,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_at',],
                    ],
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_by']
                    ],
                    'value' => Yii::$app->user->id
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
            [['message', 'to_number',], 'required'],
            [['message', 'response_log', 'request_log'], 'string'],
            [['customer_id', 'created_at', 'created_by'], 'integer'],
            [['to_number'], 'string', 'max' => 20],
            [['status'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'message' => Yii::t('backend', 'Message'),
            'to_number' => Yii::t('backend', 'Gửi Đến SĐT'),
            'customer_id' => Yii::t('backend', 'Gửi Đến KH'),
            'status' => Yii::t('backend', 'Status'),
            'response_log' => Yii::t('backend', 'Response Log'),
            'request_log' => Yii::t('backend', 'Request Log'),
            'created_at' => Yii::t('backend', 'Created At'),
            'created_by' => Yii::t('backend', 'Created By'),
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

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

}
