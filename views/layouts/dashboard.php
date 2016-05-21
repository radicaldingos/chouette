<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Project;
use yii\helpers\ArrayHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Chouette',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
        'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);
    $currentProject = Yii::$app->session->get('user.current_project') ? Yii::$app->session->get('user.current_project') : null;
    $projectsData = ArrayHelper::map(Project::find()->all(), 'id', 'name');
            
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

    <div class="container-fluid">
        <div>
            <?php if (! Yii::$app->user->isGuest) : ?>
            <div style="float: right;">
                <?=  Html::beginForm(['/site/select-project'], 'post', ['class' => 'breadcrumbs-form'])
                    . Html::dropDownList('project_id', $currentProject, $projectsData, ['class' => 'form-control', 'onchange' => 'form.submit();', 'prompt' => Yii::t('app', 'Select a project...')])
                    . Html::endForm()
                ?>
            </div>
            <?php endif; ?>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </div>
        
        <?php echo $this->render('_flash'); ?>
        
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">v0.1 &copy; RadicalDingos <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
