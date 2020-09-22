<?php

namespace modava\affiliate\models;

use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\FeedbackTable;
use modava\affiliate\models\table\FeedbackTimeTable;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use common\helpers\MyHelper;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

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
    const UNSATISFIED_TYPE = 0;
    const SATISFIED_TYPE = 1;
    const NORMAL_TYPE = 2;

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
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'slug' => Yii::t('backend', 'Slug'),
            'customer_id' => Yii::t('backend', 'Customer'),
            'unsatisfied_reason_id' => Yii::t('backend', 'Unsatisfied Reason'),
            'feedback_time_id' => Yii::t('backend', 'Feedback Time'),
            'feedback_type' => Yii::t('backend', 'Feedback Type'),
            'description' => Yii::t('backend', 'Description'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'satisfied_feedback' => Yii::t('backend', 'Satisfied Feedback'),
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

    public static function totalFeedbackByTypeAndTime($type)
    {
        $sql = "SELECT affiliate_feedback_time.title AS af_fb_time_title, feedback_type, COUNT(*) AS count
                FROM affiliate_feedback
                INNER JOIN affiliate_feedback_time ON affiliate_feedback_time.id = affiliate_feedback.feedback_time_id
                GROUP BY feedback_type, feedback_time_id
                ORDER BY af_fb_time_title, feedback_type;";

        $allRecord = \Yii::$app->db->createCommand($sql)->queryAll();
        $feedBackTimes = FeedbackTimeTable::getAllRecords();
        $feedBackTypes = Yii::$app->getModule('affiliate')->params['feedback_type'];

        $recordForChart = [];
        $feedBackTimesForChart = [];
        foreach ($feedBackTimes as $feedBackTime) {
            $feedBackTimesForChart[] = $feedBackTime->title;
        }
        $total = 0;

        foreach ($feedBackTypes as $idType => $feedBackType) {
            $data = [];
            foreach ($feedBackTimes as $idTime => $feedBackTime) {
                foreach ($allRecord as $record) {
                    if ($record['af_fb_time_title'] === $feedBackTime->title && $record['feedback_type'] == $idType) {
                        $data[$idTime] = $record['count'];
                        break;
                    } else {
                        $data[$idTime] = 0;
                    }
                }
            }
            $recordForChart[] = [
                'name' => $feedBackType,
                'type' => 'bar',
                'data' => $data
            ];
        }

        return [
            'total' => $total,
            'data' => $recordForChart,
            'color' => \Yii::$app->getModule('affiliate')->params['feedback_type_color'],
            'xaxis_data' => $feedBackTimesForChart,
            'legend' => $feedBackTypes
        ];
    }

    public static function totalFeedbackByType($type)
    {
        $sql = "SELECT feedback_type, COUNT(*) AS count
                FROM affiliate_feedback ";

        switch ($type) {
            case 'year':
                $sql .= "WHERE YEAR(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = YEAR(CURRENT_DATE())
                        GROUP BY feedback_type;";
                break;
            case 'month':
                $sql .= "WHERE MONTH(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) 
                            AND YEAR(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = YEAR(CURRENT_DATE())
                        GROUP BY feedback_type;";
                break;
            case 'week':
                $sql .= "WHERE YEARWEEK(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = YEARWEEK(CURRENT_DATE()) 
                         GROUP BY feedback_type;";
                break;
            default:
                $sql .=  "GROUP BY feedback_type;";
        }

        $allRecord = \Yii::$app->db->createCommand($sql)->queryAll();
        $recordForChart = [];
        $total = 0;

        foreach ($allRecord as $record) {
            $recordForChart[] = [
                'name' => \Yii::$app->getModule('affiliate')->params['feedback_type'][$record['feedback_type']],
                'value' => $record['count']
            ];
            $total += (int) $record['count'];
        }

        return [
            'total' => $total,
            'data' => $recordForChart,
            'color' => \Yii::$app->getModule('affiliate')->params['feedback_type_color'],
        ];
    }
}
