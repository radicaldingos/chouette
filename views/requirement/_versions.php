<?php

use yii\widgets\ListView;

/** @var app\models\RequirementSearch $node */
?>

<div class="detailBox">
    <div class="actionBox">
        <ul class="versionList">
            <?= ListView::widget([
                'dataProvider' => $node->searchForVersions(),
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_version',
                'layout' => '{items}'
            ]) ?>
        </ul>
    </div>
</div>
