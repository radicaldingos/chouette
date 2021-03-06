<?php

/* @var $this \yii\web\View */
/* @var $currentProject Project */
/* @var $projectsData array */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => '',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
    'innerContainerOptions' => ['class' => 'container-fluid'],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
        ['label' => Yii::t('app', 'Requirements'), 'url' => ['/requirement'], 'visible' => Yii::$app->user->can('displayRequirement')],
        ['label' => Yii::t('app', 'Projects'), 'url' => ['/project'], 'visible' => Yii::$app->user->can('manageProjects')],
        ['label' => Yii::t('app', 'Users'), 'url' => ['/user'], 'visible' => Yii::$app->user->can('manageUsers')],
        ['label' => Yii::t('app', 'Reports'), 'url' => ['/report']],
        Yii::$app->user->isGuest ? (
            ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
            . Html::submitButton(
                Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link no-shadow']
            )
            . Html::endForm()
            . '</li>'
        )
    ],
]);
?>
<div class="col-sm-3 col-md-3 pull-right">
    <form class="navbar-form" role="search" action="/requirement/search">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="<?= Yii::t('app', 'Search') ?>" name="q">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
    </form>
</div>
<?php
NavBar::end();
?>