<?php

namespace modava\affiliate\models;

use common\models\User;
use GuzzleHttp\Exception\GuzzleException;
use modava\affiliate\AffiliateModule;
use modava\affiliate\helpers\Utils;
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
 * @property int $customer_id Mã khách hàng
 * @property string $call_time Thời gian gọi
 * @property string $recall_time Thời gian gọi lại
 * @property string $description Mô tả
 * @property string $partner_id Hệ thống partner
 * @property string $note_type Loại note
 * @property string $partner_note_id note ở hệ thống partner
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by Người gọi
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Note extends NoteTable
{
    public $toastr_key = 'note';

    const NOTE_TYPE_SELF = 0;
    const NOTE_TYPE_PARTNER = 1;

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
                        ActiveRecord::EVENT_BEFORE_INSERT => ['call_time'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['call_time'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateTimeToDBFormat($this->call_time);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['recall_time'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['recall_time'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateTimeToDBFormat($this->recall_time);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['partner_note_id'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['partner_note_id'],
                    ],
                    'value' => function ($event) {
                        // Nếu note_type là hệ thống này thì return null
                        if ($this->note_type != self::NOTE_TYPE_PARTNER) {
                            return null;
                        }

                        // Nếu đã có id thì return id
                        if ($this->partner_note_id) {
                            return $this->partner_note_id;
                        }

                        // Nếu chưa có id:
                        // -- Gọi API về Dashboard:
                        $result = $this->saveNoteToPartner();
                        if ($result['success']) {
                            return $result['data'];
                        }

                        if (!Yii::$app->request->isAjax) {
                            Yii::$app->session->setFlash('toastr-' . $this->toastr_key . '-form', [
                                'title' => 'Thông báo',
                                'text' => AffiliateModule::t('affiliate', 'Error connection was throw, please contact to IT to check this issue'),
                                'type' => 'danger'
                            ]);
                        }

                        Yii::warning($result['error']);

                        $event->isValid = false;
                        return null;
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['partner_id'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['partner_id'],
                    ],
                    'value' => function ($event) {
                        if ($this->note_type == 1) {
                            return $this->partner_id;
                        }

                        return null;
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
            [['title', 'slug', 'customer_id', 'call_time', 'note_type'], 'required'],
            [['customer_id',], 'integer'],
            [
                ['partner_id',],
                'required',
                'when' => function () {
                    return $this->note_type == 1;
                },
                'whenClient' => "function() {
			    return conditionEffect = $('#note-note_type').val() === '1';
			}"
            ],
            [['call_time', 'recall_time'], 'safe'],
            [['description'], 'string'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [
                ['created_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['created_by' => 'id']
            ],
            [
                ['updated_by'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['updated_by' => 'id']
            ],
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
            'customer_id' => AffiliateModule::t('affiliate', 'Customer ID'),
            'call_time' => AffiliateModule::t('affiliate', 'Call Time'),
            'recall_time' => AffiliateModule::t('affiliate', 'Recall Time'),
            'description' => AffiliateModule::t('affiliate', 'Description'),
            'created_at' => AffiliateModule::t('affiliate', 'Created At'),
            'updated_at' => AffiliateModule::t('affiliate', 'Updated At'),
            'created_by' => AffiliateModule::t('affiliate', 'Created By'),
            'updated_by' => AffiliateModule::t('affiliate', 'Updated By'),
            'note_type' => AffiliateModule::t('affiliate', 'Note Type'),
            'partner_id' => AffiliateModule::t('affiliate', 'Partner Id'),
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

    public function getPartner()
    {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    public static function countByCustomer($customerId)
    {
        return (int)self::find()
            ->where(['customer_id' => $customerId])
            ->count();
    }

    public function saveNoteToPartner()
    {
        $dashboardMyAurisConfig = Yii::$app->getModule('affiliate')->params['myauris_config'];
        $url = $dashboardMyAurisConfig['url_end_point'] . $dashboardMyAurisConfig['endpoint']['create_note'];

        // Post data to MyAuris
        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('POST', $url, [
                'headers' => Yii::$app->getModule('affiliate')->params['myauris_config']['headers'],
                'form_params' => [
                    'customer_id' => $this->customer->partner_customer_id,
                    'note' => $this->description
                ]
            ]);

            $result = \GuzzleHttp\json_decode($res->getBody(), true);

            if (isset($result['code']) && $result['code'] == 200) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'error' => \GuzzleHttp\json_encode($result)
            ];
        } catch (GuzzleException $exception) {
            return [
                'success' => false,
                'error' => $exception->getMessage()
            ];
        }
    }
}
