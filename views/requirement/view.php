<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\RequirementType;
use app\models\RequirementStatus;

/* @var $this yii\web\View */
/* @var $model app\models\Requirement */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requirements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requirement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'type',
                'value' => RequirementType::getValue($model->type),
            ],
            [
                'label' => Yii::t('app', 'Section'),
                'value' => "{$model->section->document->project->name} » {$model->section->document->name} » {$model->section->name}",
            ],
            'code',
            [
                'label' => Yii::t('app', 'Version'),
                'value' => "{$model->lastVersion->version}.{$model->lastVersion->revision}",
            ],
            [
                'attribute' => 'lastVersion.status',
                'value' => RequirementStatus::getValue($model->status),
            ],
            'lastVersion.title',
            'lastVersion.description',
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute' => 'lastVersion.updated',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'priority',
        ],
    ]) ?>

</div>
