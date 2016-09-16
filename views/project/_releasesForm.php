<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Release */
/* @var $form yii\widgets\ActiveForm */
?>

<h3><?= Yii::t('app', 'Project releases') ?></h3>

<?php
echo GridView::widget([
    'dataProvider' => $releaseDataProvider,
    'columns' => [
        'version',
        [
            'attribute' => 'date_creation',
            'format' => ['date', 'php:d/m/Y H:i:s'],
        ],
        
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $url = Url::to(['/user/delete-release', 'release_id' => $model->id, 'project_id' => $model->project->id]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => \Yii::t('yii', 'Delete'),
                        'data-pjax' => '0',
                    ]);
                },
            ],
        ],
    ],
]);
?>

<div class="requirement-form">

    <?php $form = ActiveForm::begin(['layout' => 'inline']); ?>

    <?= $form->field($model, 'version')->input('field') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'update']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
