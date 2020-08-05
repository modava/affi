<?php
namespace modava\affiliate\widgets;

class JsUtils extends \yii\base\Widget
{
    public $formClassName;
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