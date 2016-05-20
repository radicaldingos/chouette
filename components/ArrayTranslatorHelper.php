<?php

namespace app\components;

use Yii;
use yii\helpers\BaseArrayHelper;

/**
 * Class ArrayTranslatorHelper
 * 
 * Allows the same things than BaseArrayHelper, with integrated translation of
 * values in arrays.
 */
class ArrayTranslatorHelper extends BaseArrayHelper
{
    /**
     * @inheritDoc
     * 
     * Values are translated by Yii::t().
     */
    public static function map($array, $from, $to, $group = null)
    {
        $result = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $from);
            $value = static::getValue($element, $to);
            $value = Yii::t('app', $value);
            if ($group !== null) {
                $result[static::getValue($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
