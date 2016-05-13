<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RequirementForm extends Model
{
    public $category;
    public $section_id;
    public $reference;
    public $title;
    public $wording;
    public $justification;
    public $status;
    public $priority = 1;
    public $isNewRecord;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['category', 'section_id', 'reference', 'wording', 'priority'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'category' => Yii::t('app', 'Category'),
            'section_id' => Yii::t('app', 'Section'),
            'reference' => Yii::t('app', 'Reference'),
            'title' => Yii::t('app', 'Title'),
            'version' => Yii::t('app', 'Version'),
            'wording' => Yii::t('app', 'Wording'),
            'justification' => Yii::t('app', 'Justification'),
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
