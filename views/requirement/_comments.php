<?php

use app\models\forms\RequirementCommentForm;
use kartik\form\ActiveForm;
use yii\widgets\ListView;

/** @var app\models\RequirementSearch $node */
?>

<div class="col-sm-6">
    <div class="detailBox">
        <div class="titleBox">
            <?= Yii::t('app', 'Comments') ?>
        </div>
        <div class="actionBox">
            <ul class="commentList">
                <?= ListView::widget([
                    'dataProvider' => $node->searchForComments(),
                    'itemOptions' => ['class' => 'item'],
                    'itemView' => '_comment',
                    'layout' => '{items}'
                ]) ?>
            </ul>
            <?php
            $form = ActiveForm::begin(['class' => 'form-inline', 'action' => '/requirement/post?id=' . $node->id]);
            $commentFormModel = new RequirementCommentForm();
            ?>
                <div class="form-group">
                    <?= $form->field($commentFormModel, 'comment')->input('text', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Your comments')])->label(false) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-default"><?= Yii::t('app', 'Add') ?></button>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
