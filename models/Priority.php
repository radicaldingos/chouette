<?php

namespace app\models;

use Yii;
use app\components\ArrayTranslatorHelper;

/**
 * This is the model class for table "priority".
 *
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property integer $order
 */
class Priority extends \yii\db\ActiveRecord
{
    const LOW = 1;
    const NORMAL = 2;
    const HIGH = 3;
    const CRITICAL = 4;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'priority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'color', 'order'], 'required'],
            [['order'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['color'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'color' => Yii::t('app', 'Color'),
            'order' => Yii::t('app', 'Order'),
        ];
    }
    
    public static function getOrderedMappedList()
    {
        return ArrayTranslatorHelper::map(static::find()
                ->orderBy('order')
                ->all(), 'id', 'name');
    }
}
