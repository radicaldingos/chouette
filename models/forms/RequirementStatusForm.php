<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class RequirementStatusForm extends Model
{
    public $status_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['status_id'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'status_id' => Yii::t('app', 'Status'),
        ];
    }
}
