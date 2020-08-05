<?php

namespace modava\affiliate\models;

use common\helpers\MyHelper;
use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\CustomerTable;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
* This is the model class for table "affiliate_customer".
*
    * @property int $id
    * @property string $slug
    * @property string $full_name Họ và tên Khách hàng
    * @property string $phone Số điện thoại - Không trùng
    * @property string $email Email khách hàng - không quan tâm trùng
    * @property string $face_customer Link facebook của KH
    * @property int $partner_id Partner tích hợp affiliate
    * @property string $description Mô tả
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by Người gọi
    * @property int $updated_by
    *
            * @property AffiliateCoupon[] $affiliateCoupons
            * @property User $createdBy
            * @property User $updatedBy
            * @property AffiliatePartner $partner
            * @property AffiliateNote[] $affiliateNotes
    */
class Customer extends CustomerTable
{
    public $toastr_key = 'customer';
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
                        return MyHelper::createAlias($this->full_name);
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
			[['full_name', 'phone', 'partner_id',], 'required'],
			[['partner_id',], 'integer'],
			[['description'], 'string'],
			[['full_name', 'email', 'face_customer'], 'string', 'max' => 255],
			[['phone'], 'string', 'max' => 15],
			[['slug'], 'unique'],
			[['phone'], 'unique'],
			[['email'], 'email'],
			[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
			[['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
			[['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::class, 'targetAttribute' => ['partner_id' => 'id']],
		];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'id' => AffiliateModule::t('affiliate', 'ID'),
            'slug' => AffiliateModule::t('affiliate', 'Slug'),
            'full_name' => AffiliateModule::t('affiliate', 'Full Name'),
            'phone' => AffiliateModule::t('affiliate', 'Phone'),
            'email' => AffiliateModule::t('affiliate', 'Email'),
            'face_customer' => AffiliateModule::t('affiliate', 'Face Customer'),
            'partner_id' => AffiliateModule::t('affiliate', 'Partner ID'),
            'description' => AffiliateModule::t('affiliate', 'Description'),
            'created_at' => AffiliateModule::t('affiliate', 'Created At'),
            'updated_at' => AffiliateModule::t('affiliate', 'Updated At'),
            'created_by' => AffiliateModule::t('affiliate', 'Created By'),
            'updated_by' => AffiliateModule::t('affiliate', 'Updated By'),
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

    public function getPartner() {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    /*public function getNotes() {
        return $this->hasMany(Note::class, ['id' => 'customer_id']);
    }

    public function getCoupons() {
        return $this->hasMany(Coupon::class, ['id' => 'customer_id']);
    }*/
}
