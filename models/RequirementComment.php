<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement_comment".
 *
 * @property integer $id
 * @property string $comment
 * @property integer $requirement_version_id
 * @property integer $user_id
 * @property integer $date_creation
 *
 * @property RequirementVersion $requirement_version
 * @property User $user
 */
class RequirementComment extends \yii\db\ActiveRecord
{
    public $requirementId;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requirement_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requirement_version_id', 'user_id', 'date_creation'], 'required'],
            [['requirement_version_id', 'user_id', 'date_creation', 'requirementId'], 'integer'],
            [['comment'], 'string'],
            [['requirementId'], 'safe'],
            [['requirement_version_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequirementVersion::className(), 'targetAttribute' => ['requirement_version_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'comment' => Yii::t('app', 'Comment'),
            'requirement_version_id' => Yii::t('app', 'Requirement Version ID'),
            'requirementId' => Yii::t('app', 'Requirement ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'date_creation' => Yii::t('app', 'Date Creation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirementVersion()
    {
        return $this->hasOne(RequirementVersion::className(), ['id' => 'requirement_version_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
