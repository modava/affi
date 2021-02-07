<?php


namespace modava\affiliate\models;

use yii\base\Model;


class KolsFanForm extends Model
{
    /**
     * @var string Tên khách hàng
     */
    public $name;

    /**
     * @var string Số điện thoại
     */
    public $phone;

    /**
     * @var int id coupon
     */
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