<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class RequirementStatusForm extends Model
{
    public $status_id;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['status_id'], 'required'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'status_id' => Yii::t('app', 'Status'),
        ];
    }
}
