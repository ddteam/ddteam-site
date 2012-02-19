<?php

/**
 * ThumbNail action helper.
 */
class FinalView_Controller_Action_Helper_Thumbnail extends Zend_Controller_Action_Helper_Abstract
{

    private $_width;
    private $_height;
    private $_src;
    private $_crop;
    private $_destination;
    private $_imgPath;
    private $_format;

    /**
     *
     * file template: filename'_'width'x'height'.'extension
     *
     * url - with first slash ('/') - absolute path.
     * 
     * crop flags: [T, B, C, L, R, P, PF]. Default - PF.
     * +---+---+---+
     * |   | T |   |
     * +---+---+---+
     * | L | C | R |
     * +---+---+---+
     * |   | B |   |
     * +---+---+---+
     * P - proportionally, (!) should note that the input and output image proportions are saved,
     * can be disproportionately. Don't resize if the dimensions is less than desired.
     * PF - proportionally, set missing bg transparent (png) or white color.
     * CC - crop with coordinates, responce params: x, y.
     *
     * format: [GIF,JPG,PNG]
     *
     * bg: R.G.B:opaque - opaque a value between 0 and 127, 0 indicates completely opaque while, 127 indicates completely transparent.
     * default - 255.255.255:0 - white for all formats. If used x.x.x - opaque = 0.
     * 
     * destination - new destination folder
     * 
     * name  - new filename, override all (original name and format).
     *
     * @param array(*url, *width, *height, bg, crop, destination, name, format, coords[x,y])
     * @param &$width
     * @param &$height
     *
     * @return string tumb path or '' if paramenters[url] - folder
     *
     */
    public function thumb(array $parameters, &$width = NULL, &$height = NULL)
    {
        if (
                !array_key_exists('url', $parameters) ||
                !array_key_exists('width', $parameters) ||
                !array_key_exists('height', $parameters)
        ) {
            throw new Exception('thumbnail action helper required parameter missed');
        }

        $this->_width = $parameters['width'];
        $this->_height = $parameters['height'];
        $this->_crop = array_key_exists('crop', $parameters) ? $parameters['crop'] : 'PF';

        $pathinfo = pathinfo($parameters['url']);

        // if directory
        if (empty($pathinfo['filename']))
            return '';

        $this->_format = array_key_exists('format', $parameters) ? strtolower($parameters['format']) : $pathinfo['extension'];

        // destination
        if (array_key_exists('destination', $parameters)) {
            $this->_destination = (substr($parameters['destination'], -1) === DIRECTORY_SEPARATOR ) ?
                    $parameters['destination'] :
                    $parameters['destination'] . DIRECTORY_SEPARATOR;
        } else {
            //if first '/' - absolute path   
            $this->_destination = $pathinfo['dirname'] . DIRECTORY_SEPARATOR;
//            $this->_destination = ( substr($pathinfo['dirname'], 0, 1) === DIRECTORY_SEPARATOR ) ?
//                    $pathinfo['dirname'] . DIRECTORY_SEPARATOR :
//                    $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $pathinfo['dirname'] . DIRECTORY_SEPARATOR;
        }

        // file name
        $fileName = array_key_exists('name', $parameters) ? $parameters['name'] :
                ($pathinfo['filename'] . '_' . $this->_width . 'x' . $this->_height . '.' . $this->_format);

        // new image src path
        $this->_src = $this->_destination . $fileName;

        // old image src path
        $this->_imgPath = ( substr($pathinfo['dirname'], 0, 1) == DIRECTORY_SEPARATOR ) ?
                $parameters['url'] :
                $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $parameters['url'];

        if (!is_file($this->_src)) {
            $umask = umask(0);
            @mkdir($this->_destination, 0777, true);
            umask($umask);

            require_once LIBRARY_PATH . '/PHPThumb/ThumbLib.inc.php';

            $image = PhpThumbFactory::create($this->_imgPath);

            switch ($this->_crop) {
                case 'P':
                    $image->resize($this->_width, $this->_height);
                    break;

                case 'PF':
                    $bgRGB = array(255, 255, 255);
                    $bgOpaque = 0;

                    if (array_key_exists('bg', $parameters)) {
                        //$bgRGB[0, 1, 2] - R, G, B; $bgOpaque 0-127
                        $tmp = explode(':', $parameters['bg'], 2);
                        $bgRGB = explode('.', $tmp[0]);
                        $bgOpaque = array_key_exists(1, $tmp) ? (int) $tmp[1] : 0;
                    }

                    $image->resize($this->_width, $this->_height)->resizeFill($this->_width, $this->_height, $bgRGB, $bgOpaque);
                    break;

                case 'CC':
                    $cropX = $cropY = 0;
                    if (array_key_exists('coords', $parameters)) {
                        $cropX = $parameters['coords']['x'];
                        $cropY = $parameters['coords']['y'];
                    }

                    $image->crop($cropX, $cropY, $this->_width, $this->_height);
                    break;

                default:
                    $image->adaptiveResizeQuadrant($this->_width, $this->_height, $this->_crop);
                    break;
            }

            $image->save($this->_src, $this->_format);
        }

        // links on image width and height
        list($width, $height) = getimagesize($this->_src);

        return $this->_src;
    }

}