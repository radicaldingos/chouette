<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Documents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Document'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reference',
            'name',
            'created',
            // 'status',
            // 'priority',
            // 'project_id',
            // 'tree',
            // 'lft',
            // 'rgt',
            // 'depth',
            // 'icon',
            // 'active:boolean',
            // 'selected:boolean',
            // 'disabled:boolean',
            // 'readonly:boolean',
            // 'visible:boolean',
            // 'collapsed:boolean',
            // 'movable_u:boolean',
            // 'movable_d:boolean',
            // 'movable_l:boolean',
            // 'movable_r:boolean',
            // 'removable:boolean',
            // 'removable_all:boolean',
            // 'icon_type',
            // 'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
