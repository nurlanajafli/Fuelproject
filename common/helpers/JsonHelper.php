<?php

namespace common\helpers;

use yii\helpers\Json as BaseJson;
use yii\base\InvalidArgumentException;

class JsonHelper extends BaseJson
{
    /**
     * Encodes the given value into a JSON string.
     *
     * The method enhances `json_encode()` by supporting JavaScript expressions.
     * In particular, the method will not encode a JavaScript expression that is
     * represented in terms of a [[JsExpression]] object.
     *
     * Note that data encoded as JSON must be UTF-8 encoded according to the JSON specification.
     * You must ensure strings passed to this method have proper encoding before passing them.
     *
     * @param mixed $value the data to be encoded.
     * @param int $options the encoding options. For more details please refer to
     * <https://secure.php.net/manual/en/function.json-encode.php>. Default is `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE`.
     * @return string the encoding result.
     * @throws InvalidArgumentException if there is any encoding error.
     */
    public static function encode($value, $options = 320)
    {
        $encoded = parent::encode($value, $options);
        $i = 0;
        $backSlash = '\\';
        while (($i = strpos($encoded, '":"js:', $i + 1)) !== false) {
            $j = $i + 5;
            while (($j = strpos($encoded, '"', $j + 1)) !== false) {
                if (substr($encoded, $j - 1, 2) != ($backSlash . '"')) {
                    break;
                }
            }
            $js = substr($encoded, $i + 6, $j - $i - 6);
            $js = str_replace(['\n', '\t'], '', $js);
            for ($m = 1; $m < 6; $m++) {
                $js = str_replace(str_repeat($backSlash, $m) . '"', str_repeat($backSlash, $m - 1) . '"', $js);
            }
            $encoded = substr($encoded, 0, $i + 2) . $js . substr($encoded, $j + 1);
        }
        return $encoded;
    }
}