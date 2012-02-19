<?php

class GdResizeFillLib
{

    /**
     * Instance of GdThumb passed to this class
     *
     * @var GdThumb
     */
    protected $parentInstance;
    protected $currentDimensions;
    protected $workingImage;
    protected $oldImage;
    protected $options;

    public function resizeFill($wishWidth, $wishHeight, array $bgRGB, $bgOpaque, $that)
    {
        // bring stuff from the parent class into this class...
        $this->parentInstance = $that;
        $this->currentDimensions = $this->parentInstance->getCurrentDimensions();
        $this->oldImage = $this->parentInstance->getOldImage();

        //list($stamp_width, $stamp_height, $stamp_type, $stamp_attr) = getimagesize($mask_file);
        $currWidth = $this->currentDimensions['width'];
        $currHeight = $this->currentDimensions['height'];

        //create empty primitive
        $this->workingImage = imagecreatetruecolor($wishWidth, $wishHeight);

        // if png - transparent bg, else - white
        $colorTransparent = imagecolorallocatealpha(
                        $this->workingImage,
                        $bgRGB[0],
                        $bgRGB[1],
                        $bgRGB[2],
                        $bgOpaque
        );
        imagefill($this->workingImage, 0, 0, $colorTransparent);
        imagesavealpha($this->workingImage, true);

        $d_x = 0;
        $d_y = 0;

        //fill
        switch (true) {

            case ($currHeight > $currWidth):
                $d_x = ($wishWidth - $currWidth) / 2;
            //break specifically omitted

            case ($currHeight < $currWidth):
                $d_y = ($wishHeight - $currHeight) / 2;
            //break specifically omitted

            case ($currWidth < $wishWidth):
                $d_x = ($wishWidth - $currWidth) / 2;
            //break specifically omitted

            case ($currHeight < $wishHeight):
                $d_x = ($wishHeight - $currHeight) / 2;
            //break specifically omitted

            default:
                $d_x = ($wishWidth - $currWidth) / 2;
                $d_y = ($wishHeight - $currHeight) / 2;
                break;
        }

        imagecopy($this->workingImage, $this->oldImage, $d_x, $d_y, 0, 0, $currWidth, $currHeight);

        $this->parentInstance->setOldImage($this->workingImage);
        $this->currentDimensions['width'] = $wishWidth;
        $this->currentDimensions['height'] = $wishHeight;
        $this->parentInstance->setCurrentDimensions($this->currentDimensions);

        return $that;
    }

}

$pt = PhpThumb::getInstance();
$pt->registerPlugin('GdResizeFillLib', 'gd');

