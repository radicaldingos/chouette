<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<h3><?= Yii::t('app', 'Project releases') ?></h3>

<?php
echo GridView::widget([
    'dataProvider' => $releaseDataProvider,
    'columns' => [
        'version',
        'date_creation',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
        ],
    ],
]);
?>

<div class="requirement-form">

    <?php $form = ActiveForm::begin(['layout' => 'inline']); ?>

    <?= $form->field($releaseModel, 'version')->input('field') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'update']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
