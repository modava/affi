<?php

namespace modava\affiliate\models\table;

use cheatsheet\Time;
use Yii;
use yii\db\ActiveRecord;

class CouponTypeTable extends \yii\db\ActiveRecord
{
    const CACHE_KEY_GET_ALL = 'redis-affiliate-coupon-type-get-all';

    public static function tableName()
    {
        return 'coupon_type';
    }


    public function afterDelete()
    {
        $cache = Yii::$app->cache;
        $keys = [
            self::CACHE_KEY_GET_ALL
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
        parent::afterSave($insert, $changedAttributes);
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
