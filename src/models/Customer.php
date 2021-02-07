<?php

namespace modava\affiliate\models;

use common\helpers\MyHelper;
use common\models\User;
use modava\affiliate\helpers\Utils;
use modava\affiliate\models\search\PartnerSearch;
use modava\affiliate\models\table\CustomerTable;
use modava\location\models\LocationCountry;
use modava\location\models\LocationDistrict;
use modava\location\models\LocationProvince;
use modava\location\models\LocationWard;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

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
 * @property int $partner_customer_id id Customer trên hệ thống partner
 * @property string $description Mô tả
 * @property int $status Tình trạng
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by Người gọi
 * @property int $updated_by
 * @property int $sex Giới tính
 * @property int $total_commission Tổng tiền hoa hồng của KH
 * @property int $total_commission_paid Tổng tiền hoa hồng chi trả cho KH
 * @property int $total_commission_remain Tổng tiền hoa hồng còn lại
 * @property date $birthday Ngày sinh
 * @property date $date_checkin ** Không sử dụng **
 * @property date $date_accept_do_service ** Không sử dụng **
 * @property string $bank_name Tên ngân hàng
 * @property string $bank_branch Tài khoản ngân hàng
 * @property string $bank_customer_id Số tài khoản ngân hàng
 * @property string $id_card_number CMND CTCD
 * @property int $payment_type Phương thức chuyển khoản
 * @property int place_of_issue Nơi cấp CMND/CCCD
 * @property int date_of_issue Ngày cấp CMND/CCCD
 *
 * @property Coupon[] $affiliateCoupons
 * @property User $createdBy
 * @property User $updatedBy
 * @property Partner $partner
 * @property Note[] $affiliateNotes
 */
class Customer extends CustomerTable
{
    /**
     * Đang làm dịch vụ
     */
    const STATUS_DANG_LAM_DICH_VU = 0;
    /**
     * Đã hoàn thành dịch vụ
     */
    const STATUS_HOAN_THANH_DICH_VU = 1;
    /**
     * Thanh toán chuyển khoản
     */
    const PAYMENT_TYPE_TRANSFER = 1;
    /**
     * Thanh toán tiền mặt
     */
    const PAYMENT_TYPE_CASH = 2;
    public $toastr_key = 'customer';

    /**
     * Tổng KH chuyển đổi theo type
     * @param $type
     * @return array
     * @throws \yii\db\Exception
     */
    public static function totalConvert($type)
    {
        $sql = "SELECT status, COUNT(*) AS count
                FROM `affiliate_customer`";

        switch ($type) {
            case 'year':
                $sql .= "WHERE YEAR(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = YEAR(CURRENT_DATE())
                        GROUP BY status;";
                break;
            case 'month':
                $sql .= "WHERE MONTH(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) 
                            AND YEAR(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = YEAR(CURRENT_DATE())
                        GROUP BY status;";
                break;
            case 'week':
                $sql .= "WHERE YEARWEEK(FROM_UNIXTIME(created_at, '%Y-%m-%d')) = YEARWEEK(CURRENT_DATE()) 
                         GROUP BY status;";
                break;
        }

        $allRecord = \Yii::$app->db->createCommand($sql)->queryAll();
        $recordForChart = [];
        $total = 0;

        foreach ($allRecord as $record) {
            $recordForChart[] = [
                'name' => \Yii::$app->getModule('affiliate')->params['customer_status'][$record['status']],
                'value' => $record['count']
            ];
            $total += (int)$record['count'];
        }

        return [
            'total' => $total,
            'data' => $recordForChart,
            'color' => \Yii::$app->getModule('affiliate')->params['customer_status_color'],
        ];
    }

    /**
     * Lấy danh sách KH theo keyword
     * @param $keyWord
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getCustomerByKeyWord($keyWord)
    {
        $sql = 'SELECT `id`, concat(full_name, " - ", phone) AS `text` FROM `affiliate_customer` WHERE full_name LIKE :q OR phone LIKE :q';

        $data = Yii::$app->db->createCommand($sql, [':q' => "%{$keyWord}%"])->queryAll();

        return [
            'results' => $data
        ];
    }

    /**
     * Danh sách khách hàng cần chuyển khoản HH
     * @return array|ActiveRecord[]
     */
    public static function getCustomerForPay()
    {
        return self::find()->where('total_commission_remain > 0')->all();
    }

    /**
     * Tổng doanh thu khách hàng kiếm được
     * @param $customerId
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getTotalRevenueByCustomer($customerId)
    {
        $sql = "SELECT SUM(afo.final_total) AS revenue, FROM_UNIXTIME(afo.created_at, '%Y-%m') AS created_at_y_m
                FROM affiliate_order afo
                INNER JOIN affiliate_coupon afcp ON afo.coupon_id = afcp.id
                WHERE afo.status = 3 AND afcp.customer_id = :id
                GROUP BY created_at_y_m;";

        $data = Yii::$app->db->createCommand($sql, [':id' => $customerId])->queryAll();

        return $data;
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
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['birthday'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['birthday'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateToDBFormat($this->birthday);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['date_of_issue'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['date_of_issue'],
                    ],
                    'value' => function ($event) {
                        if (is_numeric($this->date_of_issue)) return $this->date_of_issue;
                        return strtotime($this->date_of_issue);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['date_checkin'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['date_checkin'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateToDBFormat($this->date_checkin);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['date_accept_do_service'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['date_accept_do_service'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateToDBFormat($this->date_accept_do_service);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['total_commission_remain'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['total_commission_remain'],
                    ],
                    'value' => function ($event) {
                        return $this->total_commission - $this->total_commission_paid;
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
        $myAurisPartner = PartnerSearch::getRecordBySlug('dashboard-myauris');

        $vallidatePartnerCustomerId = [
            'when' => function () use ($myAurisPartner) {
                return $this->partner_id == $myAurisPartner->id;
            },
            'whenClient' => "function() {
			    return $('#partner-id').val() === '{$myAurisPartner->id}';
			}"
        ];

        return [
            [['full_name', 'phone', 'partner_id', 'status'], 'required'],
            [['partner_id', 'sex', 'partner_customer_id', 'country_id', 'province_id', 'district_id', 'ward_id', 'payment_type'], 'integer'],
            [['description', 'address'], 'string'],
            [['full_name', 'email', 'face_customer', 'bank_customer_id', 'bank_branch', 'bank_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['id_card_number'], 'string', 'max' => 20],
            [
                ['partner_customer_id',],
                'unique',
                'when' => $vallidatePartnerCustomerId['when'],
                'whenClient' => $vallidatePartnerCustomerId['whenClient']
            ],
            [
                ['partner_customer_id',],
                'required',
                'when' => $vallidatePartnerCustomerId['when'],
                'whenClient' => $vallidatePartnerCustomerId['whenClient']
            ],
            [
                ['bank_name',],
                'required',
                'when' => function () {
                    return !!$this->payment_type;
                },
                'whenClient' => "function() {
                    return $('#payment_type').val() != '';
                }"
            ],
            [
                ['id_card_number',],
                'required',
                'when' => function () {
                    return $this->payment_type == self::PAYMENT_TYPE_CASH;
                },
                'whenClient' => "function() {
                    return $('#payment_type').val() === '" . self::PAYMENT_TYPE_CASH . "';
                }"
            ],
            [
                ['bank_branch', 'bank_customer_id'],
                'required',
                'when' => function () {
                    return $this->payment_type == self::PAYMENT_TYPE_TRANSFER;
                },
                'whenClient' => "function() {
                    return $('#payment_type').val() === '" . self::PAYMENT_TYPE_TRANSFER . "';
                }"
            ],
            [['bank_customer_id'], 'string', 'max' => 35],
            [['slug', 'phone', 'id_card_number'], 'unique'],
            [['email'], 'email'],
            [['total_commission', 'total_commission_paid', 'total_commission_remain'], 'number'],
            [['birthday', 'date_accept_do_service', 'date_checkin'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::class, 'targetAttribute' => ['partner_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationCountry::class, 'targetAttribute' => ['country_id' => 'id']],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationProvince::class, 'targetAttribute' => ['province_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationDistrict::class, 'targetAttribute' => ['district_id' => 'id']],
            [['ward_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationWard::class, 'targetAttribute' => ['ward_id' => 'id']],
            [['place_of_issue'], 'exist', 'skipOnError' => true, 'targetClass' => LocationProvince::class, 'targetAttribute' => ['place_of_issue' => 'id']],
            [['date_of_issue'], 'safe'],
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
            'full_name' => Yii::t('backend', 'Full Name'),
            'phone' => Yii::t('backend', 'Phone'),
            'email' => Yii::t('backend', 'Email'),
            'face_customer' => Yii::t('backend', 'Face Customer'),
            'partner_id' => Yii::t('backend', 'Đối tác'),
            'description' => Yii::t('backend', 'Description'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'birthday' => Yii::t('backend', 'Birthday'),
            'sex' => Yii::t('backend', 'Sex'),
            'partner_customer_id' => Yii::t('backend', 'Id KH (Đối tác)'),
            'date_accept_do_service' => Yii::t('backend', 'Date Accept Do Service'),
            'date_checkin' => Yii::t('backend', 'Date Checkin'),
            'country_id' => Yii::t('backend', 'Country'),
            'province_id' => Yii::t('backend', 'Province'),
            'district_id' => Yii::t('backend', 'District'),
            'ward_id' => Yii::t('backend', 'Ward'),
            'address' => Yii::t('backend', 'Address'),
            'status' => Yii::t('backend', 'Customer Status'),
            'total_commission' => Yii::t('backend', 'Tổng hoa hồng'),
            'total_commission_paid' => Yii::t('backend', 'Tổng hoa hồng đã trả cho KH'),
            'total_commission_remain' => Yii::t('backend', 'Tổng hoa hồng còn lại'),
            'bank_branch' => Yii::t('backend', 'Chi nhánh ngân hàng'),
            'bank_name' => Yii::t('backend', 'Tên ngân hàng'),
            'bank_customer_id' => Yii::t('backend', 'Số tài khoản ngân hàng'),
            'id_card_number' => Yii::t('backend', 'CMND/CTCD'),
            'payment_type' => Yii::t('backend', 'Phương thức thanh toán'),
            'place_of_issue' => Yii::t('backend', 'Nơi cấp'),
            'date_of_issue' => Yii::t('backend', 'Ngày cấp'),
        ];
    }

    /**
     * Kiểm tra khách có hài lòng không
     * @return bool
     */
    public function isUnsatisfied()
    {
        $listFeedback = $this->feedbacks;

        if (!$listFeedback) {
            return false;
        }

        $lastFeedback = array_pop($listFeedback);

        if ($lastFeedback->feedback_type === Feedback::UNSATISFIED_TYPE) {
            return true;
        }

        return false;
    }

    /**
     * Load data từ API
     * @param $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function loadFromApi($params)
    {
        $formName = $this->formName();
        $paramsPrepare = [];

        foreach ($params as $k => $v) {
            $paramsPrepare[$formName][$k] = $v;
        }

        return $this->load($paramsPrepare);
    }

}
