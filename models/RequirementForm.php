<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RequirementForm extends Model
{
    public $type;
    public $section_id;
    public $code;
    public $statement;
    public $status;
    public $priority = 1;
    public $isNewRecord;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['type', 'section_id', 'code', 'statement', 'priority'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'Type'),
            'section_id' => Yii::t('app', 'Section'),
            'code' => Yii::t('app', 'Code'),
            'version' => Yii::t('app', 'Version'),
            'statement' => Yii::t('app', 'Statement'),
            'priority' => Yii::t('app', 'Priority'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    /*public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }*/
}
