<?php

namespace modava\affiliate\models\table;

use backend\components\MyModel;
use Yii;

class NoteTable extends MyModel
{
    public static function tableName()
    {
        return 'affiliate_note';
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
}
