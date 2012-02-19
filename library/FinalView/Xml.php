<?php

class FinalView_Xml
{

    /**
     * Generate XML from array or object.
     *
     * @param Array|Object $input
     * @param string $rootTag First XML tag
     * @param string $node Array|Obj key
     * @return string valid XML
     */
    public function generateXml($input, $rootTag='root', $node='node')
    {

        if (!is_object($input) && !is_array($input)) {
            throw new Exception('Input must be array or object.');
        }

        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';

        $xml .= '<' . $rootTag . '>';
        $xml .= self::generateXmlFromArray($input, $node);
        $xml .= '</' . $rootTag . '>';

        return $xml;
    }

    private static function generateXmlFromArray($array, $node)
    {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key => $value) {
                if (is_numeric($key)) {
                    $key = $node;
                }

                $xml .= '<' . $key . '>' . self::generateXmlFromArray($value, $node) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES);
        }

        return $xml;
    }

}

?>
