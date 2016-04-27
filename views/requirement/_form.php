<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\RequirementType;
use app\models\RequirementStatus;

/* @var $this yii\web\View */
/* @var $model app\models\RequirementForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $sectionItems array */
?>

<div class="requirement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(RequirementType::getValues()) ?>

    <?= $form->field($model, 'section_id')->dropDownList($sectionItems) ?>
    
    <?php if (! $model->isNewRecord): ?>
        <?= $form->field($model, 'status')->dropDownList(RequirementStatus::getValues()) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'code')->input('text') ?>
    
    <?= $form->field($model, 'statement')->textarea(['style' => 'resize: vertical']) ?>
    
    <?= $form->field($model, 'priority')->input('text') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'sub', 'value' => 'update']) ?>
        <?php if (! $model->isNewRecord): ?>
            <?= Html::submitButton(Yii::t('app', 'New Revision'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'revision']) ?>
            <?= Html::submitButton(Yii::t('app', 'New Version'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'version']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
