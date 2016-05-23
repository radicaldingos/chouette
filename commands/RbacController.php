<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Profile;

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
        
        $displayRequirement = $auth->createPermission('displayRequirement');
        $displayRequirement->description = 'Display a requirement';
        $auth->add($displayRequirement);
        
        $createRequirement = $auth->createPermission('createRequirement');
        $createRequirement->description = 'Create a requirement';
        $auth->add($createRequirement);
        
        $updateRequirement = $auth->createPermission('updateRequirement');
        $updateRequirement->description = 'Update a requirement';
        $auth->add($updateRequirement);
        
        $deleteRequirement = $auth->createPermission('deleteRequirement');
        $deleteRequirement->description = 'Delete a requirement';
        $auth->add($deleteRequirement);
        
        $archiveRequirement = $auth->createPermission('archiveRequirement');
        $archiveRequirement->description = 'Archive a requirement';
        $auth->add($archiveRequirement);
        
        $manageProjects = $auth->createPermission('manageProjects');
        $manageProjects->description = 'Manage projects';
        $auth->add($manageProjects);
        
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Manage users';
        $auth->add($manageUsers);
        
        // Assign permissions to roles
        $auth->addChild($user, $manageRequirements);
        $auth->addChild($manageRequirements, $displayRequirement);
        $auth->addChild($manageRequirements, $createRequirement);
        $auth->addChild($manageRequirements, $updateRequirement);
        $auth->addChild($manageRequirements, $deleteRequirement);
        $auth->addChild($manageRequirements, $archiveRequirement);
        
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $manageProjects);

        // Assign roles to profiles
        $auth->assign($admin, Profile::ADMIN);
        $auth->assign($user, Profile::LEAD_DEVELOPER);
        $auth->assign($user, Profile::DEVELOPER);
        $auth->assign($user, Profile::CUSTOMER);
    }
}
