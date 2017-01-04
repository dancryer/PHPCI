<?php

namespace PHPCI\Framework;

class Image
{
    public static $cachePath = '/tmp/';
    public static $sourcePath = './';


    /**
     * @var \Imagick
     */
    protected $source;

    public function __construct($imagePath)
    {
        $this->setSource(new \Imagick(self::$sourcePath . $imagePath));
    }

    /**
     * @return \Imagick
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param \Imagick $image
     */
    public function setSource(\Imagick $image)
    {
        $this->source = $image;
    }

    public function render($media, $width, $height, $format = 'jpeg')
    {
        $cachePath = self::$cachePath . $media['fileId'] . '.' . $width  . 'x' . $height . '.' . $format;

        if(file_exists($cachePath) && 0)
        {
            $output = file_get_contents($cachePath);
        }
        else
        {
            $output = $this->doRender($media, $width, $height, $format);
            file_put_contents($cachePath, $output);
        }

        return $output;
    }

    public function doRender($media, $width, $height, $format = 'jpeg')
    {
        $focal                  = !empty($media['focal_point']) ? $media['focal_point'] : array(0, 0);
        $focalX = (int)$focal[0];
        $focalY = (int)$focal[1];

        $width          = (int)$width;
        $height         = (int)$height;

        $source         = $this->getSource();
        $sourceWidth    = $source->getImageWidth();
        $sourceHeight   = $source->getImageHeight();
        $sourceRatio    = $sourceWidth / $sourceHeight;
        $targetRatio    = $height != 'auto' ? $width / $height : $sourceRatio;

        $quads          = $this->_getQuadrants($sourceWidth, $sourceHeight);

        foreach($quads as $name => $l)
        {
            if($focalX >= $l[0] && $focalX <= $l[1] && $focalY >= $l[2] && $focalY <= $l[3])
            {
                $useQuad = $name;
            }
        }

        if($sourceRatio <= $targetRatio)
        {
            $scale = $sourceWidth / $width;
        }
        else
        {
            $scale = $sourceHeight / $height;
        }

        $resizeWidth = (int)($sourceWidth / $scale);
        $resizeHeight = (int)($sourceHeight / $scale);

        if($height == 'auto')
        {
            $height = $resizeHeight;
        }

        $source->scaleImage($resizeWidth, $resizeHeight);

        switch($useQuad)
        {
            case 'top_left':
                $cropX = 0;
                $cropY = 0;
                break;

            case 'top_right':
                $cropX = ($resizeWidth - $width);
                $cropY = 0;
                break;

            case 'middle_left':
                $cropX = 0;
                $cropY = ($resizeHeight - $height) / 2;
                break;

            case 'middle-right':
                $cropX = ($resizeWidth - $width);
                $cropY = ($resizeHeight - $height) / 2;
                break;

            case 'bottom_left':
                $cropX = 0;
                $cropY = ($resizeHeight - $height);
                break;

            case 'bottom_right':
                $cropX = ($resizeWidth - $width);
                $cropY = ($resizeHeight - $height);
                break;
        }

        $source->cropImage($width, $height, $cropX, $cropY);
        $source->setImageFormat($format);

        return $source;
    }

    protected function _getQuadrants($x, $y)
    {
        $rtn                    = array();
        $rtn['top_left']        = array(0, $x / 2, 0, $y / 3);
        $rtn['top_right']       = array(($x / 2) + 1, $x, 0, $y / 3);
        $rtn['middle_left']     = array(0, $y / 2, ($y / 3)+1, (($y / 3) * 2));
        $rtn['middle_right']    = array(($x / 2) + 1, $x, ($y / 3)+1, (($y / 3) * 2));
        $rtn['bottom_left']     = array(0, $y / 2, (($y / 3) * 2)+1, $y);
        $rtn['bottom_right']    = array(($x / 2) + 1, $x, (($y / 3) * 2)+1, $y);

        return $rtn;
    }
}