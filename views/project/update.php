<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Project',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="project-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-sm-4">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
    
    <div class="col-sm-4">
        <?= $this->render('_releasesForm', [
            'releaseDataProvider' => $releaseDataProvider,
            'releaseModel' => $releaseModel,
        ]) ?>
    </div>

</div>
