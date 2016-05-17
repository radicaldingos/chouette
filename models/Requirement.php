<?php

namespace app\models;

use Yii;
use app\models\Item;

/**
 * This is the model class for table "requirement".
 *
 * @property Section $section
 * @property RequirementVersion[] $versions
 * @property RequirementVersion $lastVersion
 * @property RequirementAttachment[] $attachments
 * @property RequirementComment[] $comments
 * @property RequirementEvent[] $events
 * @property RequirementVersion[] $versions
 * @property Priority $priority
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
            [['name', 'category_id', 'created', 'status_id', 'priority_id', 'project_id', 'type'], 'required'],
            [['category_id', 'created', 'status_id', 'priority_id', 'project_id'], 'integer'],
            [['reference'], 'string', 'max' => 255],
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
            'reference' => Yii::t('app', 'Reference'),
            'name' => Yii::t('app', 'Name'),
            'category_id' => Yii::t('app', 'Category'),
            'created' => Yii::t('app', 'Created'),
            'section_id' => Yii::t('app', 'Section'),
            'status_id' => Yii::t('app', 'Status'),
            'priority_id' => Yii::t('app', 'Priority'),
            //'lastVersionWording' => Yii::t('app', 'Wording'),
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
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
    public function getPriority()
    {
        return $this->hasOne(Priority::className(), ['id' => 'priority_id']);
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
                'label' => Yii::t('app', 'Category'),
                'value' => Yii::t('app', $this->category->name),
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'value' => Yii::t('app', $this->status->name),
            ],
            [
                'label' => Yii::t('app', 'Version'),
                'value' => "{$this->lastVersion->version}.{$this->lastVersion->revision}",
            ],
            'lastVersion.title',
            'lastVersion.wording',
            'lastVersion.justification',
            [
                'label' => Yii::t('app', 'Priority'),
                'value' => Yii::t('app', $this->priority->name),
            ],
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
     * Generate a unique reference for a new requirement
     * 
     * @return string Generated reference
     */
    public static function generateReferenceFromPattern()
    {
        return 'TEMP';
        
        $pattern = '{project.name}_{section.reference}_{serial}';
        
        $session = Yii::$app->session;
        $session->set('selected_project', Project::findOne('1'));
        $session->set('selected_section', Section::findOne('1'));
        
        $vars = [
            'project.name' => $session->get('selected_project')->name,
            'section.reference' => $session['selected_section']->reference,
            'serial' => '01',
        ];
        
        $reference = $pattern;
        
        foreach ($vars as $key => $var) {
            $reference = str_replace('{' . $key . '}', $var, $reference);
        }
        
        return $reference;
    }
}
