<?php

namespace app\models;

use Yii;

class RequirementStatus
{
    const NEW_REQUIREMENT = 1;
    
    public static function getValues()
    {
        return [
            self::NEW_REQUIREMENT => Yii::t('app', 'New'),
            2 => Yii::t('app', 'Accepted'),
            3 => Yii::t('app', 'Validated'),
            4 => Yii::t('app', 'Refused'),
            5 => Yii::t('app', 'Implemented'),
        ];
    }
    
    public static function getValue($id)
    {
        return self::getValues()[$id];
    }
}
