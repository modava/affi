<?php

namespace modava\affiliate\models\query;

use modava\affiliate\models\UnsatisfiedReason;

/**
 * This is the ActiveQuery class for [[UnsatisfiedReason]].
 *
 * @see UnsatisfiedReason
 */
class UnsatisfiedReasonQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([UnsatisfiedReason::tableName() . '.status' => UnsatisfiedReason::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([UnsatisfiedReason::tableName() . '.status' => UnsatisfiedReason::STATUS_DISABLED]);
    }

    public function sortDescById()
    {
        return $this->orderBy([UnsatisfiedReason::tableName() . '.id' => SORT_DESC]);
    }
}
