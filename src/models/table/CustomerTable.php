<?php

namespace modava\affiliate\models\table;

use cheatsheet\Time;
use modava\affiliate\models\Coupon;
use modava\affiliate\models\Feedback;
use modava\affiliate\models\Note;
use modava\affiliate\models\Partner;
use modava\affiliate\models\UnsatisfiedReason;
use modava\location\models\LocationCountry;
use modava\location\models\LocationDistrict;
use modava\location\models\LocationProvince;
use modava\location\models\LocationWard;
use Yii;
use yii\db\ActiveRecord;
use common\models\User;

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

    public static function getRecordByPhone ($phone) {
        if (!$phone) return null;

        return self::find()->where(['phone' => $phone])->one();
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreated()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserUpdated()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getPartner() {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    public function getNotes() {
        return $this->hasMany(Note::class, ['customer_id' => 'id']);
    }

    public function getFeedbacks() {
        return $this->hasMany(Feedback::class, ['customer_id' => 'id']);
    }

    public function getCoupons() {
        return $this->hasMany(Coupon::class, ['customer_id' => 'id']);
    }

    public function getCountry() {
        return $this->hasOne(LocationCountry::class, ['id' => 'country_id']);
    }

    public function getProvince() {
        return $this->hasOne(LocationProvince::class, ['id' => 'province_id']);
    }

    public function getDistrict() {
        return $this->hasOne(LocationDistrict::class, ['id' => 'district_id']);
    }

    public function getWard() {
        return $this->hasOne(LocationWard::class, ['id' => 'ward_id']);
    }
}
