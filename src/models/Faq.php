<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\FaqTable;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\helpers\MyHelper;
use yii\db\ActiveRecord;
use Yii;

/**
* This is the model class for table "affiliate_faq".
*
    * @property int $id
    * @property string $title
    * @property string $slug
    * @property string $content Câu trả lời
    * @property string $short_content Câu trả lời ngắn
    * @property int $publish 0: Không hiển thị, 1: Hiển thị
    * @property int $faq_category_id
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by
    * @property int $updated_by
    *
            * @property User $createdBy
            * @property FaqCategory $faqCategory
            * @property User $updatedBy
    */
class Faq extends FaqTable
{
    public $toastr_key = 'faq';
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
			[['title', 'faq_category_id',], 'required'],
			[['content'], 'string'],
			[['publish', 'faq_category_id',], 'integer'],
			[['title', 'slug', 'short_content'], 'string', 'max' => 255],
			[['slug'], 'unique'],
			[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
			[['faq_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqCategory::class, 'targetAttribute' => ['faq_category_id' => 'id']],
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
            'title' => AffiliateModule::t('affiliate', 'Question'),
            'slug' => AffiliateModule::t('affiliate', 'Slug'),
            'content' => AffiliateModule::t('affiliate', 'Answer'),
            'short_content' => AffiliateModule::t('affiliate', 'Short Answer'),
            'publish' => AffiliateModule::t('affiliate', 'Publish'),
            'faq_category_id' => AffiliateModule::t('affiliate', 'Faq Category ID'),
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

    public function getFaqCategory() {
        return $this->hasOne(FaqCategory::class, ['id' => 'faq_category_id']);
    }
}
