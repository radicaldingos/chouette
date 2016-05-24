<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\base\ErrorException;

class LdapController extends Controller
{
    public $username = 'nicolas.digard';
    public $password = 'nico';
    
    public function actionTest()
    {
        $options = Yii::$app->params['ldap'];
        
        $dc_string = "dc=" . implode(",dc=", $options['dc']);
 
        $connection = ldap_connect($options['host'], $options['port']);
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
        
        if (! $connection) {
            echo ldap_error($connection);
        }
        
        $bind = null;
        try {
            $bind = ldap_bind($connection, "cn={$this->username},ou={$options['ou']},{$dc_string}", $this->password);
            //$bind = ldap_bind($connection, "uid={$this->username},ou={$options['ou']},{$dc_string}", $this->password);
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
        
        if ($bind) {
            echo "Authentifié\n";
        }
        
        ldap_close($connection);
    }
}
