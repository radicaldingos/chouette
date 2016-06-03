<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "release".
 *
 * @property integer $id
 * @property string $version
 * @property integer $date_creation
 *
 * @property RequirementVersion[] $requirementVersions
 */
class Release extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'release';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version', 'date_creation'], 'required'],
            [['date_creation'], 'integer'],
            [['version'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'version' => Yii::t('app', 'Version'),
            'date_creation' => Yii::t('app', 'Creation date'),
        ];
    }
    
    public static function getOrderedMappedList()
    {
        return ArrayHelper::map(static::find()
                ->orderBy('date_creation')
                ->all(), 'id', 'version');
    }
}
