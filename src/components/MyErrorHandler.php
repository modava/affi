<?php
namespace modava\affiliate\components;

class MyErrorHandler extends \yii\web\ErrorHandler
{
    public $errorView = '@modava/affiliate/views/error/error.php';

}
