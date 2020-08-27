<?php

namespace modava\affiliate\models\query;

use modava\affiliate\models\Receipt;

/**
 * This is the ActiveQuery class for [[Receipt]].
 *
 * @see Receipt
 */
class ReceiptQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([Receipt::tableName() . '.status' => Receipt::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([Receipt::tableName() . '.status' => Receipt::STATUS_DISABLED]);
    }

    public function sortDescById()
    {
        return $this->orderBy([Receipt::tableName() . '.id' => SORT_DESC]);
    }
}
