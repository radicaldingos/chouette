<?php

use yii\helpers\Html;
use kartik\tree\TreeView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $id int */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Requirements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Requirement'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Create Section'), ['section/create'], ['class' => 'btn btn-info']) ?>
    </p>
    
    <?= TreeView::widget([
        'query' => $query,
        'displayValue' => $id,
        'emptyNodeMsg' => 'Node not found',
        'headingOptions' => ['label' => Yii::t('app', 'Requirements')],
        'fontAwesome' => false,
        'isAdmin' => false,
        'softDelete' => true,
        'cacheSettings' => [        
            'enableCache' => true,
        ],
        'showIDAttribute' => false,
        'showFormButtons' => true,
        'nodeView' => '@app/views/requirement/_view',
        'childNodeIconOptions' => ['class' => 'node-icon'],
        'parentNodeIconOptions' => ['class' => 'node-icon'],
        'rootOptions' => [
            'label' => $project->name,
        ],
        'wrapperTemplate' => '{header}{tree}',
        'mainTemplate' => '
<div class="row">
    <div class="col-sm-4">
        {wrapper}
    </div>
    <div class="col-sm-8">
        {detail}
    </div>
</div>',
        
    ]); ?>
</div>
