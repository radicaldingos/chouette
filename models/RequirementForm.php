<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RequirementForm extends Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    public $category_id;
    public $section_id;
    public $reference;
    public $title;
    public $wording;
    public $justification;
    public $priority_id = 1;
    public $isNewRecord;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['category_id', 'section_id', 'reference', 'wording', 'priority_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['category_id', 'section_id', 'reference', 'wording', 'priority_id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['category_id', 'section_id', 'priority_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['wording', 'justification'], 'string'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('app', 'Category'),
            'section_id' => Yii::t('app', 'Section'),
            'reference' => Yii::t('app', 'Reference'),
            'title' => Yii::t('app', 'Title'),
            'version' => Yii::t('app', 'Version'),
            'wording' => Yii::t('app', 'Wording'),
            'justification' => Yii::t('app', 'Justification'),
            'priority_id' => Yii::t('app', 'Priority'),
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['category_id', 'section_id', 'reference', 'wording', 'priority_id', 'title', 'justification'],
            self::SCENARIO_UPDATE => ['category_id', 'section_id', 'reference', 'wording', 'priority_id', 'title', 'justification'],
        ];
    }
    
    public function getCompleteName()
    {
        return "{$this->reference} - {$this->title}";
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
