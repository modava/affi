<?php


namespace modava\affiliate\helpers;

/*
 * Implement by Hoang Duc
 * Date:    2020-07-29
 * Purpose: Provide a Util class*/

class MyAurisApi
{
    static $CACHE_TIME = 7200; // 2 hours
    static $CACHE_MANAGE_KEY = 'redis-affiliate-dashboard-myauris-list-home-key';

    public static function getListThaoTac () {
        $cache = \Yii::$app->cache;
        $cacheKey = 'redis-affiliate-dashboard-myauris-list-thao-tac';

        $apiParam = \Yii::$app->controller->module->params['myauris_config'];

        if ($cache->exists($cacheKey)) return $cache->get($cacheKey);

        $curlHelper2 = new CurlHelper($apiParam['url_end_point'] . $apiParam['endpoint']['list_thao_tac']);
        $curlHelper2->setHeader($apiParam['header']);
        $response2 = $curlHelper2->execute();

        $listThaoTac = json_decode($response2['result'], true);
        $cache->set($cacheKey, $listThaoTac, self::$CACHE_TIME);

        self::manageCacheKey($cacheKey);

        return $listThaoTac;
    }

    public static function getCustomerInfo ($customerId) {
        $cache = \Yii::$app->cache;
        $cacheKey = 'redis-affiliate-dashboard-myauris-get-customer-info-with-id-' . $customerId;

        $apiParam = \Yii::$app->controller->module->params['myauris_config'];

        if ($cache->exists($cacheKey)) return $cache->get($cacheKey);

        $curlHelper2 = new CurlHelper($apiParam['url_end_point'] . $apiParam['endpoint']['get_customer'] . "?id={$customerId}");
        $curlHelper2->setHeader($apiParam['header']);
        $response2 = $curlHelper2->execute();

        $listThaoTac = json_decode($response2['result'], true);
        $cache->set($cacheKey, $listThaoTac, self::$CACHE_TIME);

        self::manageCacheKey($cacheKey);

        return $listThaoTac;
    }

    public static function getCompleteCustomerService ($payload) {
        $cache = \Yii::$app->cache;
        $cacheKey = 'redis-affiliate-dashboard-myauris';
        foreach ($payload as $key => $value) {
            $cacheKey .= "-{$key}-{$value}";
        }

        if ($cache->exists($cacheKey)) return $cache->get($cacheKey);

        $apiParam = \Yii::$app->controller->module->params['myauris_config'];

        $url = $apiParam['url_end_point'] . $apiParam['endpoint']['complete_customer_service'] . '?per-page=' . $apiParam['row_per_page'] . '&' . http_build_query($payload);

        $curlHelper = new CurlHelper($url);
        $curlHelper->setHeader($apiParam['header']);
        $response = $curlHelper->execute();

        $cache->set($cacheKey, $response, self::$CACHE_TIME);

        self::manageCacheKey($cacheKey);

        return $response;
    }

    private static function manageCacheKey ($cacheKey) {
        $cache = \Yii::$app->cache;

        if ($cache->exists(self::$CACHE_MANAGE_KEY)) {
            $listKey = $cache->get(self::$CACHE_MANAGE_KEY);
            if (!array_key_exists($cacheKey, $listKey)) $listKey[] = $cacheKey;
        }
        else {
            $listKey = [$cacheKey];
        }

        $cache->set(self::$CACHE_MANAGE_KEY, $listKey);
    }
}