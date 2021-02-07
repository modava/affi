<?php


namespace modava\affiliate\models\search;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use yii\base\Model;
use Yii;
use yii\data\ArrayDataProvider;

class CustomerPartnerSearch extends Model
{
    static $CACHE_TIME = 86400; // 1 day
    static $CACHE_TIME_CUSTOMER_INFO = 31536000; // 1 year
    static $CACHE_MANAGE_KEY = 'redis-affiliate-dashboard-myauris-list-home-key';
    const ID_THAO_TAC_LAP = 2;

    public $creation_time_from;
    public $creation_time_to;
    public $keyword;
    public $co_so;
    public $permission_user;
    public $appointment_time_from;
    public $appointment_time_to;
    public $customer_come_time_to;
    public $directsale;
    public $nguon_online;
    public $appointment_time_from_lich_dieu_tri;
    public $appointment_time_to_lich_dieu_tri;
    public $id_dich_vu;
    public $thao_tac;

    public function formName()
    {
        return 'ClinicSearch';
    }

    public function rules()
    {
        return [
            [
                [
                    'creation_time_from',
                    'creation_time_to',
                    'keyword',
                    'co_so',
                    'permission_user',
                    'appointment_time_from',
                    'appointment_time_to',
                    'customer_come_time_to',
                    'directsale',
                    'nguon_online',
                    'appointment_time_from_lich_dieu_tri',
                    'appointment_time_to_lich_dieu_tri',
                    'id_dich_vu',
                    'thao_tac',
                ],
                'safe'
            ],
        ];
    }

    /**
     * Lấy ds KH từ crm.myauris.vn
     * @param $params
     * @param false $loadDefaultSearch
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params, $loadDefaultSearch = false)
    {
        if ($loadDefaultSearch) {
            // Set default thao tac
            if (!array_key_exists('ClinicSearch', $params) || !array_key_exists('thao_tac', $params['ClinicSearch'])) {
                $params['ClinicSearch']['thao_tac'] = self::ID_THAO_TAC_LAP;
            }
        }

        $this->load($params);

        $myauris_config = \Yii::$app->getModule('affiliate')->params['myauris_config'];
        $params['page'] = $this->_getPage();
        $params['per-page'] = $myauris_config['row_per_page'];

        $cache = \Yii::$app->cache;

        $cacheKey = $this->_getCacheKey('redis-affiliate-dashboard-myauris', $this->formName(), $this->getAttributes());

        $cacheKey .= "page-{$params['page']}";

        if ($cache->exists($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $url = $myauris_config['url_end_point'] . $myauris_config['endpoint']['customer'];

        $client = new Client();

        try {
            $res = $client->request('GET', $url, [
                'headers' => Yii::$app->getModule('affiliate')->params['myauris_config']['headers'],
                'query' => $params
            ]);

            $response = \GuzzleHttp\json_decode($res->getBody(), true);

            if ($res->getStatusCode() == 200) {
                $return = [
                    'success' => true,
                    'result' => $response,
                    'dataProvider' => $this->_getDataProvider($response)
                ];
                $cache->set($cacheKey, $return, self::$CACHE_TIME);
                self::_manageCacheKey($cacheKey);
                return $return;
            }

            return [
                'success' => false,
                'error' => \GuzzleHttp\json_encode($response),
                'dataProvider' => $this->_getDataProvider()
            ];
        } catch (GuzzleException $exception) {
            return [
                'success' => false,
                'error' => $exception->getMessage(),
                'dataProvider' => $this->_getDataProvider()
            ];
        }
    }

    /**
     * Lấy danh sách dropdown từ crm.myauris.vn
     * @return array
     */
    public function getDropdowns()
    {
        $apiParam = \Yii::$app->getModule('affiliate')->params['myauris_config'];

        $listDropdown = [];

        foreach ($apiParam['field_to_endpoint'] as $fieldName => $endPoint) {
            $listDropdown[$fieldName] = $this->getDropdown($fieldName);
        }

        return $listDropdown;
    }

    /**
     * Lấy dropdown theo fieldname từ crm.myauris.vn
     * @param $fieldName
     * @return array|mixed
     */
    public function getDropdown($fieldName)
    {
        if (!$fieldName) return [];

        $cache = \Yii::$app->cache;
        $cacheKey = $this->_getCacheKey('redis-affiliate-dashboard-myauris-list-' . $fieldName);

        $apiParam = \Yii::$app->getModule('affiliate')->params['myauris_config'];

        if ($cache->exists($cacheKey)) return $cache->get($cacheKey);

        $url = $apiParam['url_end_point'] . $apiParam['field_to_endpoint'][$fieldName];

        $client = new Client();

        try {
            $res = $client->request('GET', $url, [
                'headers' => Yii::$app->getModule('affiliate')->params['myauris_config']['headers'],
            ]);

            $response = \GuzzleHttp\json_decode($res->getBody(), true);

            if ($res->getStatusCode() == 200) {
                $cache->set($cacheKey, $response, self::$CACHE_TIME);
                self::_manageCacheKey($cacheKey);
                return $response;
            }

            return [];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    /**
     * Lấy log cuộc gọi từ crm.myauris.vn
     * @param $phone
     * @return array|mixed
     */
    public static function getCallLog($phone) {
        if (!$phone) return [];

        $apiParam = \Yii::$app->getModule('affiliate')->params['myauris_config'];

        $url = $apiParam['url_end_point'] . $apiParam['endpoint']['get_call_log'];

        $client = new Client();

        try {
            $res = $client->request('GET', $url, [
                'headers' => Yii::$app->getModule('affiliate')->params['myauris_config']['headers'],
                'query' => ['phone' => $phone]
            ]);

            $response = \GuzzleHttp\json_decode($res->getBody(), true);

            if ($res->getStatusCode() == 200) {
                return $response;
            }

            return [];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    /**
     * Lấy record bằng số điện thoại
     * @param $phone
     * @return mixed|null
     */
    public static function getCustomerByPhone($phone)
    {
        if (!$phone) return null;

        $cache = Yii::$app->cache;
        $cacheKey = "redis-affiliate-dashboard-myauris-customer-by-phone-{$phone}";
        $params['ClinicSearch']['keyword'] = $phone;

        if ($cache->exists($cacheKey)) return $cache->get($cacheKey);

        $myauris_config = \Yii::$app->getModule('affiliate')->params['myauris_config'];

        $url = $myauris_config['url_end_point'] . $myauris_config['endpoint']['customer'];

        $client = new Client();

        try {
            $res = $client->request('GET', $url, [
                'headers' => Yii::$app->getModule('affiliate')->params['myauris_config']['headers'],
                'query' => $params
            ]);

            $response = \GuzzleHttp\json_decode($res->getBody(), true);

            if ($res->getStatusCode() == 200) {
                if (array_key_exists('data', $response) && count($response['data'])) {
                    $return = $response['data'][0];
                } else {
                    return null;
                }

                $cache->set($cacheKey, $return, self::$CACHE_TIME_CUSTOMER_INFO);
                self::_manageCacheKey($cacheKey);
                return $return;
            }

            return null;
        } catch (GuzzleException $exception) {
            return null;
        }
    }

    /**
     * Lấy thông tin khách hàng theo id
     * @param $customerId
     * @return mixed|null
     */
    public static function getCustomerById($customerId) {
        if (!$customerId) return null;

        $cache = \Yii::$app->cache;
        $cacheKey = 'redis-affiliate-dashboard-myauris-get-customer-info-with-id-' . $customerId;

        if ($cache->exists($cacheKey)) return $cache->get($cacheKey);

        $myauris_config = \Yii::$app->getModule('affiliate')->params['myauris_config'];

        $url = $myauris_config['url_end_point'] . $myauris_config['endpoint']['get_customer'] . "?id={$customerId}";

        $client = new Client();

        try {
            $res = $client->request('GET', $url, [
                'headers' => Yii::$app->getModule('affiliate')->params['myauris_config']['headers'],
            ]);

            $response = \GuzzleHttp\json_decode($res->getBody(), true);

            if ($res->getStatusCode() == 200) {
                if (array_key_exists('data', $response) && count($response['data'])) {
                    $return = $response['data'];
                } else {
                    return null;
                }

                $cache->set($cacheKey, $return, self::$CACHE_TIME_CUSTOMER_INFO);
                // self::_manageCacheKey($cacheKey); // No need to delete cache in this action
                return $return;
            }

            return null;
        } catch (GuzzleException $exception) {
            return null;
        }
    }

    /**
     * Lấy cache key
     * @param $prefix
     * @param string $prefixKey
     * @param array $params
     * @return string
     */
    private function _getCacheKey($prefix, $prefixKey = '', $params = [])
    {
        $cacheKey = $prefix;

        foreach ($params as $key => $value) {
            $cacheKey .= "{$prefixKey}-{$key}-{$value}-";
        }

        return $cacheKey;
    }

    /**
     * Get data provider
     * @param array $response
     * @return ArrayDataProvider
     */
    private function _getDataProvider($response = [])
    {
        if (!count($response)) {
            return new ArrayDataProvider([
                'allModels' => [],
                'pagination' => [
                    'pageSize' => \Yii::$app->getModule('affiliate')->params['myauris_config']['row_per_page'],
                ]
            ]);
        }

        $data = $response['data'];

        /*
         * Mô tả các case:
         * 1. Dữ liệu có 99 dòng, per-page = 10
         * page = 1: index từ 0 -> 9
         * page = 2: index từ 10 -> 19
         * page = 0, -1, abd: => page = 1
         * */
        // Fill fake data to use Array Data Provider for Grid View Pagination
        $rowPerPage = \Yii::$app->getModule('affiliate')->params['myauris_config']['row_per_page'];
        $fakeData = array_fill(0, $response['totalCount'], null);
        $pageForFakeData = $this->_getPage() - 1;
        foreach ($data as $rowIndex => $row) {
            $index = $pageForFakeData * $rowPerPage + $rowIndex;
            $fakeData[$index] = $row;
        }

        return new ArrayDataProvider([
            'allModels' => $fakeData,
            'pagination' => [
                'pageSize' => $rowPerPage,
            ]
        ]);
    }

    /**
     * Lấy số trang (phân trang)
     * @return int
     */
    private function _getPage()
    {
        $page = (int)\Yii::$app->request->get('page');
        return $page > 0 ? $page : 1;
    }

    /**
     * Quản lý cache key
     * @param $cacheKey
     */
    private static function _manageCacheKey($cacheKey)
    {
        $cache = \Yii::$app->cache;

        if ($cache->exists(self::$CACHE_MANAGE_KEY)) {
            $listKey = $cache->get(self::$CACHE_MANAGE_KEY);
            if (!array_key_exists($cacheKey, $listKey)) {
                $listKey[] = $cacheKey;
            }
        } else {
            $listKey = [$cacheKey];
        }

        $cache->set(self::$CACHE_MANAGE_KEY, $listKey);
    }
}