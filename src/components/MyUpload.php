<?php
namespace modava\affiliate\components;

use modava\imagick\Imagick;
use yii\base\Component;

class MyUpload extends Component
{
    public static function upload($width, $height, $pathImage, $pathSave)
    {
        $imagick = new Imagick($pathImage, true);
        return $imagick->resizeImage($width, $height)->saveTo($pathSave);
    }
}
