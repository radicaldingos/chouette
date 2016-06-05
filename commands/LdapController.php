<?php
/**
 * Yii CLI command used to test LDAP connection
 */

namespace app\commands;

use yii\console\Controller;
use app\components\LdapAuthManager;

/**
 * Class LdapController
 */
class LdapController extends Controller
{
    public function actionTest()
    {
        $authManager = new LdapAuthManager;
        if ($authManager->authenticate('admin', 'admin')) {
            echo "Authentication test succeed.\n";
        } else {
            echo "Authentication test failed.\n";
        }
    }
}
