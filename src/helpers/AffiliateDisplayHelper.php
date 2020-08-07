<?php


namespace modava\affiliate\helpers;


use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\CustomerTable;
use Yii;
use yii\helpers\Html;

class AffiliateDisplayHelper
{
    public static function getCustomerInformation ($model) {
        $customerInfo = "<strong>". AffiliateModule::t('affiliate', 'Full Name') .": </strong>" . self::getFullName($model) . "<br>";
        $customerInfo .=  "<strong>" . AffiliateModule::t('affiliate', 'Sex') . ": </strong>" . self::getSex($model) . "<br>";
        $customerInfo .=  "<strong>" . AffiliateModule::t('affiliate', 'Permission User') . ": </strong>{$model['permission_user']}<br>";
        $customerInfo .=  "<strong>" . AffiliateModule::t('affiliate', 'Phone') . ": </strong>" . self::getPhone($model) . "<br>";

        return $customerInfo;
    }

    public static function getFullName ($model) {
        $message = AffiliateModule::t('affiliate', 'Converted');
        $tick = '';

        if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->controller->module->params['partner_id']['dashboard-myauris'], $model['id'])) {
            $tick = Html::tag('span', '', [
                'title' => $message,
                'alia-label' => $message,
                'class' => 'glyphicon glyphicon-ok text-success m-1',
            ]);
        }

        return "{$model['full_name']} {$tick}";
    }

    public static function getSex ($model) {
        return Yii::$app->controller->module->params['sex'][$model['sex']];
    }

    public static function getPhone ($model) {
        $content = '';
        if (class_exists('modava\voip24h\CallCenter')) $content .= Html::a('<i class="fa fa-phone"></i>', 'javascript: void(0)', [
            'class' => 'btn btn-xs btn-success call-to',
            'title' => 'Gọi',
            'data-uri' => $model['phone']
        ]);
        $content .= Html::a('<i class="fa fa-paste"></i>', 'javascript: void(0)', [
            'class' => 'btn btn-xs btn-info copy ml-1',
            'title' => 'Copy'
        ]);
        return $content;
    }

    public static function getImages ($model) {
        $as ='';
        $hostUrl = Yii::$app->controller->module->params['myauris_config']['url_website'];

        foreach ($model['image'] as $img) {
            $imgs = Html::img($hostUrl . $img['thumbnailLink'], [
                'class' => 'img-fluid mx-1 rounded',
                'width' => '100px'
            ]);

            $as = Html::a($imgs, $hostUrl . $img['webContentLink']) . $as;
        }

        return "<div class='customer-img-container'>{$as}</div>";
    }

    public static function getOrderInformation ($model, $listThaotac) {
        if (!array_key_exists('don_hang', $model)) return '';

        $content = '';
        foreach ($model['don_hang'] as $donhang) {
            $content1 = "<strong>Mã HĐ: {$donhang['order_code']}</strong> <i>(" . date('d-m-Y', $donhang['ngay_tao']) . ")</i><br/>";
            foreach ($donhang['chi_tiet'] as $chiTietDonHang) {
                $content1 .= "{$chiTietDonHang['dich_vu']} | {$chiTietDonHang['san_pham']} : <strong>{$chiTietDonHang['so_luong']}</strong><br/>";
            }

            $content .= "<div class='hk-sec-wrapper header-300 mx-2 mb-0 px-3 py-2'>{$content1}</div>";
        }

        return "<div class='d-flex'>{$content}</div>";
    }

    public static function getTreatmentSchedule ($model, $listThaotac) {
        if (!array_key_exists('don_hang', $model)) return '';

        $content = '';
        foreach ($model['don_hang'] as $donhang) {
            if (array_key_exists('lich_dieu_tri', $donhang)) {
                foreach ($donhang['lich_dieu_tri'] as $lichDieuTriInfo) {
                    $content1 = '';
                    $arrThaoTac = [];

                    foreach ($lichDieuTriInfo['thao_tac'] as $thaotac) {
                        $arrThaoTac[] = $listThaotac[$thaotac];
                    }

                    $content1 .= '<strong>Thac tác: </strong>'. implode(', ', $arrThaoTac) . '<br>';
                    $content1 .= '<strong>Ekip:</strong> '. $lichDieuTriInfo['ekip'] . '<br>';
                    $content1 .= '<strong>Trợ thủ:</strong> '. implode(', ', $lichDieuTriInfo['tro_thu']) . '<br>';
                    $content1 .= '<strong>Ngày điều trị: </strong>'. date('d-m-Y H:i', $lichDieuTriInfo['time_dieu_tri']) . '<br>';

                    $content .= "<div class='hk-sec-wrapper header-400 mx-2 mb-0 px-3 py-2'>{$content1}</div>";
                }
            }
        }

        return "<div class='d-flex'>{$content}</div>";
    }
}