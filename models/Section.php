<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section".
 *
 * @property Requirement[] $requirements
 */
class Section extends Item
{
    const TYPE = 'Section';
    
    public $parentSectionId;

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
            [['reference', 'name', 'project_id'], 'required'],
            [['project_id', 'parentSectionId'], 'integer'],
            [['reference'], 'string', 'max' => 10],
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
            'reference' => Yii::t('app', 'Reference'),
            'name' => Yii::t('app', 'Name'),
            'created' => Yii::t('app', 'Created'),
            'parentSectionId' => Yii::t('app', 'Parent Section'),
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
    public function getRequirements()
    {
        //return $this->hasMany(Requirement::className(), ['section_id' => 'id']);
    }
    
    /**
     * Retrieve parent section of the section
     * 
     * @return Section Parent section
     */
    public function getParentSection()
    {
        return $this->parents(1)->one();
    }
    
    public function getDetailAttributes()
    {
        return [
            'name',
            'reference',
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d/m/Y'],
            ],
        ];
    }
    
    /**
     * Get the section full name with sections name and project name
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
