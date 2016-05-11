<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Requirement[] $requirements
 * @property Document $document
 */
class Section extends Item
{
    const TYPE = 'Section';

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
            [['code', 'name', 'project_id'], 'required'],
            [['project_id'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 255],
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
            'created' => Yii::t('app', 'Created'),
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
    public function getDocument()
    {
        //return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirements()
    {
        //return $this->hasMany(Requirement::className(), ['section_id' => 'id']);
    }
    
    public function getDetailAttributes()
    {
        return [
            'name',
            'code',
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d/m/Y'],
            ],
        ];
    }
    
    /**
     * Get the section full name with document name and project name
     * 
     * @return array
     */
    public static function getSectionsWithFullPath($projectId)
    {
        $sections = Section::find()
            ->where(['project_id' => $projectId])
            ->with('project')
            ->all();
        $tab = array();
        foreach ($sections as $section) {
            $tab[$section->id] = "{$section->project->name} » {$section->name}";
        }
        return $tab;
    }
}
