<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement_version".
 *
 * @property integer $id
 * @property integer $requirement_id
 * @property integer $version
 * @property integer $revision
 * @property string $statement
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
            [['requirement_id', 'statement', 'updated'], 'required'],
            [['requirement_id', 'version', 'revision', 'updated'], 'integer'],
            [['statement'], 'string'],
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
            'version' => Yii::t('app', 'Version'),
            'revision' => Yii::t('app', 'Revision'),
            'statement' => Yii::t('app', 'Statement'),
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
}
