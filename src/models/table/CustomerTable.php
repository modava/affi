<?php

namespace modava\affiliate\models\table;

use cheatsheet\Time;
use Yii;
use yii\db\ActiveRecord;

class CustomerTable extends \yii\db\ActiveRecord
{
    const CACHE_KEY_GET_ALL = 'redis-affiliate-customer-get-all';
    const CACHE_KEY_RECORD_PREFIX = 'redis-affiliate-customer-record';

    public static function tableName()
    {
        return 'affiliate_customer';
    }


    public function afterDelete()
    {
        $cache = Yii::$app->cache;
        $keys = [
            self::CACHE_KEY_GET_ALL,
            $this->getRecordCacheKey(),
        ];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        return parent::beforeDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $cache = Yii::$app->cache;
        $keys = [
            self::CACHE_KEY_GET_ALL
        ];
        foreach ($keys as $key) {
            $cache->delete($key);
        }

        /* Set cache for record customer for fetch with api */
        $cache->set($this->getRecordCacheKey(), $this->getAttributes());

        parent::afterSave($insert, $changedAttributes);
    }

    public function getRecordCacheKey () {
        return self::CACHE_KEY_RECORD_PREFIX . '-partner-' . $this->partner_id . '-id-' . $this->partner_customer_id;
    }

    public static function getRecordByPartnerInfoFromCache ($partnerId, $partnerCustomerId) {
        $cache = Yii::$app->cache;

        $cacheKey = self::CACHE_KEY_RECORD_PREFIX . '-partner-' . $partnerId . '-id-' . $partnerCustomerId;

        if ($cache->exists($cacheKey))
            return $cache->get($cacheKey);

        return null;
    }

    public static function getAllRecords()
    {
        $cache = Yii::$app->cache;
        $data = $cache->get(self::CACHE_KEY_GET_ALL);
        if (!$data) {
            $data = self::find()->all();
            $cache->set(self::CACHE_KEY_GET_ALL, $data, Time::SECONDS_IN_A_YEAR);
        }
        return $data;
    }
}
