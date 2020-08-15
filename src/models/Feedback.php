<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\FeedbackTable;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\helpers\MyHelper;
use yii\db\ActiveRecord;
use Yii;

/**
* This is the model class for table "affiliate_feedback".
*
    * @property int $id
    * @property string $title
    * @property string $slug
    * @property int $customer_id Khách hàng
    * @property int $unsatisfied_reason_id Lý do không hài lòng
    * @property int $feedback_time_id Thời gian Feedback: 1. Tháng, 3. Tháng, 6. Tháng ...
    * @property int $feedback_type 0: Không hài lòng, 1: Hài lòng
    * @property string $description Mô tả
    * @property string $satisfied_feedback Feedback hài lòng
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by
    * @property int $updated_by
    *
            * @property Customer $customer
            * @property FeedbackTime $feedbackTime
            * @property UnsatisfiedReason $unsatisfiedReason
            * @property User $createdBy
            * @property User $updatedBy
    */
class Feedback extends FeedbackTable
{
    public $toastr_key = 'feedback';
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
			[['title', 'slug', 'customer_id', 'feedback_time_id', 'feedback_type',], 'required'],
			[['customer_id', 'unsatisfied_reason_id', 'feedback_time_id', 'feedback_type',], 'integer'],
			[['description', 'satisfied_feedback'], 'string'],
			[['title', 'slug'], 'string', 'max' => 255],
			[['slug'], 'unique'],
            [
                ['unsatisfied_reason_id',],
                'required',
                'when' => function () {
                    return $this->feedback_type == 0;
                },
                'whenClient' => "function() {
			    return $('#feedback-feedback_type').val() === '0';
			}"
            ],
            [
                ['satisfied_feedback',],
                'required',
                'when' => function () {
                    return $this->feedback_type == 1;
                },
                'whenClient' => "function() {
			    return $('#feedback-feedback_type').val() === '1';
			}"
            ],
			[['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
			[['feedback_time_id'], 'exist', 'skipOnError' => true, 'targetClass' => FeedbackTime::class, 'targetAttribute' => ['feedback_time_id' => 'id']],
			[['unsatisfied_reason_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnsatisfiedReason::class, 'targetAttribute' => ['unsatisfied_reason_id' => 'id']],
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
            'id' => AffiliateModule::t('affiliate', 'ID'),
            'title' => AffiliateModule::t('affiliate', 'Title'),
            'slug' => AffiliateModule::t('affiliate', 'Slug'),
            'customer_id' => AffiliateModule::t('affiliate', 'Customer'),
            'unsatisfied_reason_id' => AffiliateModule::t('affiliate', 'Unsatisfied Reason'),
            'feedback_time_id' => AffiliateModule::t('affiliate', 'Feedback Time'),
            'feedback_type' => AffiliateModule::t('affiliate', 'Feedback Type'),
            'description' => AffiliateModule::t('affiliate', 'Description'),
            'created_at' => AffiliateModule::t('affiliate', 'Created At'),
            'updated_at' => AffiliateModule::t('affiliate', 'Updated At'),
            'created_by' => AffiliateModule::t('affiliate', 'Created By'),
            'updated_by' => AffiliateModule::t('affiliate', 'Updated By'),
            'satisfied_feedback' => AffiliateModule::t('affiliate', 'Satisfied Feedback'),
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

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function getUnsatisfiedReason()
    {
        return $this->hasOne(UnsatisfiedReason::class, ['id' => 'unsatisfied_reason_id']);
    }

    public function getFeedbackTime()
    {
        return $this->hasOne(FeedbackTime::class, ['id' => 'feedback_time_id']);
    }

    public static function countByCustomer($customerId)
    {
        return (int)self::find()
            ->where(['customer_id' => $customerId])
            ->count();
    }
}
