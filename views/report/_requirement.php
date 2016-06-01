<?php
/* @var $model \app\models\Requirement */
?>

<div style="border: 2px solid black; width: 100%; margin-bottom: 20px;">
    <div style="background: #f0f0f0;">
        <div style="float: right;">v<?= $model->lastVersion->getCompleteVersion() ?></div>
        <?= $model->name ?>
    </div>
    <div>
        <span style="width: 300px;"><?= Yii::t('app', 'Category') ?></span>
        <span><?= Yii::t('app', $model->category->name) ?></span>
        <span style="width: 300px;"><?= Yii::t('app', 'Status') ?></span>
        <span><?= Yii::t('app', $model->lastVersion->status->name) ?></span>
    </div>
    <div>
        <span style="width: 300px;"><?= Yii::t('app', 'Wording') ?></span>
        <span><?= $model->lastVersion->wording ?></span>
    </div>
    <div>
        <span style="width: 300px;"><?= Yii::t('app', 'Justification') ?></span>
        <span><?= $model->lastVersion->justification ?></span>
    </div>
</div>