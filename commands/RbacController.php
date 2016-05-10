<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        
        // Add roles
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $user = $auth->createRole('user');
        $auth->add($user);

        // Add permissions
        $manageRequirements = $auth->createPermission('manageRequirements');
        $manageRequirements->description = 'Manage requirements';
        $auth->add($manageRequirements);
        
        $manageProjects = $auth->createPermission('manageProjects');
        $manageProjects->description = 'Manage projects';
        $auth->add($manageProjects);
        
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Manage users';
        $auth->add($manageUsers);
        
        // Assign permissions to roles
        $auth->addChild($user, $manageRequirements);        
        
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $manageProjects);

        // Assign roles to users
        $auth->assign($admin, 1);
        $auth->assign($user, 2);
    }
}
