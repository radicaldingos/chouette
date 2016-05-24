<?php

/* @var $model app\models\RequirementComment */
/* @var $key */
/* @var $index  */
/* @var $item  */

use yii\helpers\Html;

// Create user avatar
$userAvatar = strtoupper(substr($model->user->username, 0, 1));
?>
<li>
    <span class="comment-version">v<?= $model->requirementVersion->getCompleteVersion() ?></span>
    <div class="avatar">
        <div class="no-avatar" title="<?= $model->user->username ?>"><?= $userAvatar ?></div>
        <!-- <img src="" /> -->
    </div>
    <div class="commentText">
        <p class=""><?= Html::encode($model->comment) ?></p> <span class="date sub-text"><?= Yii::t('app', 'on') . ' ' . date('d/m/Y ' . Yii::t('app', 'at') . ' H:i', $model->date_creation) ?></span>
    </div>
</li>
