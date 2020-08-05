<?php

namespace modava\affiliate\controllers;

use modava\affiliate\helpers\CurlHelper;
use Yii;
use yii\data\ArrayDataProvider;

class AffiliateController extends \backend\components\MyController
{
    public function actionIndex()
    {
        $apiParam = \Yii::$app->controller->module->params['myauris_config'];

        $page = (int) \Yii::$app->request->get('page');
        $page = $page > 0 ? $page : 1;

        $curlHelper = new CurlHelper($apiParam['api_endpoint'] . '/?page=' . $page .'&per-page=' . $apiParam['row_per_page']);
        $curlHelper->setHeader($apiParam['header']);
        $response = $curlHelper->execute();
        $realResponse = json_decode($response['result'], true);

        if (!$realResponse || (isset($realResponse['status']) && $realResponse['status'] == 500)) {
            if (isset($realResponse['status']) && $realResponse['status'] == 500) {
                Yii::warning($response['result']);
            }
            Yii::$app->session->setFlash('toastr-affiliate-list', [
                'title' => 'Thông báo',
                'text' => 'Đã có lỗi kết nối!',
                'type' => 'warning'
            ]);
            $dataProvider = new ArrayDataProvider([
                'allModels' => [],
                'pagination' => [
                    'pageSize' => $apiParam['row_per_page'],
                ]
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider
            ]);
        }

        $data = $realResponse['data'];

        /*
         * Mô tả các case:
         * 1. Dữ liệu có 99 dòng, per-page = 10
         * page = 1: index từ 0 -> 9
         * page = 2: index từ 10 -> 19
         * page = 0, -1, abd: => page = 1
         * */
        // Fill fake data to use Array Data Provider for Grid View Pagination
        $fakeData = array_fill(0, $realResponse['totalCount'], null);
        $pageForFakeData = $page - 1;
        foreach ($data as $rowIndex => $row) {
            $index = $pageForFakeData * $apiParam['row_per_page'] + $rowIndex;
            $fakeData[$index] = $row;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $fakeData,
            'pagination' => [
                'pageSize' => $apiParam['row_per_page'],
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

}
