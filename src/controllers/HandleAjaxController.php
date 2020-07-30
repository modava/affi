<?php

namespace modava\affiliate\controllers;

use backend\widgets\ToastrWidget;
use modava\affiliate\AffiliateModule;
use modava\affiliate\components\MyAffiliateController;
use modava\affiliate\helpers\Utils;
use Yii;
use yii\helpers\Html;
use yii\web\Response;

/*
 * Implement by Duc Huynh
 * Date: 2020-07-29
 * Purpose: Provide a controller handle ajax request
 * */

class HandleAjaxController extends MyAffiliateController
{
    var $modelName = null;
    var $classModelName = null;
    var $toastr_key = 'handle-ajax';

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\AjaxFilter',
                'only' => ['*']
            ],
        ];
    }

    public function actionGetCreateModal()
    {
        $formView = Utils::decamelize($this->modelName);
        $filePath = \Yii::getAlias('@modava/affiliate/views/' . $formView . '/_form.php');
        if (!file_exists($filePath)) {
            return $this->renderAjax('error-modal', [
                'errorMessage' => AffiliateModule::t('affiliate', 'Form is not existed'),
            ]);
        }

        $filePath = '@modava/affiliate/views/' . $formView . '/_form.php';

        $model = new $this->classModelName();
        $model->load(\Yii::$app->request->get());

        $data = \Yii::$app->request->get('data');

        return $this->renderAjax('create-modal', [
            'modelName' => $this->modelName,
            'formView' => $formView,
            'model' => $model,
            'formPath' => $filePath
        ]);
    }

    public function actionSaveAjax()
    {
        $model = new $this->classModelName();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('toastr-' . $this->toastr_key . '-save-ajax', [
                    'title' => 'Thông báo',
                    'text' => 'Tạo mới thành công',
                    'type' => 'success'
                ]);

                Yii::$app->response->format = Response::FORMAT_JSON;

                return [ 'success' => true];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [ 'success' => true];
            }
        }
    }

    public function beforeAction($action)
    {
        $modelName = \Yii::$app->request->get('model');
        if (!$modelName) $modelName = \Yii::$app->request->post('model');
        $className = 'modava\affiliate\models\\' . $modelName;

        // Validate Query Param
        if (!$modelName || !class_exists($className)) {
            echo $this->renderAjax('error-modal', [
                'errorMessage' => AffiliateModule::t('affiliate', 'Object is not existed'),
            ]);

            Yii::$app->end();
        }

        $this->modelName = $modelName;
        $this->classModelName = $className;

        return parent::beforeAction($action);
    }
}