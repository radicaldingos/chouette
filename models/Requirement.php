<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requirement".
 *
 * @property integer $id
 * @property string $code
 * @property integer $type
 * @property integer $created
 * @property integer $section_id
 * @property integer $status
 * @property integer $priority
 *
 * @property Section $section
 * @property RequirementVersion[] $versions
 * @property RequirementVersion $lastVersion
 * @property RequirementAttachment[] $attachments
 * @property RequirementComment[] $comments
 * @property RequirementEvent[] $events
 * @property RequirementVersion[] $versions
 */
class Requirement extends \yii\db\ActiveRecord
{
    //public $lastVersionStatement;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requirement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'type', 'created', 'section_id'], 'required'],
            [['type', 'created', 'section_id', 'status', 'priority'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::className(), 'targetAttribute' => ['section_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'type' => Yii::t('app', 'Type'),
            'created' => Yii::t('app', 'Created'),
            'section_id' => Yii::t('app', 'Section'),
            'status' => Yii::t('app', 'Status'),
            'priority' => Yii::t('app', 'Priority'),
            //'lastVersionStatement' => Yii::t('app', 'Statement'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersions()
    {
        return $this->hasMany(RequirementVersion::className(), ['requirement_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(RequirementAttachment::className(), ['requirement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(RequirementComment::className(), ['requirement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(RequirementEvent::className(), ['requirement_id' => 'id']);
    }
    
    /**
     * Retrieve the current version of a requirement
     * 
     * @return RequirementVersion
     */
    public function getLastVersion()
    {
        return $this->hasOne(RequirementVersion::className(), ['requirement_id' => 'id'])
            ->orderBy('version DESC, revision DESC');
    }
    
    /**
     * 
     * @param \app\models\RequirementCommentForm $comment
     */
    public function addComment(RequirementCommentForm $data)
    {
        $comment = new RequirementComment;
        $comment->comment = $data->comment;
        $comment->requirement_id = $this->id;
        $comment->user_id = Yii::$app->user->id;
        $comment->date_creation = time();
        if (! $comment->save()) {
            die(print_r($comment->getErrors()));
        }
    }
}
