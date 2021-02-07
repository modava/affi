<?php

namespace modava\affiliate\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AffiliateCustomAsset extends AssetBundle
{
    public $sourcePath = '@affiliateweb';
    public $css = [
        'css/customAffiliate.css',
    ];
    public $js = [
        'js/customAffiliate.js'
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
