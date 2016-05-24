<?php

namespace app\components;

use Yii;
use \yii\base\Component;
use yii\base\ErrorException;

class LdapAuthManager extends Component
{
    public $connection = null; 
    
    public $options = [];
    
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->options = Yii::$app->params['ldap'];
    }
    
    /**
     * Connect LDAP server
     * 
     * @return \app\components\LdapAuthManager
     * 
     * @throws ErrorException
     */
    public function connect()
    {
        $this->connection = ldap_connect($this->options['host'], $this->options['port']);
        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);
        
        if (! $this->connection) {
            throw new \yii\base\ErrorException();
        }
        
        return $this;
    }
    
    /**
     * Authenticate user on LDAP server
     * 
     * @param type $username Username
     * @param type $password Password
     * 
     * @return \app\components\LdapAuthManager
     * 
     * @throws ErrorException
     */
    public function bind($username, $password)
    {
        $dc_string = 'dc=' . implode(',dc=', $this->options['dc']);
        
        $bind = @ldap_bind($this->connection, "cn={$username},ou={$this->options['ou']},{$dc_string}", $password);
        //$bind = ldap_bind($connection, "uid={$this->username},ou={$options['ou']},{$dc_string}", $this->password);
        
        if (! $bind) {
            throw new ErrorException('Error');
        }
        
        return $this;
    }
    
    /**
     * Try to connect & authenticate user on LDAP server
     * 
     * @param type $username Username
     * @param type $password Password
     * 
     * @return boolean
     */
    public function authenticate($username, $password)
    {
        try {
            $this->connect()->bind($username, $password);
            
            return true;
        } catch (ErrorException $e) {
            echo ldap_error($this->connection);
        }
        
        return false;
    }
}
