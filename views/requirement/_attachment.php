<?php

/* @var $model app\models\RequirementAttachment */
/* @var $key */
/* @var $index  */
/* @var $item  */

use yii\helpers\Url;

?>
<li>
    <a href="<?= Url::to(['/attachment/download', 'id' => $model->id], true) ?>" title=""><?= $model->name ?></a>
</li>
