<?php

namespace modava\affiliate\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AffiliateAsset extends AssetBundle
{
    public $sourcePath = '@backendWeb';
    public $css = [
        'vendors/datatables.net-dt/css/jquery.dataTables.min.css',
        'vendors/bootstrap/dist/css/bootstrap.min.css',
        'vendors/jquery-toggles/css/toggles.css',
        'vendors/jquery-toggles/css/themes/toggles-light.css',
        'vendors/daterangepicker/daterangepicker.css',
        'vendors/lightgallery/dist/css/lightgallery.min.css',
    ];
    public $js = [
        "vendors/popper.js/dist/umd/popper.min.js",
        "vendors/bootstrap/dist/js/bootstrap.min.js",
        "vendors/jasny-bootstrap/dist/js/jasny-bootstrap.min.js",
        "vendors/moment/min/moment.min.js",
        "vendors/daterangepicker/daterangepicker.js",
        'vendors/lightgallery/dist/js/lightgallery-all.min.js',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
