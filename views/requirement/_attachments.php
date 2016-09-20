<?php

use yii\widgets\ListView;

/** @var app\models\Requirement $node */
?>

<div class="detailBox">
    <div class="actionBox">
        <ul class="attachmentList">
            <?= ListView::widget([
                'dataProvider' => $node->searchForAttachments(),
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_attachment',
                'layout' => '{items}'
            ]) ?>
        </ul>
    </div>
</div>
