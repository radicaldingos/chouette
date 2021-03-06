<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

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
                'label' => Yii::t('app', 'Category'),
                'value' => function ($model) {
                    return Yii::t('app', $model->category->name);
                },
            ],
            'reference',
            'lastVersion.title',
            [
                'attribute' => 'lastVersion.wording',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->getEllipsedWording();
                },
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'contentOptions' => function ($model) {
                    return ['style' => "background-color:{$model->getStatusColor()};"];
                },
                'value' => function ($model) {
                    return Yii::t('app', $model->lastVersion->status->name);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/requirement', 'id' => $model->id]));
                    },
                ],

            ],
        ],
    ]); ?>
</div>
