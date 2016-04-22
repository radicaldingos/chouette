<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RequirementVersion */

$this->title = 'Create Requirement Version';
$this->params['breadcrumbs'][] = ['label' => 'Requirement Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requirement-version-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
