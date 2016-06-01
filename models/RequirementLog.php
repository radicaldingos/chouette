<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement_log".
 *
 * @property integer $id
 * @property string $event
 * @property integer $requirement_id
 * @property integer $user_id
 * @property integer $date
 *
 * @property Requirement $requirement
 * @property User $user
 */
class RequirementLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requirement_log';
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['event', 'requirement_id', 'user_id', 'date'], 'required'],
            [['requirement_id', 'user_id', 'date'], 'integer'],
            [['event'], 'string', 'max' => 20],
            [['requirement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requirement::className(), 'targetAttribute' => ['requirement_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'event' => Yii::t('app', 'Event'),
            'requirement_id' => Yii::t('app', 'Requirement ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'date' => Yii::t('app', 'Date'),
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
    
    /**
     * Get event litteral string.
     * 
     * @return string
     */
    public function getEventLitteral()
    {
        return Yii::t('app/events', $this->event);
    }
}
