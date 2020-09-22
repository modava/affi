<?php


namespace modava\affiliate\helpers;


use modava\affiliate\models\table\CustomerTable;
use Yii;
use yii\helpers\Html;

class AffiliateDisplayHelper
{
    public static function getCustomerInformation($model)
    {
        $customerInfo = "<strong>" . Yii::t('backend', 'Full Name') . ": </strong>" . self::getFullName($model) . "<br>";
        $customerInfo .= "<strong>" . Yii::t('backend', 'Sex') . ": </strong>" . self::getSex($model) . "<br>";
        $customerInfo .= "<strong>" . Yii::t('backend', 'Permission User') . ": </strong>{$model['permission_user']}<br>";
        $customerInfo .= "<strong>" . Yii::t('backend', 'Phone') . ": </strong>" . self::getPhone($model) . "<br>";
        $customerInfo .= "<strong>" . Yii::t('backend', 'Direct Sales') . ": </strong>{$model['directsale']}<br>";
        $customerInfo .= "<strong>" . Yii::t('backend', 'Service') . ": </strong>{$model['id_dich_vu']}<br>";
        $customerInfo .= "<strong>" . Yii::t('backend', 'Cơ sở') . ": </strong>{$model['co_so']}<br>";

        return $customerInfo;
    }

    public static function getFullName($model)
    {
        $message = Yii::t('backend', 'Converted');
        $tick = '';

        if (CustomerTable::getRecordByPartnerInfoFromCache(Yii::$app->getModule('affiliate')->params['partner_id']['dashboard-myauris'], $model['id'])) {
            $tick = Html::tag('span', '', [
                'title' => $message,
                'alia-label' => $message,
                'class' => 'glyphicon glyphicon-ok text-success m-1',
            ]);
        }

        return "{$model['full_name']} {$tick}";
    }

    public static function getSex($model)
    {
        return Yii::$app->getModule('affiliate')->params['sex'][$model['sex']];
    }

    public static function getPhone($model)
    {
        $content = '';
        if (class_exists('modava\voip24h\CallCenter')) $content .= Html::a('<i class="fa fa-phone"></i>', 'javascript: void(0)', [
            'class' => 'btn btn-xs btn-success call-to',
            'title' => 'Gọi',
            'data-uri' => $model['phone']
        ]);
        $content .= Html::a('<i class="fa fa-paste"></i>', 'javascript: void(0)', [
            'class' => 'btn btn-xs btn-info copy ml-1',
            'title' => 'Copy',
            'data-copy' => $model['phone']
        ]);
        return $content;
    }

    public static function getImages($model, $param = ['container_class' => '', 'img_class' => ''])
    {
        $as = '';

        foreach ($model['image'] as $img) {
            $imgs = Html::img($img['thumbnailLink'], [
                'class' => "img-fluid mx-1 rounded " . (isset($param['img_class']) ? $param['img_class'] : ''),
                'width' => '100px'
            ]);

            $as = Html::a($imgs, $img['webContentLink']) . $as;
        }

        return "<div class='customer-img-container " . ($param['container_class'] ? $param['container_class'] : '') . "'>{$as}</div>";
    }

    public static function getOrderInformation($model, $listThaotac)
    {
        if (!array_key_exists('don_hang', $model)) return '';

        $content = '';
        foreach ($model['don_hang'] as $donhang) {
            $content1 = "<strong>Mã HĐ: {$donhang['order_code']}</strong> <i>(" . date('d-m-Y', $donhang['ngay_tao']) . ")</i><br/>";
            foreach ($donhang['chi_tiet'] as $chiTietDonHang) {
                $content1 .= "{$chiTietDonHang['dich_vu']} | {$chiTietDonHang['san_pham']} : <strong>{$chiTietDonHang['so_luong']}</strong><br/>";
            }

            $content .= "<div class='hk-sec-wrapper mb-2 px-3 py-2'>{$content1}</div>";
        }

        return "<div class='d-flex flex-wrap h-100'>{$content}</div>";
    }

    public static function getTreatmentSchedule($model, $listThaotac)
    {
        if (!array_key_exists('don_hang', $model)) return '';

        $content = '';
        foreach ($model['don_hang'] as $donhang) {
            if (array_key_exists('lich_dieu_tri', $donhang)) {
                foreach ($donhang['lich_dieu_tri'] as $lichDieuTriInfo) {
                    $popoverTitle = '';
                    $popoverContent = '';
                    $content1 = '';
                    $arrThaoTac = [];

                    if (array_key_exists('thao_tac', $lichDieuTriInfo) && $lichDieuTriInfo['thao_tac'] !== null) {
                        foreach ($lichDieuTriInfo['thao_tac'] as $thaotac) {
                            $arrThaoTac[] = $listThaotac[$thaotac];
                        }
                    }

                    $popoverTitle = '<strong>Ngày điều trị:</strong> ' . date('d-m-Y H:i', $lichDieuTriInfo['time_dieu_tri']);

                    $popoverContent = "<strong>Mã HĐ: {$donhang['order_code']}</strong> <i>(" . date('d-m-Y', $donhang['ngay_tao']) . ")</i><br/>";
                    $popoverContent .= '<strong>Phòng:</strong> ' . $lichDieuTriInfo['room_id'] . '<br>';
                    $popoverContent .= '<strong>Thao tác: </strong>' . implode(', ', $arrThaoTac) . '<br>';
                    $popoverContent .= '<strong>Ekip:</strong> ' . $lichDieuTriInfo['ekip'] . '<br>';
                    $popoverContent .= '<strong>Trợ thủ:</strong> ' . implode(', ', $lichDieuTriInfo['tro_thu']) . '<br>';
//                    $content1 .= '<strong>Ngày điều trị: </strong>' . date('d-m-Y H:i', $lichDieuTriInfo['time_dieu_tri']) . '<br>';


//                    $content .= "<div class='hk-sec-wrapper mb-2 px-3 py-2 w-350p text-left'>{$content1}</div>";
//                    $content .= '<button type="button" class="btn btn-sm" data-toggle="popover" title="' . $popoverTitle . '" data-content="' . $popoverContent . '" data-html="true" data-placement="bottom">' . $popoverTitle . '</button>';
                    $content .= '<div class="hk-sec-wrapper mb-2 px-3 py-2 w-300p text-left" type="button"
                                      data-toggle="popover"
                                      data-content="' . $popoverContent . '"
                                      data-html="true" data-placement="bottom">' . $popoverTitle . '</div>';
                }
            }
        }

        return "<div class='d-flex flex-wrap h-100'>{$content}</div>";
    }
}