<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\tree\TreeViewInput;

/* @var $this yii\web\View */
/* @var $model app\models\forms\RequirementForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $priorityItems array */
/* @var $categoryItems array */
/* @var $statusItems array */

//$this->registerJs("$('#requirementform-section_id').on('change', function(){alert('ok');})", View::POS_END, 'my-options');
?>

<div class="requirement-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <div class="col-md-6">
        <?= $form->field($model, 'reference')->input('text') ?>
        
        <?= $form->field($model, 'title')->input('text') ?>
    
        <?= $form->field($model, 'wording')->textarea(['style' => 'resize: vertical']) ?>

        <?= $form->field($model, 'justification')->textarea(['style' => 'resize: vertical']) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'category_id')->dropDownList($categoryItems) ?>
    
        <div class="form-group field-requirementform-category_id required">
        <?php 
            echo Html::label(Yii::t('app', 'Section'), 'requirementform-section_id', ['class' => 'control-label']);    
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
        
        <?= $form->field($model, 'priority_id')->dropDownList($priorityItems) ?>

        <?= $form->field($model, 'target_release_id')->dropDownList($releaseItems, ['prompt' => Yii::t('app', 'No targeted release')]) ?>

        <?= $form->field($model, 'integrated_release_id')->dropDownList($releaseItems, ['prompt' => Yii::t('app', 'Not implemented yet')]) ?>
        
        <?= $form->field($model, 'attachment')->fileInput() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'sub', 'value' => 'update']) ?>
        <?php if (! $model->isNewRecord): ?>
            <?= Html::submitButton(Yii::t('app', 'New Revision'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'revision']) ?>
            <?= Html::submitButton(Yii::t('app', 'New Version'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'version']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
