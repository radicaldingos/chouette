<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\tree\TreeViewInput;

/* @var $this yii\web\View */
/* @var $model app\models\Section */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="section-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group field-requirementform-category_id required">
    <?php 
        echo Html::label(Yii::t('app', 'Parent section'), 'requirementform-section_id', ['class' => 'control-label']);    
        echo TreeViewInput::widget([
            'model' => $model,
            'attribute' => 'section_id',
            'query' => $query,
            'headingOptions' => ['label' => Yii::t('app', 'Sections')],
            'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
            'fontAwesome' => true,
            'asDropdown' => true,
            'multiple' => false,
            'options' => ['disabled' => false],
            'dropdownConfig' => [
                'input' => [
                    'placeholder' => Yii::t('app', 'Select a section...'),
                ],
            ],
        ]); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
