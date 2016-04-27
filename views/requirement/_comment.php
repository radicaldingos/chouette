<?php

/* @var $model app\models\RequirementComment */
/* @var $key */
/* @var $index  */
/* @var $item  */

use yii\helpers\Html;
?>
<li>
    <div class="avatar">
        <div class="no-avatar"><?= strtoupper(substr(Yii::$app->user->identity->username, 0, 1)) ?></div>
        <!-- <img src="" /> -->
    </div>
    <div class="commentText">
        <p class=""><?= Html::encode($model->comment) ?></p> <span class="date sub-text"><?= Yii::t('app', 'on') . ' ' . date('d/m/Y ' . Yii::t('app', 'at') . ' H:i', $model->date_creation) ?></span>
    </div>
</li>
