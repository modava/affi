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
        if(CONSOLE_HOST == 1)
            return $this->render('navbarWidgets', []);
        else
            return '';
    }
}