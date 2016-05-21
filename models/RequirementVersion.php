<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement_version".
 *
 * @property integer $id
 * @property integer $requirement_id
 * @property string $title
 * @property integer $version
 * @property integer $revision
 * @property string $wording
 * @property string $justification
 * @property integer $updated
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
            [['requirement_id', 'wording', 'status_id', 'updated'], 'required'],
            [['requirement_id', 'version', 'revision', 'status_id', 'updated'], 'integer'],
            [['title', 'wording', 'justification'], 'string'],
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
            'requirement_id' => Yii::t('app', 'Requirement ID'),
            'title' => Yii::t('app', 'Title'),
            'version' => Yii::t('app', 'Version'),
            'revision' => Yii::t('app', 'Revision'),
            'wording' => Yii::t('app', 'Wording'),
            'justification' => Yii::t('app', 'Justification'),
            'status_id' => Yii::t('app', 'Status'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirement()
    {
        return $this->hasOne(Requirement::className(), ['id' => 'requirement_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }
}
