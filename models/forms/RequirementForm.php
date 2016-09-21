<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\RequirementAttachment;

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
    public $target_release_id;
    public $integrated_release_id;
    public $isNewRecord;

    /**
     * @var \yii\web\UploadedFile
     */
    public $attachment;
    
    /**
     * @var string
     */
    public $attachmentPath;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['category_id', 'section_id', 'reference', 'wording', 'priority_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['category_id', 'section_id', 'reference', 'wording', 'priority_id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['category_id', 'section_id', 'priority_id', 'target_release_id', 'integrated_release_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['wording', 'justification'], 'string'],
            [['attachment'], 'file', 'skipOnEmpty' => false],
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
            'target_release_id' => Yii::t('app', 'Target release'),
            'integrated_release_id' => Yii::t('app', 'Implemented in release'),
            'attachment' =>Yii::t('app', 'Attachment'),
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => [
                'category_id',
                'section_id',
                'reference',
                'wording',
                'priority_id',
                'title',
                'justification',
                'target_release_id',
                'integrated_release_id',
            ],
            self::SCENARIO_UPDATE => [
                'category_id',
                'section_id',
                'reference',
                'wording',
                'priority_id',
                'title',
                'justification',
                'target_release_id',
                'integrated_release_id',
            ],
        ];
    }
    
    public function getCompleteName()
    {
        return "{$this->reference} - {$this->title}";
    }
    
    /**
     * Upload of an attachment file
     * 
     * @return boolean
     */
    public function upload()
    {
        if ($this->validate()) {
            $this->attachmentPath = uniqid();
            $uploadDir = Yii::$app->params['attachmentsUploadDir']
                ? Yii::$app->params['attachmentsUploadDir']
                : RequirementAttachment::DEFAULT_UPLOAD_DIR;
            $this->attachment->saveAs(Yii::getAlias('@app') . $uploadDir . $this->attachmentPath);
            return true;
        } else {
            return false;
        }
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
