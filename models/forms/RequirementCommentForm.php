<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class RequirementCommentForm extends Model
{
    public $comment;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['comment'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'comment' => Yii::t('app', 'Comment'),
        ];
    }
}
