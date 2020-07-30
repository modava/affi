<?php
namespace modava\affiliate\widgets;

class NavbarWidgets extends \yii\base\Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('navbarWidgets', []);
    }
}