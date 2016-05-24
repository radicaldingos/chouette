<?php

use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => 'Chouette',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-static-side',
    ],
    'innerContainerOptions' => ['class' => 'container-fluid'],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
        ['label' => Yii::t('app', 'Requirements'), 'url' => ['/requirement'], 'visible' => Yii::$app->user->can('manageRequirements')],
        ['label' => Yii::t('app', 'Projects'), 'url' => ['/project'], 'visible' => Yii::$app->user->can('manageProjects')],
        ['label' => Yii::t('app', 'Users'), 'url' => ['/user'], 'visible' => Yii::$app->user->can('manageUsers')],
        Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
            . Html::submitButton(
                Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>'
        )
    ],
]);

NavBar::end();
?>