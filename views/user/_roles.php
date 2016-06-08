<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\UserProject */
/* @var $form yii\widgets\ActiveForm */
?>

<h3><?= Yii::t('app', 'User projects') ?></h3>

<?php
echo GridView::widget([
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

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $url = Url::to(['/user/delete-role', 'user_id' => $model->user_id, 'project_id' => $model->project_id, 'profile_id' => $model->profile_id]);
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

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'project_id')->dropDownList($projectItems) ?>
    
    <?= $form->field($model, 'profile_id')->dropDownList($profileItems) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'update']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
