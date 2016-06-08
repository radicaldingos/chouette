<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<h3><?= Yii::t('app', 'User projects') ?></h3>

<?= GridView::widget([
    'dataProvider' => $projectDataProvider,
    'columns' => [
        [
            'label' => Yii::t('app', 'Project'),
            'value' => function ($model) {
                return $model->project->name;
            }
        ],
        [
            'label' => Yii::t('app', 'Profile'),
            'value' => function ($model) {
                return Yii::t('app', $model->profile->name);
            }
        ],
    ],
]);
?>
