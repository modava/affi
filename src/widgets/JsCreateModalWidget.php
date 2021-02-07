<?php
namespace modava\affiliate\widgets;

/**
 * Đăng ký javascript lấy modal khởi tạo
 * Class JsCreateModalWidget
 * @package modava\affiliate\widgets
 */
class JsCreateModalWidget extends \yii\base\Widget
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
        return $this->render('jsCreateModalWidget', [
            'formClassName' => $this->formClassName,
            'modelName' => $this->modelName,
        ]);
    }
}