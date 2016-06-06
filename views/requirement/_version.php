<?php

/* @var $model app\models\RequirementVersion */
/* @var $key */
/* @var $index  */
/* @var $item  */

use yii\helpers\Html;
?>
<div class="event-detail">
    <p class="event-title"><span class="event-version">v<?= $model->getCompleteVersion() ?></span> <?= Html::encode($model->title) ?></p>
    <label><?= Yii::t('app', 'Wording') ?></label>
    <p class="event-field"><?= Html::encode($model->wording) ?></p>
    <label><?= Yii::t('app', 'Justification') ?></label>
    <p class="event-field"><?= Html::encode($model->justification) ?></p>
</div>
