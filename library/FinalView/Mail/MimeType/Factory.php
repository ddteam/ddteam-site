<?php
class FinalView_Mail_MimeType_Factory
{
    public static function defineMimeType($src)
    {
        switch(true) {
            case in_array(parse_url($src, PHP_URL_SCHEME), array('http', 'https')) :
                $definer = new FinalView_Mail_MimeType_ViaHttp;
                $mimeType = $definer->defineMimeType($src);
                break;

            case file_exists(realpath($src)) :
                $definer = new FinalView_Mail_MimeType_ViaFS;
                $mimeType = $definer->defineMimeType($src);
                break;

            default :
                $mimeType = null;
                break;
        }
        return $mimeType;
    }
}