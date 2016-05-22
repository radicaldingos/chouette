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
 * @property RequirementLog[] $logs
 * @property RequirementVersion[] $versions
 * @property Priority $priority
 */
class Requirement extends Item
{
    // Item type
    const TYPE = 'Requirement';
    
    // Events
    const EVENT_CREATE = 'create';
    const EVENT_UPDATE = 'update';
    const EVENT_ARCHIVE = 'archive';
    const EVENT_REVISION = 'revision';
    const EVENT_VERSION = 'version';
    const EVENT_POST = 'post';

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->type = self::TYPE;
        
        $this->on(self::EVENT_CREATE, ['app\components\RequirementLogEventHandler', 'log']);
        $this->on(self::EVENT_UPDATE, ['app\components\RequirementLogEventHandler', 'log']);
        $this->on(self::EVENT_ARCHIVE, ['app\components\RequirementLogEventHandler', 'log']);
        $this->on(self::EVENT_REVISION, ['app\components\RequirementLogEventHandler', 'log']);
        $this->on(self::EVENT_VERSION, ['app\components\RequirementLogEventHandler', 'log']);
        $this->on(self::EVENT_POST, ['app\components\RequirementLogEventHandler', 'log']);
        
        parent::init();
    }

    /*public static function find()
    {
        return new ItemQuery(get_called_class(), ['type' => self::TYPE]);
    }*/

    /**
     * @inheritDoc
     */
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
            [['name', 'category_id', 'created', 'priority_id', 'project_id', 'type'], 'required'],
            [['category_id', 'created', 'priority_id', 'project_id'], 'integer'],
            [['reference'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 40],
            [['archive'], 'boolean'],
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
            'priority_id' => Yii::t('app', 'Priority'),
            'archive' => Yii::t('app', 'Archive ?'),
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
    public function getLogs()
    {
        return $this->hasMany(RequirementLog::className(), ['requirement_id' => 'id']);
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
                'value' => Yii::t('app', $this->lastVersion->status->name),
            ],
            [
                'label' => Yii::t('app', 'Version'),
                'value' => $this->lastVersion->getCompleteVersion(),
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
        $comment->requirement_version_id = $this->lastVersion->id;
        $comment->user_id = Yii::$app->user->id;
        $comment->date_creation = time();
        if (! $comment->save()) {
            die(print_r($comment->getErrors()));
        }
    }
    
    /**
     * Generate a unique reference for a new requirement
     * 
     * If serial number is needed, the next serial available is determined by
     * searching the last serial giver.
     * 
     * @return string Generated reference
     */
    public static function generateReferenceFromPattern()
    {
        $project = Yii::$app->session->get('user.current_project');
        $section = Yii::$app->session->get('user.current_section');
        
        $vars = [
            'project.name' => $project ? $project->name : Yii::t('app', 'PROJECT'),
            'section.reference' => $section ? $section->reference : Yii::t('app', 'SECTION'),
            'serial' => '%',
        ];

        $generatedRef = $project->requirement_pattern;
        
        foreach ($vars as $key => $var) {
            $generatedRef = str_replace('{' . $key . '}', $var, $generatedRef);
        }
        
        if ($x = strpos($generatedRef, '%')) {
            // We try do determine the last serial given to define the next serial
            // available
            $last = Requirement::find()
                ->where('reference LIKE :ref', ['ref' => $generatedRef])
                ->orderBy('reference DESC')
                ->limit(1)
                ->one();
            
            if ($last) {
                $lastReference = $last->reference;
                $lastSerial = (int) substr($lastReference, $x, 2);
                $nextSerial = str_pad($lastSerial + 1, 2, '0', STR_PAD_LEFT);
            } else {
                $nextSerial = '01';
            }
            
            $generatedRef = str_replace('%', $nextSerial, $generatedRef);
        }
        
        return $generatedRef;
    }
    
    /**
     * Retrieve parent section of the requirement
     * 
     * @return Section Parent section
     */
    public function getSection()
    {
        return $this->parents(1)->one();
    }
    
    /**
     * Retrieve requirement comments
     * 
     * @return type
     */
    public function searchForComments()
    {
        $comment = new RequirementCommentSearch();
        return $comment->search(['RequirementCommentSearch' => ['requirementId' => $this->id]]);
    }
    
    /**
     * Archive a requirement
     * 
     * @throws Exception
     */
    public function archive()
    {
        $this->archive = true;
        if (! $this->save()) {
            throw new Exception("Requirement can't be archive.");
        }
    }
}
