<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement_version".
 *
 * @property integer $id
 * @property integer $type
 * @property string $code
 * @property integer $requirement_id
 * @property integer $version
 * @property integer $revision
 * @property string $title
 * @property string $description
 * @property integer $priority
 * @property integer $updated
 * @property integer $status
 *
 * @property Requirement $requirement
 */
class RequirementVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requirement_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'code', 'requirement_id', 'title', 'updated'], 'required'],
            [['type', 'requirement_id', 'version', 'revision', 'priority', 'updated', 'status'], 'integer'],
            [['title', 'description'], 'string'],
            [['code'], 'string', 'max' => 10],
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
            'type' => Yii::t('app', 'Type'), 
            'code' => Yii::t('app', 'Code'),
            'requirement_id' => Yii::t('app', 'Requirement ID'),
            'version' => Yii::t('app', 'Version'),
            'revision' => Yii::t('app', 'Revision'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'priority' => Yii::t('app', 'Priority'), 
            'updated' => Yii::t('app', 'Updated'),
            'status' => Yii::t('app', 'Status'),
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
