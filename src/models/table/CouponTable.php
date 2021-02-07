<?php

namespace modava\affiliate\models\table;

use cheatsheet\Time;
use Yii;
use yii\db\ActiveRecord;

class CouponTable extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'affiliate_coupon';
    }


    public function afterDelete()
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        return parent::beforeDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public static function getAll() {
        return self::find()->all();
    }
}
