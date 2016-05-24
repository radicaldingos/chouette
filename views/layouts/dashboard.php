<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Project;

$currentProject = Yii::$app->session->get('user.current_project') ? Yii::$app->session->get('user.current_project') : null;
$projectsData = ArrayHelper::map(Project::find()->all(), 'id', 'name');

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
    <?= $this->render('dashboard/_menu', [
        'currentProject' => $currentProject,
    ]) ?>

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
        
        <?= $this->render('dashboard/_flash') ?>
        
        <?= $content ?>
    </div>
</div>

<?= $this->render('dashboard/_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
