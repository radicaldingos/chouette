<?php

use yii\widgets\ActiveForm;
use app\models\forms\RequirementStatusForm;

$form = ActiveForm::begin(['class' => 'form-inline', 'action' => '/requirement/update-status?id=' . $node->id]);
$model = new RequirementStatusForm();
?>
    <div class="form-group">
        <?= $form->field($model, 'status_id')->dropDownList($statusItems, ['class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <button class="btn btn-default"><?= Yii::t('app', 'Update') ?></button>
    </div>
<?php ActiveForm::end(); ?>