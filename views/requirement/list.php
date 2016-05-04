<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\RequirementCategory;
use app\models\RequirementStatus;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequirementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Requirements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requirement-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Requirement'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'Section',
                //'value' => function($data){ return "{$data->section->document->name} » {$data->section->name}"; },
            ],
            [
                'attribute' => 'type',
                //'value' => function($data){ return RequirementCategory::getValue($data->type); },
            ],
            'lastVersion.statement',
            [
                'attribute' => 'status',
                'value' => function($data){ return RequirementStatus::getValue($data->status); },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
