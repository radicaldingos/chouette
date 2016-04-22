<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement_attachment".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property integer $requirement_id
 *
 * @property Requirement $requirement
 */
class RequirementAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requirement_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'requirement_id'], 'required'],
            [['path'], 'string'],
            [['requirement_id'], 'integer'],
            [['name'], 'string', 'max' => 40],
            [['requirement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requirement::className(), 'targetAttribute' => ['requirement_id' => 'id']],
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
            'path' => Yii::t('app', 'Path'),
            'requirement_id' => Yii::t('app', 'Requirement ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirement()
    {
        return $this->hasOne(Requirement::className(), ['id' => 'requirement_id']);
    }
}
