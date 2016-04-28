<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "section".
 *
 * @property integer $id
 * @property string $name
 * @property integer $document_id
 * @property integer $position
 *
 * @property Requirement[] $requirements
 * @property Document $document
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'document_id'], 'required'],
            [['document_id', 'position'], 'integer'],
            [['name'], 'string', 'max' => 40],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'document_id' => Yii::t('app', 'Document ID'),
            'position' => Yii::t('app', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirements()
    {
        return $this->hasMany(Requirement::className(), ['section_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }
    
    /**
     * Get the section full name with document name and project name
     * 
     * @return array
     */
    public static function getSectionsWithFullPath()
    {
        $sections = Section::find()->with('document', 'document.project')->all();
        $tab = array();
        foreach ($sections as $section) {
            $tab[$section->id] = "{$section->document->project->name} » {$section->document->name} » {$section->name}";
        }
        return $tab;
    }
}
