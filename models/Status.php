<?php

namespace app\models;

use Yii;
use app\components\ArrayTranslatorHelper;

/**
 * This is the model class for table "status".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $color
 */
class Status extends \yii\db\ActiveRecord
{
    const NEW_REQUIREMENT = 1;
    const ACCEPTED = 2;
    const VALIDATED = 3;
    const REJECTED = 4;
    const IMPLEMENTED = 5;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'color'], 'required'],
            [['code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 30],
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
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'color' => Yii::t('app', 'Color'),
        ];
    }
    
    public static function getOrderedMappedList()
    {
        return ArrayTranslatorHelper::map(static::find()
                ->orderBy('order')
                ->all(), 'id', 'name');
    }
}
