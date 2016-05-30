<?php

namespace app\models;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $name
 * @property string $long_name
 * @property string $requirement_pattern
 *
 * @property Section[] $sections
 * @property UserProject[] $userProjects
 * @property User[] $users
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'requirement_pattern'], 'required'],
            [['name'], 'string', 'max' => 30],
            [['long_name'], 'string', 'max' => 200],
            [['requirement_pattern'], 'string', 'max' => 100],
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
            'long_name' => Yii::t('app', 'Complete Name'),
            'requirement_pattern' => Yii::t('app', 'Requirement Pattern'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProjects()
    {
        return $this->hasMany(UserProject::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_project', ['project_id' => 'id']);
    }
    
    public static function getOrderedMappedList()
    {
        return ArrayHelper::map(static::find()
            ->orderBy('name')
            ->all(), 'id', 'name');
    }
}
