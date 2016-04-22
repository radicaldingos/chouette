<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement_comment".
 *
 * @property integer $id
 * @property string $comment
 * @property integer $requirement_id
 * @property integer $user_id
 * @property integer $date_creation
 *
 * @property Requirement $requirement
 * @property User $user
 */
class RequirementComment extends \yii\db\ActiveRecord
{
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
            [['comment'], 'string'],
            [['requirement_id', 'user_id', 'date_creation'], 'required'],
            [['requirement_id', 'user_id', 'date_creation'], 'integer'],
            [['requirement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requirement::className(), 'targetAttribute' => ['requirement_id' => 'id']],
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
            'requirement_id' => Yii::t('app', 'Requirement ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'date_creation' => Yii::t('app', 'Date Creation'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
