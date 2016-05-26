<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Section */

$this->title = Yii::t('app', 'Create Section');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'query' => $query,
    ]) ?>

</div>
