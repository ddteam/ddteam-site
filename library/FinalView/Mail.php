<?php

/**
 * Parse and send mail templates
 *
 * @author dV
 */
class FinalView_Mail
{

    const DEFAULT_CHARSET = 'utf-8';

    /**
    * Zend_Mail
    *
    * @var Zend_Mail
    */
    protected $_mailer;

    /**
    * Template
    *
    * @var FinalView_Mail_Template_Interface
    */
    protected $_template;

    /**
    * Vars to parse in template
    *
    * @var array
    */
    protected $_vars = array();

    public function __construct(FinalView_Mail_Template_Interface $template,
        array $vars = array(), $charset = self::DEFAULT_CHARSET)
    {
        $this->_mailer = new Zend_Mail($charset);
        $this->_mailer->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);

        $this->_template = $template;
        $this->_vars = $vars;
    }

    /**
    * Set template vars
    *
    * @param array $vars
    */
    public function setVars(array $vars)
    {
        $this->_vars = $vars;
        return $this;
    }

    /**
    * Set default vars
    *
    */
    protected function _getDefaultVars()
    {
        $vars = array();

        if (defined('BASE_PATH')) {
            $vars['BASE_PATH'] = BASE_PATH;
        }

        return $vars;
    }

    /**
     * Magic method for calling Zend_Mail methods
     *
     * @param string $method_name
     * @param array $arguments
     * @return mixed
     */
    public function __call($method_name, $arguments)
    {
        return call_user_func_array(array($this->_mailer, $method_name), $arguments);
    }

    /**
    * Send email
    *
    * @param string|array $email
    * @param string $name
    */
    public function send($email = null, $name = '')
    {
        if (!is_null($email)) {
            if (is_array($email)) {
                foreach ($email as $_email => $_name) {
                    is_string($_email)
                        ? $this->_mailer->addTo($_email, $_name)
                        : $this->_mailer->addTo($_name);
                }
            } else {
                $this->_mailer->addTo($email, $name);
            }
        }

        $this->setSubject($this->_parse($this->_template->getSubject()));
        $this->setBodyText($this->_parse($this->_template->getBodyText()));
        $this->setBodyHtml($this->_parse($this->_template->getBodyHtml()));
        $this->_mailer->send();
    }

    /**
    * Parse vars in text
    *
    * @param string $string
    * @return string
    */
    protected function _parse($string)
    {
        $vars = $this->_vars + $this->_getDefaultVars();

        return strtr($string, array_combine(
                array_map
                (
                    function ($var) { return '{$' . $var . '}'; },
                    array_keys($vars)
                ),
                $vars
            ));
    }
    
    protected function _replaceImg($match)
    {
        $src = $match[1];
        if ($mimeType = FinalView_Mail_MimeType_Factory::defineMimeType($src)) {
            $contentID = $this->addAttachment($src, file_get_contents($src), $mimeType);
            return '<img src="cid:' . $contentID . '"/>';
        }else{
            throw new FinalView_Mail_Exception('Mime type cannot be defined for source: '. $src);
        }
    }


    protected function _replaceDocument($match)
    {
        $src = $match[1];
        $title = $match[2];
        if ($mimeType = FinalView_Mail_MimeType_Factory::defineMimeType($src)) {
            $contentID = $this->addAttachment($src, file_get_contents($src), $mimeType);
            return '<a href="cid:' . $contentID . '">' . $title . '</a>';
        }else{
            throw new FinalView_Mail_Exception('Mime type cannot be defined for source: '. $src);
        }
    }

    /**
     * Sets the HTML body for the message
     *
     * @param  string    $html
     * @param  string    $charset
     * @param  string    $encoding
     * @return Zend_Mail Provides fluent interface
     */
    public function setBodyHtml($html, $charset = null,
        $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE)
    {

        $html = preg_replace_callback('/{img\s+src="([^"]*)"}/', array($this, '_replaceImg'), $html, -1, $img_count);

        $html = preg_replace_callback('/{document\s+src="([^"]*)"\s+title="([^"]*)"}/', array($this, '_replaceDocument'), $html, -1, $doc_count);

        if ($img_count + $doc_count) {
            $this->_mailer->setType(Zend_Mime::MULTIPART_RELATED);
        }
        
        return $this->_mailer->setBodyHtml($html, $charset, $encoding);
    }

    /**
     * Add attachemnt and return content id
     *
     * @param string $filename
     * @param string $content
     * @param string $mimeType
     * @return string
     */
    public function addAttachment($filename, $content, $mimeType)
    {
        $attachment = new Zend_Mime_Part($content);
        $attachment->id          = md5_file($filename);
        $attachment->filename    = pathinfo($filename, PATHINFO_BASENAME);

        $attachment->type        = $mimeType;
        $attachment->disposition = Zend_Mime::DISPOSITION_INLINE;
        $attachment->encoding    = Zend_Mime::ENCODING_BASE64;

        $this->_mailer->addAttachment($attachment);

        return $attachment->id;
    }

}
