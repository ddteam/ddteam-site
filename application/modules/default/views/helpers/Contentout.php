<?php

/**
*/
//class Comment_View_Helper_ChildBlock extends Zend_View_Helper_Abstract
class Default_View_Helper_Contentout extends Zend_View_Helper_Abstract
{

    public function contentout($content)
     {
              $width = 15;
              $break = "&shy;";
              $content = htmlspecialchars($content);
              $content = preg_replace("/([\n\r])+/ism", "\n\r", $content);
              $content = trim($content);
              $content=preg_replace("/  +/"," ",$content);
              $content =  preg_replace('#(\S{'.$width.',})#e', "chunk_split('$1', ".$width.", '".$break."')", $content);
              $content = nl2br($content);
               return $content;
       }



    
}