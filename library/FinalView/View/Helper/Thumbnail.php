<?php

/**
 * @author Andrey Kozelecky
 * @corrections dV
 */
class FinalView_View_Helper_Thumbnail extends Zend_View_Helper_HtmlElement
{

    /**
     * View attributes (width, height, format) override action helper params.
     *
     * @param actHelpersParams array(*url, *width, *height, crop, destination, format)
     * @param $viewAttribs array(width, height, format ...) etc <img> attributes
     *
     * @return string tumb path or '' if paramenters[url] - folder
     *
     */
    public function thumbnail(array $actHelperParams, array $viewAttribs = array())
    {
        // action helper function links
        $width = 0;
        $height = 0;

        if (array_key_exists('format', $viewAttribs)) {
            $actHelperParams['format'] = $viewAttribs['format'];
        }

        $thumpHelp = Zend_Controller_Action_HelperBroker::getStaticHelper('Thumbnail');

        $viewAttribs['src'] = $thumpHelp->thumb($actHelperParams, $width, $height);

        $viewAttribs['width'] = array_key_exists('width', $viewAttribs) ? $viewAttribs['width'] : $width;
        $viewAttribs['height'] = array_key_exists('height', $viewAttribs) ? $viewAttribs['height'] : $height;

        return '<img' . $this->_htmlAttribs($viewAttribs) . $this->getClosingBracket() . PHP_EOL;
    }

}
