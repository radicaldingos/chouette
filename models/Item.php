<?php

namespace app\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property string $reference
 * @property integer $category_id
 * @property string $name
 * @property integer $author
 * @property integer $created
 * @property integer $status
 * @property integer $criticality
 * @property integer $priority_id
 * @property integer $project_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $type
 *
 * @property Item $parent
 * @property Item[] $items
 * @property Project $project
 * @property RequirementAttachment[] $attachments
 * @property RequirementComment[] $comments
 * @property RequirementLog[] $events
 * @property RequirementVersion[] $versions
 */
class Item extends \kartik\tree\models\Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference', 'name', 'created', 'project_id', 'lft', 'rgt', 'depth', 'type'], 'required'],
            [['created', 'status', 'priority_id', 'project_id', 'lft', 'rgt', 'depth'], 'integer'],
            [['reference'], 'string', 'max' => 10],
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
            'created' => Yii::t('app', 'Created'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'status' => Yii::t('app', 'Status'),
            'priority_id' => Yii::t('app', 'Priority'),
            'project_id' => Yii::t('app', 'Project ID'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
            'type' => Yii::t('app', 'Type'),
        ];
    }
    
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                    'treeAttribute' => 'tree',
                    'leftAttribute' => 'lft',
                    'rightAttribute' => 'rgt',
                    'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    
    public static function instantiate($row)
    {
        switch ($row['type']) {
            case Requirement::TYPE:
                return new Requirement();
            case Section::TYPE:
                return new Section();
            default:
               return new self;
        }
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
    public function getRequirementAttachments()
    {
        return $this->hasMany(RequirementAttachment::className(), ['requirement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirementComments()
    {
        return $this->hasMany(RequirementComment::className(), ['requirement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirementLogs()
    {
        return $this->hasMany(RequirementLog::className(), ['requirement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirementVersions()
    {
        return $this->hasMany(RequirementVersion::className(), ['requirement_id' => 'id']);
    }
    
    public function getDetailAttributes()
    {
        return [
            'reference',
            'name',
            'status',
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d/m/Y'],
            ],
            /*[
                'attribute' => 'lastVersion.updated',
                'format' => ['date', 'php:d/m/Y'],
            ],*/
            'priority_id',
        ];
    }

    /**
     * @inheritdoc
     * @return ItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemQuery(get_called_class());
    }
}
