<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class SectionForm extends Model
{
    public $reference;
    public $name;
    public $section_id;
    public $isNewRecord;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['reference', 'name', 'section_id'], 'required'],
            [['section_id'], 'integer'],
            [['reference'], 'string', 'max' => 40],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'reference' => Yii::t('app', 'Reference'),
            'name' => Yii::t('app', 'Name'),
            'section_id' => Yii::t('app', 'Section'),
            
        ];
    }
}
