<?php

use yii\widgets\ListView;

/** @var app\models\RequirementSearch $node */
?>

<div class="detailBox">
    <div class="actionBox">
        <?= ListView::widget([
            'dataProvider' => $node->searchForOldVersions(),
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_version',
            'layout' => '{items}'
        ]) ?>
    </div>
</div>
