<?php

namespace modava\affiliate\models\query;

use modava\affiliate\models\Phonebook;

/**
 * This is the ActiveQuery class for [[Phonebook]].
 *
 * @see Phonebook
 */
class PhonebookQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([Phonebook::tableName() . '.status' => Phonebook::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([Phonebook::tableName() . '.status' => Phonebook::STATUS_DISABLED]);
    }

    public function sortDescById()
    {
        return $this->orderBy([Phonebook::tableName() . '.id' => SORT_DESC]);
    }
}
