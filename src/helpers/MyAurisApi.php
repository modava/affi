<?php


namespace modava\affiliate\helpers;

/*
 * Implement by Hoang Duc
 * Date:    2020-07-29
 * Purpose: Provide a Util class*/

class MyAurisApi
{
    public static function getListThaoTac () {
        $apiParam = \Yii::$app->controller->module->params['myauris_config'];

        $curlHelper2 = new CurlHelper($apiParam['uri'] . $apiParam['endpoint']['list_thao_tac']);
        $curlHelper2->setHeader($apiParam['header']);
        $response2 = $curlHelper2->execute();

        $listThaoTac = json_decode($response2['result'], true);

        return $listThaoTac;
    }

    public static function getCompleteCustomerService ($payload) {
        $apiParam = \Yii::$app->controller->module->params['myauris_config'];

        $url = $apiParam['uri'] . $apiParam['endpoint']['complete_customer_service'] . '?per-page=' . $apiParam['row_per_page'] . '&' . http_build_query($payload);

        $curlHelper = new CurlHelper($url);
        $curlHelper->setHeader($apiParam['header']);
        $response = $curlHelper->execute();
        return $response;
    }
}