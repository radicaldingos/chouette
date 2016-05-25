<?php

namespace app\commands;

use yii\console\Controller;
use app\components\LdapAuthManager;

class LdapController extends Controller
{
    public function actionTest()
    {
        $authManager = new LdapAuthManager;
        if ($authManager->authenticate('admin', 'admins')) {
            echo "Authentication test succeed.\n";
        } else {
            echo "Authentication test failed.\n";
        }
    }
}
