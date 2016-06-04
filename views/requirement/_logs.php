<?php

use yii\widgets\ListView;

/** @var app\models\RequirementSearch $node */
?>

<div class="detailBox">
    <div class="actionBox">
        <ul class="commentList">
            <?= ListView::widget([
                'dataProvider' => $node->searchForLogs(),
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_log',
                'layout' => '{items}'
            ]) ?>
        </ul>
    </div>
</div>
