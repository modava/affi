<?php

namespace modava\affiliate\controllers;

class AffiliateController extends \backend\components\MyController
{
    public function actionIndex()
    {
        return $this->redirect(['customer/index']);
    }

}
