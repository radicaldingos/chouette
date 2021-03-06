<?php

namespace app\models;
use app\components\ArrayTranslatorHelper;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property string $name
 *
 * @property UserProfile[] $userProfiles
 * @property User[] $users
 * @property UserProject[] $userProjects
 */
class Profile extends \yii\db\ActiveRecord
{
    const ADMIN = 1;
    const LEAD_DEVELOPER = 2;
    const DEVELOPER = 3;
    const CUSTOMER = 4;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 40],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_profile', ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProjects()
    {
        return $this->hasMany(UserProject::className(), ['profile_id' => 'id']);
    }
    
    public static function getOrderedMappedList()
    {
        return ArrayTranslatorHelper::map(static::find()
            ->orderBy('name')
            ->all(), 'id', 'name');
    }
}
