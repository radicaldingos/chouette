<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\RequirementType;

/* @var $this yii\web\View */
/* @var $model app\models\RequirementForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $sectionItems array */
?>

<div class="requirement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(RequirementType::getValues()) ?>

    <?= $form->field($model, 'section_id')->dropDownList($sectionItems) ?>
    
    <?= $form->field($model, 'code')->input('text') ?>
    
    <?= $form->field($model, 'title')->input('text') ?>
    
    <?= $form->field($model, 'description')->textarea() ?>
    
    <?= $form->field($model, 'priority')->input('text') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
