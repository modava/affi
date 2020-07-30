<?php
namespace modava\affiliate\widgets;

class JsCreateModalWidget extends \yii\base\Widget
{
    public $formClassName;
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