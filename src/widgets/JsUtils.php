<?php
namespace modava\affiliate\widgets;

/**
 * Các function utils JS
 * Class JsUtils
 * @package modava\affiliate\widgets
 */
class JsUtils extends \yii\base\Widget
{
    /**
     * @var string tên form: coupon_form
     */
    public $formClassName;

    /**
     * @var string tên model: Coupon
     */
    public $modelName;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('jsUtils', [
            'formClassName' => $this->formClassName,
            'modelName' => $this->modelName,
        ]);
    }
}