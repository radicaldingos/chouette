<?php
/* @var $model \app\models\Requirement */
?>

<div style="border: 2px solid black; width: 100%; margin-bottom: 20px;">
    <div style="background: #f0f0f0;">
        <table width="100%">
            <tr>
                <td width="90%"><?= $model->name ?></td>
                <td width="10%" style="text-align: right;">v<?= $model->lastVersion->getCompleteVersion() ?></td>
            </tr>
        </table>
    </div>
    <div>
        <table width="100%" style="font-size: 12px;">
            <tr>
                <th width="15%" style="text-align: right;"><?= Yii::t('app', 'Category') ?></th>
                <td width="35%"><?= Yii::t('app', $model->category->name) ?></td>
                <th width="15%" style="text-align: right;"><?= Yii::t('app', 'Status') ?></th>
                <td width="35%"><?= Yii::t('app', $model->lastVersion->status->name) ?></td>
            </tr>
            <tr>
                <th style="text-align: right;"><?= Yii::t('app', 'Priority') ?></th>
                <td><?= Yii::t('app', $model->priority->name) ?></td>
                <th style="text-align: right;"><?= Yii::t('app', 'Release') ?></th>
                <td><?= isset($model->lastVersion->release) ? $model->lastVersion->release->version : '' ?></td>
            </tr>
            <tr>
                <th style="text-align: right;"><?= Yii::t('app', 'Wording') ?></th>
                <td colspan="3"><?= $model->lastVersion->wording ?></td>
            </tr>
            <tr>
                <th style="text-align: right;"><?= Yii::t('app', 'Justification') ?></th>
                <td colspan="3"><?= $model->lastVersion->justification ?></td>
            </tr>
        </table>
    </div>
    <div>
        <h4><?= Yii::t('app', 'Comments') ?></h4>
        <table width="100%" style="font-size: 12px;">
            <?php foreach ($model->searchForComments()->getModels() as $comment) : ?>
            <tr>
                <td><?= date('d/m/Y', $comment->date_creation) ?></td>
                <td><?= $comment->user->username ?></td>
                <td><?= $comment->comment ?></td>
                <td><?= $comment->requirementVersion->getCompleteVersion() ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <h4><?= Yii::t('app', 'Events') ?></h4>
        <table width="100%" style="font-size: 12px;">
            <?php foreach ($model->logs as $log) : ?>
            <tr>
                <td><?= date('d/m/Y', $log->date) ?></td>
                <td><?= $log->getEventLitteral() ?></td>
                <td><?= $log->user->username ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>