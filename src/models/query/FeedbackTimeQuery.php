<?php

namespace modava\affiliate\models\query;

use modava\affiliate\models\FeedbackTime;

/**
 * This is the ActiveQuery class for [[FeedbackTime]].
 *
 * @see FeedbackTime
 */
class FeedbackTimeQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([FeedbackTime::tableName() . '.status' => FeedbackTime::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([FeedbackTime::tableName() . '.status' => FeedbackTime::STATUS_DISABLED]);
    }

    public function sortDescById()
    {
        return $this->orderBy([FeedbackTime::tableName() . '.id' => SORT_DESC]);
    }
}
