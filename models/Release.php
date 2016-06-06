<?php

namespace app\models;

use Yii;
use app\models\Project;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "release".
 *
 * @property integer $id
 * @property string $version
 * @property integer $project_id
 * @property integer $date_creation
 *
 * @property RequirementVersion[] $requirementVersions
 */
class Release extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'release';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version', 'project_id', 'date_creation'], 'required'],
            [['project_id', 'date_creation'], 'integer'],
            [['version'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'version' => Yii::t('app', 'Version'),
            'project_id' => Yii::t('app', 'Project'),
            'date_creation' => Yii::t('app', 'Creation date'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
    
    public static function getOrderedMappedList()
    {
        return ArrayHelper::map(static::find()
            ->where('project_id = ' . Yii::$app->session->get('user.current_project')->id)
            ->orderBy('date_creation')
            ->all(), 'id', 'version');
    }
}
