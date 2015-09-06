<?php
namespace Sky\Uploads;

use Sky\Uploads\Imaging;

class Thumbs extends Imaging
{
//    public function __construct($image, $arrayWidth = null,$arrayHeight = null ,$arrayPath = null, $item = 1, $text = '' ) {
//        parent::set_img($image);
//        parent::set_quality(80);
//        for ($i = 1; $i <= $item; $i++){
//            parent::set_size($arrayWidth[$i],$arrayHeight[$i]);
//            $this->thumbnail= $arrayPath[$i].pathinfo($image, PATHINFO_FILENAME).'-'. $text. '.' . pathinfo($image, PATHINFO_EXTENSION);
//            $this->thumbname = pathinfo($image, PATHINFO_FILENAME).'-'. $text. '.' .pathinfo($image, PATHINFO_EXTENSION);
//            parent::save_img($this->thumbnail);
//            parent::clear_cache();
//        }
//    }
//    
    public function createThumb($image, $arrayWidth = null,$arrayHeight = null ,$arrayPath = null, $item = 1, $text = '' )
    {
        parent::set_img($image);
        parent::set_quality(80);
        for ($i = 1; $i <= $item; $i++){
            parent::set_size($arrayWidth[$i],$arrayHeight[$i]);
            $this->thumbnail= $arrayPath[$i].pathinfo($image, PATHINFO_FILENAME).'-'. $text. '.' . pathinfo($image, PATHINFO_EXTENSION);
            $this->thumbname = pathinfo($image, PATHINFO_FILENAME).'-'. $text. '.' .pathinfo($image, PATHINFO_EXTENSION);
            parent::save_img($this->thumbnail);
            parent::clear_cache();
        }
    }

    public function removeImage($link, $folder, $name , $item = 1 )
    {
        for ($i = 1; $i <= $item; $i++){
            @unlink($link . $folder[$i] . $name);
        }
    }

    public function __toString() {
            return $this->thumbnail;
    }
}

