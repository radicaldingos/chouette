<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RequirementForm */
/* @var $sectionItems array */
/* @var $priorityItems array */

$this->title = Yii::t('app', 'Create Requirement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requirements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requirement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sectionItems' => $sectionItems,
        'priorityItems' => $priorityItems,
    ]) ?>

</div>
