<?php
use modava\affiliate\models\Coupon;
use modava\affiliate\models\Customer;
use modava\affiliate\models\Feedback;
use modava\affiliate\models\Order;
use modava\affiliate\models\Payment;
use modava\affiliate\models\SmsLog;

return [
    'affiliateName' => 'Affiliate',
    'affiliateVersion' => '1.0',
    'status' => [
        '0' => Yii::t('backend', 'Tạm ngưng'),
        '1' => Yii::t('backend', 'Hiển thị'),
    ],
    'active' => [
        '0' => Yii::t('backend', 'Ngừng hoạt động'),
        '1' => Yii::t('backend', 'Hoạt động'),
    ],
    'sex' => [
        '0' => Yii::t('backend', 'Female'),
        '1' => Yii::t('backend', 'Male'),
        '2' => Yii::t('backend', 'Other'),
    ],
    'customer_status' => [
        Customer::STATUS_DANG_LAM_DICH_VU => Yii::t('backend', 'Đang làm dịch vụ'),
        Customer::STATUS_HOAN_THANH_DICH_VU => Yii::t('backend', 'Đã hoàn thành dịch vụ'),
    ],
    'customer_status_color' => [
        Customer::STATUS_DANG_LAM_DICH_VU => '#69c982',
        Customer::STATUS_HOAN_THANH_DICH_VU => '#22af47',
    ],
    'promotion_type' => [
        Coupon::DISCOUNT_PERCENT => Yii::t('backend', 'Discount Percent In Order'),
        Coupon::DISCOUNT_AMOUNT => Yii::t('backend', 'Discount Amount In Order'),
    ],
    'myauris_config' => [
        'url_website' => "https://dashboard.myauris.vn",
        'url_end_point' => "https://dashboard.myauris.vn/api/v2/",
        'endpoint' => [
            'customer' => "affiliate/customer",
            'get_customer' => "affiliate/get-customer",
            'create_note' => "affiliate/save-log-cskh",
            'get_call_log' => 'affiliate/get-call-log',
            'send_sms_coupon' => 'sms/send-sms-promotions',
            'get_sms_coupon' => 'sms/get-content-sms-promotions',
        ],
        'header' => ['X-Api-Key: qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX'], // @todo Refactor code here: chuyển MyAurisApi vào model
        'headers' => ['X-Api-Key' => 'qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX',],
        'row_per_page' => 10,
        'field_to_endpoint' => [
//             'co_so' => 'co-so', // @todo
            'permission_user' => 'affiliate/nhan-vien-le-tan',
            'customer_come_time_to' => 'affiliate/status-customer-come',
            'directsale' => 'affiliate/nhan-vien-direct-sale',
            'nguon_online' => 'affiliate/nguon-customer-online',
            'id_dich_vu' => 'affiliate/dich-vu-online',
            'thao_tac' => 'affiliate/list-thao-tac',
        ]
    ],
    'not_release_object' => [
    ],
    'note_type' => [
        '0' => Yii::t('backend', 'For This System'),
        '1' => Yii::t('backend', 'For Partner System'),
    ],
    'feedback_type' => [
        Feedback::UNSATISFIED_TYPE => Yii::t('backend', 'Unsatisfied'),
        Feedback::SATISFIED_TYPE => Yii::t('backend', 'Satisfied'),
        Feedback::NORMAL_TYPE => Yii::t('backend', 'Bình thường'),
    ],
    'feedback_type_color' => [
        '0' => '#ab26aa',
        '1' => '#22af47',
        '2' => '#52b5f3',
    ],
    'receipt_status' => [
        '0' => Yii::t('backend', 'Thanh toán'),
        '1' => Yii::t('backend', 'Đặt cọc'),
        '2' => Yii::t('backend', 'Hoàn Cọc'),
    ],
    'order_status' => [
        Order::CHUA_HOAN_THANH => Yii::t('backend', 'Chưa hoàn thành'),
        Order::HOAN_THANH => Yii::t('backend', 'Hoàn thành'),
        Order::HUY => Yii::t('backend', 'Hủy'),
        Order::KE_TOAN_DUYET => Yii::t('backend', 'Lễ tân đã xác nhận thu tiền'),
        Order::DA_THANH_TOAN => Yii::t('backend', 'Đã thanh toán')
    ],
    'note_is_recall' => [
        '0' => Yii::t('backend', 'Chưa gọi'),
        '1' => Yii::t('backend', 'Đã gọi')
    ],
    'payment_status' => [
        Payment::STATUS_DRAFT => Yii::t('backend', 'Tạo nháp'),
        Payment::STATUS_PAID => Yii::t('backend', 'Đã chi'),
    ],
    'sms_log_status' => [
        SmsLog::STATUS_SUCCESS => Yii::t('backend', 'Thành công'),
        SmsLog::STATUS_FAIL => Yii::t('backend', 'Thất bại'),
    ],
    'customer_payment_type' => [
        Customer::PAYMENT_TYPE_TRANSFER => Yii::t('backend', 'Chuyển khoản ngân hàng'),
        Customer::PAYMENT_TYPE_CASH => Yii::t('backend', 'Tiền mặt'),
    ],
];
