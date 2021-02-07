<?php

namespace modava\affiliate\models\query;

use modava\affiliate\models\SmsLog;

/**
 * This is the ActiveQuery class for [[SmsLog]].
 *
 * @see SmsLog
 */
class SmsLogQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([SmsLog::tableName() . '.status' => SmsLog::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([SmsLog::tableName() . '.status' => SmsLog::STATUS_DISABLED]);
    }

    public function sortDescById()
    {
        return $this->orderBy([SmsLog::tableName() . '.id' => SORT_DESC]);
    }
}
