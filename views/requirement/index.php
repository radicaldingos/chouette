<?php

use yii\helpers\Html;
use kartik\tree\TreeView;
use app\models\Item;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Document'), ['document/create'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Create Section'), ['section/create'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Create Requirement'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= TreeView::widget([
        'query' => Item::find()->addOrderBy('tree, lft'), 
        'headingOptions' => ['label' => Yii::t('app', 'Requirements')],
        'fontAwesome' => false,
        'isAdmin' => false,
        'displayValue' => 1,
        'softDelete' => true,
        'cacheSettings' => [        
            'enableCache' => true,
        ],
        'showIDAttribute' => false,
        'nodeView' => '@app/views/requirement/_view',
        'rootOptions' => [
            'label' => 'BNE',
        ],
        'mainTemplate' => '
<div class="row">
    <div class="col-sm-6">
        {wrapper}
    </div>
    <div class="col-sm-6">
        {detail}
    </div>
</div>',
        
    ]); ?>
</div>
