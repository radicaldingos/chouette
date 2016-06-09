<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\components\LdapAuthManager;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $lastname
 * @property string $firstname
 * @property string $phone
 * @property string $email
 * @property string $avatar
 * @property string $auth_key
 * @property string $access_token
 * @property int $project_id
 *
 * @property RequirementComment[] $requirementComments
 * @property RequirementLog[] $RequirementLogs
 * @property UserProfile[] $userProfiles
 * @property Profile[] $profiles
 * @property UserProject[] $userProjects
 * @property Project $lastProject
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'auth_key', 'access_token'], 'required'],
            [['project_id'], 'integer'],
            [['username', 'auth_key', 'access_token'], 'string', 'max' => 40],
            [['password', 'lastname', 'firstname'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 128],
            [['avatar'], 'string', 'max' => 1024],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'lastname' => Yii::t('app', 'Last name'),
            'firstname' => Yii::t('app', 'First name'),
            'phone' => Yii::t('app', 'Phone number'),
            'email' => Yii::t('app', 'E-mail'),
            'avatar' => Yii::t('app', 'E-mail'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirementComments()
    {
        return $this->hasMany(RequirementComment::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequirementLogs()
    {
        return $this->hasMany(RequirementLog::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['user_id' => 'id']);
    }
    
    /**
     * 
     * @return Profile
     */
    public function getGlobalProfile()
    {
        $globalProfile = Yii::$app->session->get('user.global_profile');
        return $globalProfile ? $globalProfile : null;
    }
    
    /**
     * 
     * @return Profile
     */
    public function getCurrentProfile()
    {
        $currentProfile = Yii::$app->session->get('user.current_profile');
        return $currentProfile ? $currentProfile : null;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['id' => 'profile_id'])->viaTable('user_profile', ['user_id' => 'id']);
    }
    
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id' => 'project_id'])
            ->viaTable(UserProject::tableName(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProjects()
    {
        return $this->hasMany(UserProject::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    public function hashPassword()
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->getPassword());
    }
    
    /**
     * Generate auth key for cookie authentication
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * 
     * @param type $password
     * 
     * @return type
     */
    public function validatePassword($password)
    {
        if (isset(Yii::$app->params['ldap']['enabled'])
            && Yii::$app->params['ldap']['enabled']
        ) {
            // LDAP authentication
            $ldap = new LdapAuthManager();
            return $ldap->authenticate($this->username, $password);
        } else {
            // Server authentication
            return Yii::$app->security->validatePassword($password, $this->getPassword());
        }
    }
    
    
    public function getLastProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
    
    /**
     * Loading global profile for all projects
     */
    public function loadGlobalProfile()
    {
        $globalProfile = UserProfile::find()
            ->where('user_id = ' . Yii::$app->user->id)
            ->one();
        Yii::$app->session->set('user.global_profile', $globalProfile);
    }
    
    /**
     * Loading last project to user session
     */
    public function loadLastProject()
    {
        $currentProject = $this->lastProject;
            
        if ($currentProject) {
            // If user has a "last project", we load it as the current project
            $currentProfile = UserProject::find()
                ->where('user_id = ' . Yii::$app->user->id)
                ->andWhere('project_id = ' . $currentProject->id)
                ->one();

            Yii::$app->session->set('user.current_project', $currentProject);
            Yii::$app->session->set('user.current_profile', $currentProfile);
        }
    }
}
