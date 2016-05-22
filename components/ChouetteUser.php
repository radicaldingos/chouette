<?php

namespace app\components;

use yii\web\User;

class ChouetteUser extends User
{
    private $_access = [];
    
    /**
     * @inheritDoc
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if ($allowCaching && empty($params) && isset($this->_access[$permissionName])) {
            return $this->_access[$permissionName];
        }
        if (($manager = $this->getAuthManager()) === null) {
            return false;
        }
        $access = $manager->checkAccess($this->getProfileId(), $permissionName, $params);
        if ($allowCaching && empty($params)) {
            $this->_access[$permissionName] = $access;
        }

        return $access;
    }
    
    /**
     * Get user current profile id
     * 
     * @return int
     */
    private function getProfileId()
    {
        $identity = $this->getIdentity();
        
        return $identity !== null && $identity->getCurrentProfile() ? $identity->getCurrentProfile()->profile_id : null;
    }
}