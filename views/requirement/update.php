<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RequirementForm */
/* @var $id int */
/* @var $sectionItems array */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Requirement',
]) . $id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requirements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="requirement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sectionItems' => $sectionItems,
    ]) ?>

</div>
