<?php

namespace app\models;

use Yii;

class RequirementCategory
{
    public static function getValues()
    {
        return [
            1 => Yii::t('app', 'Functional'),
            2 => Yii::t('app', 'Security'),
            3 => Yii::t('app', 'Availability'),
            4 => Yii::t('app', 'Performance'),
            5 => Yii::t('app', 'Ergonomics'),
            6 => Yii::t('app', 'Testability'),
            7 => Yii::t('app', 'Constraint'),
            99 => Yii::t('app', 'Other'),
        ];
    }
    
    public static function getValue($id)
    {
        return self::getValues()[$id];
    }
}
