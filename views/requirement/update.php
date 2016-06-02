<?php

use yii\helpers\Html;
use app\models\ItemQuery;

/* @var $this yii\web\View */
/* @var $model app\models\forms\RequirementForm */
/* @var $id int */
/* @var $priorityItems array */
/* @var $categoryItems array */
/* @var $releaseItems array */
/* @var $query ItemQuery */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app', 'Requirement'),
]) . $model->reference;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requirements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="requirement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'query' => $query,
        'priorityItems' => $priorityItems,
        'categoryItems' => $categoryItems,
        'releaseItems' => $releaseItems,
    ]) ?>

</div>
