<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-view">

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
            'id',
            'code',
            'name',
            'created',
            'parent_id',
            'status',
            'priority',
            'project_id',
            'tree',
            'lft',
            'rgt',
            'depth',
            'icon',
            'active:boolean',
            'selected:boolean',
            'disabled:boolean',
            'readonly:boolean',
            'visible:boolean',
            'collapsed:boolean',
            'movable_u:boolean',
            'movable_d:boolean',
            'movable_l:boolean',
            'movable_r:boolean',
            'removable:boolean',
            'removable_all:boolean',
            'icon_type',
            'type',
        ],
    ]) ?>

</div>
