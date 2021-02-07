<?php

namespace modava\affiliate\models\table;

use cheatsheet\Time;
use common\models\User;
use modava\affiliate\models\Coupon;
use modava\affiliate\models\Receipt;
use Yii;
use yii\db\ActiveRecord;

class OrderTable extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'affiliate_order';
    }


    public function afterDelete()
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
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

    /**
     * Lấy coupon
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::class, ['id' => 'coupon_id']);
    }

    /**
     * Lấy danh sách phiếu thu
     * @return \yii\db\ActiveQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::class, ['order_id' => 'id']);
    }
}
