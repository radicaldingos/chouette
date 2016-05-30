<?php

namespace app\models;

use Yii;
use yii\base\Model;

class UserProjectForm extends Model
{
    public $project_id;
    public $profile_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['project_id', 'profile_id'], 'required'],
            [['project_id', 'profile_id'], 'integer'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'project_id' => Yii::t('app', 'Project'),
            'profile_id' => Yii::t('app', 'Profile'),
        ];
    }
}
