<?php

namespace app\models;

use Yii;
use app\models\Item;
use app\models\RequirementCategory;

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
class Requirement extends Item
{
    const TYPE = 'Requirement';

    public function init()
    {
        $this->type = self::TYPE;
        parent::init();
    }

    public static function find()
    {
        return new ItemQuery(get_called_class(), ['type' => self::TYPE]);
    }

    public function beforeSave($insert)
    {
        $this->type = self::TYPE;
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category', 'created', 'status', 'priority', 'project_id', 'type'], 'required'],
            [['category', 'created', 'status', 'priority', 'project_id'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 40],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
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
            'name' => Yii::t('app', 'Name'),
            'category' => Yii::t('app', 'Category'),
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
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
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
     * Retrieve the attributes for DetailView
     * 
     * @return array
     */
    public function getDetailAttributes()
    {
        return [
            [
                'attribute' => 'category',
                'value' => RequirementCategory::getValue($this->category),
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'attribute' => 'lastVersion.status',
                'value' => RequirementStatus::getValue($this->status),
            ],
            [
                'label' => Yii::t('app', 'Version'),
                'value' => "{$this->lastVersion->version}.{$this->lastVersion->revision}",
            ],
            'lastVersion.statement',
            'priority',
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute' => 'lastVersion.updated',
                'format' => ['date', 'php:d/m/Y'],
            ],
        ];
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
    
    /**
     * Generate a unique code for a new requirement
     * 
     * @return string Generated code
     */
    public static function generateCodeFromPattern()
    {
        return 'TEMP';
        
        $pattern = '{project.name}_{document.code}_{section.code}_{serial}';
        
        $session = Yii::$app->session;
        $session->set('selected_project', Project::findOne('1'));
        $session->set('selected_document', Document::findOne('1'));
        $session->set('selected_section', Section::findOne('1'));
        
        $vars = [
            'project.name' => $session->get('selected_project')->name,
            'document.code' => $session['selected_document']->code,
            'section.code' => $session['selected_section']->code,
            'serial' => '01',
        ];
        
        $code = $pattern;
        
        foreach ($vars as $key => $var) {
            $code = str_replace('{' . $key . '}', $var, $code);
        }
        
        return $code;
    }
}
