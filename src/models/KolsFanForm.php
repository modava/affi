<?php


namespace modava\affiliate\models;

use yii\base\Model;


class KolsFanForm extends Model
{
    public $name;
    public $phone;
    public $coupon_id;

    public function rules()
    {
        return [
            [['name', 'phone', 'coupon_id'], 'required'],
            [['name', 'phone'], 'string'],
            ['phone', 'string', 'max' => 15],
        ];
    }
}