<?php
use modava\affiliate\AffiliateModule;

return [
    'affiliateName' => 'Affiliate',
    'affiliateVersion' => '1.0',
    'status' => [
        '0' => AffiliateModule::t('affiliate', 'Tạm ngưng'),
        '1' => AffiliateModule::t('affiliate', 'Hiển thị'),
    ],
    'sex' => [
        '0' => AffiliateModule::t('affiliate', 'Female'),
        '1' => AffiliateModule::t('affiliate', 'Male'),
        '2' => AffiliateModule::t('affiliate', 'Other'),
    ],
    'customer_status' => [
        '0' => AffiliateModule::t('affiliate', 'Đang làm dịch vụ'),
        '1' => AffiliateModule::t('affiliate', 'Đã hoàn thành dịch vụ'),
    ],
    'promotion_type' => [
        '0' => AffiliateModule::t('affiliate', 'Discount Percent In Order'),
        '1' => AffiliateModule::t('affiliate', 'Discount Amount In Order'),
    ],
    'myauris_config' => [
        'url_website' => "https://dashboard.myauris.vn",
        'url_end_point' => "https://dashboard.myauris.vn/api/v2/affiliate/",
        'endpoint' => [
            'customer' => "customer",
            'get_customer' => "get-customer",
            'create_note' => "save-log-cskh",
        ],
        'header' => ['X-Api-Key: qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX'], // @todo Refactor code here: chuyển MyAurisApi vào model
        'headers' => ['X-Api-Key' => 'qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX'],
        'row_per_page' => 10,
        'field_to_endpoint' => [
//             'co_so' => 'co-so', // @todo
            'permission_user' => 'nhan-vien-le-tan',
            'customer_come_time_to' => 'status-dat-hen',
            'directsale' => 'nhan-vien-direct-sale',
            'nguon_online' => 'nguon-customer-online',
            'id_dich_vu' => 'dich-vu-online',
            'thao_tac' => 'list-thao-tac',
        ]
    ],
    'not_release_object' => [
        'Coupon',
        'CouponType',
        'Order',
    ],
    'note_type' => [
        '0' => AffiliateModule::t('affiliate', 'For This System'),
        '1' => AffiliateModule::t('affiliate', 'For Partner System'),
    ],
    'feedback_type' => [
        '0' => AffiliateModule::t('affiliate', 'Unsatisfied'),
        '1' => AffiliateModule::t('affiliate', 'Satisfied'),
    ],
];
