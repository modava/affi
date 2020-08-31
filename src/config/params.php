<?php
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\Coupon;
use modava\affiliate\models\Customer;

return [
    'affiliateName' => 'Affiliate',
    'affiliateVersion' => '1.0',
    'status' => [
        '0' => AffiliateModule::t('affiliate', 'Tạm ngưng'),
        '1' => AffiliateModule::t('affiliate', 'Hiển thị'),
    ],
    'active' => [
        '0' => AffiliateModule::t('affiliate', 'Ngừng hoạt động'),
        '1' => AffiliateModule::t('affiliate', 'Hoạt động'),
    ],
    'sex' => [
        '0' => AffiliateModule::t('affiliate', 'Female'),
        '1' => AffiliateModule::t('affiliate', 'Male'),
        '2' => AffiliateModule::t('affiliate', 'Other'),
    ],
    'customer_status' => [
        Customer::STATUS_DANG_LAM_DICH_VU => AffiliateModule::t('affiliate', 'Đang làm dịch vụ'),
        Customer::STATUS_HOAN_THANH_DICH_VU => AffiliateModule::t('affiliate', 'Đã hoàn thành dịch vụ'),
    ],
    'customer_status_color' => [
        Customer::STATUS_DANG_LAM_DICH_VU => '#69c982',
        Customer::STATUS_HOAN_THANH_DICH_VU => '#22af47',
    ],
    'promotion_type' => [
        Coupon::DISCOUNT_PERCENT => AffiliateModule::t('affiliate', 'Discount Percent In Order'),
        Coupon::DISCOUNT_AMOUNT => AffiliateModule::t('affiliate', 'Discount Amount In Order'),
    ],
    'myauris_config' => [
        'url_website' => "https://dashboard.myauris.vn",
        'url_end_point' => "https://dashboard.myauris.vn/api/v2/affiliate/",
        'endpoint' => [
            'customer' => "customer",
            'get_customer' => "get-customer",
            'create_note' => "save-log-cskh",
            'get_call_log' => 'get-call-log'
        ],
        'header' => ['X-Api-Key: qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX'], // @todo Refactor code here: chuyển MyAurisApi vào model
        'headers' => ['X-Api-Key' => 'qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX'],
        'row_per_page' => 10,
        'field_to_endpoint' => [
//             'co_so' => 'co-so', // @todo
            'permission_user' => 'nhan-vien-le-tan',
            'customer_come_time_to' => 'status-customer-come',
            'directsale' => 'nhan-vien-direct-sale',
            'nguon_online' => 'nguon-customer-online',
            'id_dich_vu' => 'dich-vu-online',
            'thao_tac' => 'list-thao-tac',
        ]
    ],
    'not_release_object' => [
    ],
    'note_type' => [
        '0' => AffiliateModule::t('affiliate', 'For This System'),
        '1' => AffiliateModule::t('affiliate', 'For Partner System'),
    ],
    'feedback_type' => [
        '0' => AffiliateModule::t('affiliate', 'Unsatisfied'),
        '1' => AffiliateModule::t('affiliate', 'Satisfied'),
        '2' => AffiliateModule::t('affiliate', 'Bình thường'),
    ],
    'feedback_type_color' => [
        '0' => '#ab26aa',
        '1' => '#22af47',
        '2' => '#52b5f3',
    ],
    'receipt_status' => [
        '0' => AffiliateModule::t('affiliate', 'Thanh toán'),
        '1' => AffiliateModule::t('affiliate', 'Đặt cọc'),
        '2' => AffiliateModule::t('affiliate', 'Hoàn Cọc'),
    ],
    'order_status' => [
        '0' => AffiliateModule::t('affiliate', 'Chưa hoàn thành'),
        '1' => AffiliateModule::t('affiliate', 'Hoàn thành'),
        '2' => AffiliateModule::t('affiliate', 'Hủy'),
    ]
];
