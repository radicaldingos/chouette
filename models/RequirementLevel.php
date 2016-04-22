<?php

namespace app\models;

use Yii;

class RequirementLevel
{
    public static function getValues()
    {
        return [
            1 => Yii::t('app', 'General'),
            2 => Yii::t('app', 'Detailed'),
            3 => Yii::t('app', 'Technical'),
        ];
    }
    
    public static function getValue($id)
    {
        return self::getValues()[$id];
    }
}
