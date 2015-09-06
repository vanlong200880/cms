<?php
namespace Sky\Uploads;
class Imaging
{
    private $img_input;
    private $img_output;
    private $img_src;
    private $format;
    private $quality = 80;
    private $x_input;
    private $y_input;
    private $x_output;
    private $y_output;
    private $resize;
    public function set_img($img){
        $ext = strtoupper(pathinfo($img, PATHINFO_EXTENSION));
        if(is_file($img) && ($ext == 'JPG' || $ext == 'JPEG'))
        {
            $this->format = $ext;
            $this->img_input = ImageCreateFromJPEG($img);
            $this->img_src = $img;
        }
        elseif (is_file($img) && $ext == 'PNG') {
            
            $this->format = $ext;
            $this->img_input = ImageCreateFromPNG($img);
            $this->img_src = $img;
            
        }
        elseif (is_file($img) && $ext == 'GIF') {
            $this->format = $ext;
            $this->img_input = imageCreateFromGIF($img);
            $this->img_src = $ext;
        }
        
        $this->x_input = imagesx($this->img_input);
        $this->y_input = imagesy($this->img_input);
    }
    
    public function set_size($max_x = 500, $max_y = 500)
    {
        if($this->x_input > $max_x || $this->y_input > $max_y)
        {
            $a = $max_x/$max_y;
            $b = $this->x_input / $this->y_input;
            if($a < $b)
            {
                $this->x_output = $max_x;
                $this->y_output = ($max_x / $this->x_input) * $this->y_input;
            }
            else
            {
                $this->y_output = $max_y;
                $this->x_output = ($max_y / $this->y_input) * $this->x_input;
            }
            $this->resize = true;
        }
        else
        {
            $this->resize = false;
        }
    }
    
    public function set_quality($quality)
    {
        if(is_int($quality))
        {
            $this->quality = $quality;
        }
    }
    public function save_img($path)
    {
        if($this->resize)
        {
            $this->img_output = imagecreatetruecolor($this->x_output, $this->y_output);
            ImageCopyResampled($this->img_output, $this->img_input, 0, 0, 0, 0, $this->x_output, $this->y_output, $this->x_input, $this->y_input);
        }
        if($this->format == "JPG" || $this->format == "JPEG")
        {
            if($this->resize) {imagejpeg($this->img_output, $path, $this->quality); }
            else { copy($this->img_src, $path); }
        }
        // Save PNG
        elseif($this->format == "PNG")
        {
            if($this->resize) { imagepng($this->img_output, $path); }
            else { copy($this->img_src, $path); }
        }
        // Save GIF
        elseif($this->format == "GIF")
        {
            if($this->resize) { imagegif($this->img_output, $path); }
            else { copy($this->img_src, $path); }
        } 
    }
    // Get width
    public function get_width()
    {
        return $this->x_input;
    }
    // Get height
    public function get_height()
    {
        return $this->y_input;
    }
    // Clear image cache
    public function clear_cache()
    {
        @ImageDestroy($this->img_input);
        @ImageDestroy($this->img_output);
    }
}