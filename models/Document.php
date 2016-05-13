<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property Project $project
 * @property Section[] $sections
 */
class Document extends Item
{
    const TYPE = 'Document';

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
            [['project_id'], 'integer'],
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
    public function getSections()
    {
        //return $this->hasMany(Section::className(), ['document_id' => 'id']);
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
     * Get the documents full names with project name
     * 
     * @return array
     */
    public static function getDocumentsWithFullPath($projectId)
    {
        $documents = Document::find()
            ->where(['project_id' => $projectId])
            ->with('project')
            ->all();
        $tab = array();
        foreach ($documents as $document) {
            $tab[$document->id] = "{$document->project->name} » {$document->name}";
        }
        return $tab;
    }
}
