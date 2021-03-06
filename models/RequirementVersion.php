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
 * @property integer $status_id
 * @property integer $target_release_id
 * @property integer $integrated_release_id
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
            [['requirement_id', 'version', 'revision', 'status_id', 'target_release_id', 'integrated_release_id', 'updated'], 'integer'],
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
            'target_release_id' => Yii::t('app', 'Release ciblée'),
            'integrated_release_id' => Yii::t('app', 'Intégrée dans la release'),
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'target_release_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntegratedRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'integrated_release_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirementComments()
    {
        return $this->hasMany(RequirementComment::className(), ['requirement_version_id' => 'id']);
    }
    
    /**
     * Return complete version litteral
     * 
     * @return string
     */
    public function getCompleteVersion()
    {
        return "{$this->version}.{$this->revision}";
    }
    
    public function updateStatus($statusId)
    {
        $this->status_id = (int) $statusId;
        if (! $this->save()) {
            throw new Exception('Error');
        }
    }
}
