<?php

namespace modava\affiliate\controllers;

use modava\affiliate\AffiliateModule;
use modava\affiliate\helpers\MyAurisApi;
use modava\affiliate\models\Customer;
use modava\affiliate\models\search\CustomerPartnerSearch;
use modava\affiliate\models\table\CustomerTable;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

class AffiliateController extends \backend\components\MyController
{
    public function actionIndex()
    {
        $model = new CustomerPartnerSearch();
        $response = $model->search(Yii::$app->request->queryParams, true);

        // Get List Thao Tac
        $dropdowns = $model->getDropdowns();

        if (!$response['success']) {
            Yii::warning($response['error']);
            Yii::$app->session->setFlash('toastr-affiliate-list', [
                'title' => 'Thông báo',
                'text' => 'Đã có lỗi kết nối!',
                'type' => 'warning'
            ]);
        }

        return $this->render('index', [
            'dropdowns' => $dropdowns,
            'model' => $model,
            'dataProvider' => $response['dataProvider']
        ]);
    }

    public function actionClearCache()
    {
        $cache = Yii::$app->cache;

        if ($cache->exists(MyAurisApi::$CACHE_MANAGE_KEY)) {
            $keys = $cache->get(MyAurisApi::$CACHE_MANAGE_KEY);
            foreach ($keys as $key) {
                $cache->delete($key);
            }

            $cache->delete(MyAurisApi::$CACHE_MANAGE_KEY);
        }

        Yii::$app->session->setFlash('toastr-affiliate-list', [
            'title' => AffiliateModule::t('affiliate', 'Notification'),
            'text' => AffiliateModule::t('affiliate', 'Delete cache successfully'),
            'type' => 'success'
        ]);

        return $this->redirect(Yii::$app->request->referrer ?: Url::toRoute(['index']));
    }

    public function actionReInitCustomerCacheKey() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $customers = CustomerTable::find()->all();
        $cache = Yii::$app->cache;

        $caches = [];

        foreach ($customers as $customer) {
            $cache->set($customer->getRecordCacheKey(), $customer->getAttributes());
            $caches[] = $customer->getRecordCacheKey();
        }

        return [
            'status' => 'ok',
            'list_cache' => $caches
        ];
    }
}
