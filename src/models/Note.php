<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\NoteTable;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\helpers\MyHelper;
use yii\db\ActiveRecord;
use Yii;

/**
* This is the model class for table "note".
*
    * @property int $id
    * @property string $title
    * @property string $slug
    * @property int $partner_id Partner tích hợp affiliate
    * @property int $customer_id Mã khách hàng
    * @property string $call_time Thời gian gọi
    * @property string $recall_time Thời gian gọi lại
    * @property string $description Mô tả
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by Người gọi
    * @property int $updated_by
    *
            * @property User $createdBy
            * @property User $updatedBy
            * @property Partner $partner
    */
class Note extends NoteTable
{
    public $toastr_key = 'note';
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
                        ActiveRecord::EVENT_BEFORE_INSERT => ['call_time', ],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['call_time',],
                    ],
                    'value' => function ($event) {
                        return date('Y-m-d H:i:s', strtotime($this->call_time));
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['recall_time'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['recall_time'],
                    ],
                    'value' => function ($event) {
                        return date('Y-m-d H:i:s', strtotime($this->recall_time));
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
			[['title', 'slug', 'partner_id', 'customer_id', 'call_time', 'recall_time',], 'required'],
			[['partner_id', 'customer_id',], 'integer'],
			[['call_time', 'recall_time'], 'safe'],
			[['description'], 'string'],
			[['title', 'slug'], 'string', 'max' => 255],
			[['slug'], 'unique'],
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
            'title' => AffiliateModule::t('affiliate', 'Title'),
            'slug' => AffiliateModule::t('affiliate', 'Slug'),
            'partner_id' => AffiliateModule::t('affiliate', 'Partner ID'),
            'customer_id' => AffiliateModule::t('affiliate', 'Customer ID'),
            'call_time' => AffiliateModule::t('affiliate', 'Call Time'),
            'recall_time' => AffiliateModule::t('affiliate', 'Recall Time'),
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
}
