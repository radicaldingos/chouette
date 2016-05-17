<?php

namespace app\models;

use Yii;
use app\components\ArrayTranslatorHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $order
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'order'], 'required'],
            [['order'], 'integer'],
            [['name'], 'string', 'max' => 20],
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
