<?php

namespace modava\affiliate\controllers;

use modava\affiliate\AffiliateModule;
use modava\affiliate\components\MyAffiliateController;
use modava\affiliate\helpers\Utils;
use modava\affiliate\models\search\CustomerPartnerSearch;
use Yii;
use yii\web\Response;
use modava\affiliate\helpers\MyAurisApi;

/*
 * Implement by Duc Huynh
 * Date: 2020-07-29
 * Purpose: Provide a controller handle ajax request
 * */

class HandleAjaxController extends MyAffiliateController
{
    var $modelName = null;
    var $classModelName = null;
    var $classModelNameTable = null;
    var $classModelNameSearch = null;
    var $toastr_key = 'handle-ajax';

    /*public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\AjaxFilter',
                'only' => ['*']
            ],
        ];
    }*/

    public function actionGetCreateModal()
    {
        $formView = Utils::decamelize($this->modelName);
        $filePath = \Yii::getAlias('@modava/affiliate/views/' . $formView . '/_form.php');
        if (!file_exists($filePath)) {
            return $this->renderAjax('error-modal', [
                'errorMessage' => Yii::t('backend', 'Form is not existed'),
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

                return [ 'success' => true ];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [ 'success' => false ];
            }
        }
    }

    public function actionUpdateAjax($id)
    {
        $model = $this->classModelName::findOne($id);

        if ($model == null) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [ 'success' => false, 'message' => Yii::t('backend', 'Không tìm thấy record') ];
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [ 'success' => true ];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [ 'success' => false ];
            }
        }
    }

    public function beforeAction($action)
    {
        $modelName = \Yii::$app->request->get('model');
        if (!$modelName) $modelName = \Yii::$app->request->post('model');
        $className = 'modava\affiliate\models\\' . $modelName;
        $classNameTable = 'modava\affiliate\models\table\\' . $modelName . 'Table';
        $classNameSearch = 'modava\affiliate\models\search\\' . $modelName . 'Search';

        // Validate Query Param
        if (!$modelName || !class_exists($className) || !class_exists($classNameTable) || !class_exists($classNameSearch)) {
            echo $this->renderAjax('error-modal', [
                'errorMessage' => Yii::t('backend', 'Object is not existed'),
            ]);

            Yii::$app->end();
        }


        if (in_array($modelName, Yii::$app->getModule('affiliate')->params['not_release_object'])) {
            echo $this->renderAjax('error-modal', [
                'errorMessage' => Yii::t('backend', 'Object is not released'),
            ]);

            Yii::$app->end();
        }

        $this->modelName = $modelName;
        $this->classModelName = $className;
        $this->classModelNameTable = $classNameTable;
        $this->classModelNameSearch = $classNameSearch;

        return parent::beforeAction($action);
    }

    public function actionGetListRelatedRecordsModal () {
        $formView = Utils::decamelize($this->modelName);
        $filePath = \Yii::getAlias('@modava/affiliate/views/' . $formView . '/related-list.php');
        if (!file_exists($filePath)) {
            return $this->renderAjax('error-modal', [
                'errorMessage' => Yii::t('backend', 'File is not existed'),
            ]);
        }

        $searchModel = new $this->classModelNameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('list-related-records-modal', [
            'modelName' =>  $this->modelName,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filePath' => $filePath
        ]);
    }

    public function actionGetCustomerMoreInfo () {
        $customerId = Yii::$app->request->get('customerId');
        $data = CustomerPartnerSearch::getCustomerById($customerId);
        $customerSearch = new CustomerPartnerSearch();
        $listThaoTac = $customerSearch->getDropdown('thao_tac');

        if (!$data || (isset($data['status']) && $data['status'] == 500)) {
            if (isset($data['status']) && $data['status'] == 500) {
                Yii::warning($data['message']);
            }

            return $this->renderAjax('error-modal', [
                'errorMessage' => Yii::t('backend', 'Error connection!'),
            ]);
        }

        return $this->renderAjax('dashboard-myauris-customer-modal', [
            'model' => $data,
            'listThaotac' => $listThaoTac
        ]);
    }

    public function actionGetCallLogModal() {
        $phone = Yii::$app->request->get('phone');

        $callLog = CustomerPartnerSearch::getCallLog($phone);

        return $this->renderAjax('call-log-modal', [
            'data' => $callLog,
            'phone' => $phone,
        ]);
    }
}