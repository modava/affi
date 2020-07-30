<?php
use modava\affiliate\AffiliateModule;

return [
    'availableLocales' => [
        'vi' => 'Tiếng Việt',
        'en' => 'English',
        'jp' => 'Japan',
    ],
    'affiliateName' => 'Affiliate',
    'affiliateVersion' => '1.0',
    'status' => [
        '0' => AffiliateModule::t('affiliate', 'Tạm ngưng'),
        '1' => AffiliateModule::t('affiliate', 'Hiển thị'),
    ],
    'promotion_type' => [
        '0' => AffiliateModule::t('affiliate', 'Discount Percent In Order'),
        '1' => AffiliateModule::t('affiliate', 'Discount Amount In Order'),
    ],
    'myauris_config' => [
        'api_endpoint' => "http://dashboard.myauris.vn/api/v2/affiliate/complete-customer-service",
        'header' => ['X-Api-Key: qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX'],
        'row_per_page' => 10
    ]
];
