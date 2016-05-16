<?php

namespace app\models;

use Yii;

class Priority
{
    public static function getItem($id)
    {
        $items = [
            1 => Yii::t('app', 'High'),
            2 => Yii::t('app', 'Medium'),
            3 => Yii::t('app', 'Low'),
        ];
        
        return isset($items[$id]) ? $items[$id] : null;
    }
}
