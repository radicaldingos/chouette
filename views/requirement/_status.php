<?php

use yii\bootstrap\ActiveForm;
use app\models\forms\RequirementStatusForm;
use yii\helpers\Html;

$form = ActiveForm::begin(['layout' => 'inline',
    'action' => '/requirement/update-status?id=' . $node->id,
    'fieldConfig' => [
        'labelOptions' => ['class' => ''],
        'enableError' => true,
    ]
]);
$model = new RequirementStatusForm();
?>
    <?= $form->field($model, 'status_id')->dropDownList($statusItems) ?>
    
    <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success', 'name' => 'sub', 'value' => 'revision']) ?>

<?php ActiveForm::end(); ?>