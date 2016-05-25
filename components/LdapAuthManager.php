<?php
 
namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\ErrorException;
 
/**
 * LdapAuthManager class
 *
 * Manage connection and authentication with a LDAP server.
 */
class LdapAuthManager extends Component
{
    /**
     * @var resource Link identifier
     */
    public $connection = null;
    
    /**
     * @var array LDAP options
     */
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
            throw new ErrorException(Yii::t('app/error', "Couldn't connect to LDAP server."));
        }
        
        return $this;
    }
    
    /**
     * Bind to LDAP connection
     * 
     * @return \app\components\LdapAuthManager
     * 
     * @throws ErrorException
     */
    public function bind()
    {
        $bind = @ldap_bind($this->connection);
       
        if (! $bind) {
            throw new ErrorException(Yii::t('app/error', "LDAP server couldn't be bound."));
        }
       
        return $this;
    }
   
    /**
     * Get the user DN in LDAP
     * 
     * @param type $username
     * 
     * @return type
     * 
     * @throws ErrorException
     */
    public function getUserDn($username)
    {
        // Search for LDAP user
        $userEntry = $this->getUserEntry($username);
       
        // Get user DN
        $userDn = ldap_get_dn($this->connection, $userEntry);
       
        if (! $userDn) {
            throw new ErrorException(Yii::t('app/error', "User DN couldn't be fetched."));
        }
       
        return $userDn;
    }
   
    /**
     * Search for LDAP user
     * 
     * @param type $username
     * 
     * @return type
     * 
     * @throws ErrorException
     */
    public function getUserEntry($username)
    {
        $userEntries = $this->getUserEntries($username);
       
        $count = ldap_count_entries($this->connection, $userEntries);
       
        if ($count != 1) {
            throw new ErrorException(Yii::t('app/error', "Username has been found more than once."));
        }
       
        $entry = ldap_first_entry($this->connection, $userEntries);
       
        if (! $entry) {
            throw new ErrorException(Yii::t('app/error', "User couldn't be found."));
        }
       
        return $entry;
    }
   
    /**
     * Search for username entries
     * 
     * @param type $username
     * 
     * @return type
     * 
     * @throws ErrorException
     */
    public function getUserEntries($username)
    {
        $dc_string = 'dc=' . implode(',dc=', $this->options['dc']);
       
        $entries = ldap_search($this->connection, "ou={$this->options['ou']},{$dc_string}", "uid={$username}");
       
        if (! $entries) {
            throw new ErrorException(Yii::t('app/error', "Username couldn't be found."));
        }
       
        return $entries;
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
    public function bindUser($userDn, $password)
    {
        // Authentication
        $bind = @ldap_bind($this->connection, $userDn, $password);
        
        if (! $bind) {
            throw new ErrorException(Yii::t('app/error', "Invalid credentials."));
        }
       
        return $this;
    }
    
    /**
     * Close LDAP connection
     */
    public function closeConnection()
    {
        ldap_close($this->connection);
    }
   
    /**
     * Try to connect & authenticate user on LDAP server
     *
     * @param type $username Username (uid)
     * @param type $password Password
     *
     * @return boolean
     */
    public function authenticate($username, $password)
    {
        try {
            $userDn = $this->connect()
                ->bind()
                ->getUserDn($username);
 
            // Try to authenticate with credentials
            $this->bindUser($userDn, $password);
            
            // Close LDAP connection
            $this->closeConnection();
        } catch (ErrorException $e) {
            return false;
        }
       
        return true;
    }
}