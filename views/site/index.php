<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Chouette';
?>
<div class="site-index">

    <div class="jumbotron">
        <div style="float: left;"><?= Html::img('/img/chouette_400.png') ?></div>
        
        <h1><?= Yii::t('app', 'Welcome!') ?></h1>

        <p class="lead"><?= Yii::t('app', 'Chouette is a simple but powerful web requirements manager.') ?></p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com"><?= Yii::t('app', 'Get started with Chouette') ?></a></p>
    </div>

    <div class="body-content" style="clear: both;">

        <div class="row">
            <div class="col-lg-4">
                <h2><?= Yii::t('app', 'First step') ?></h2>

                <p><?= Yii::t('app', 'You need to create a new project.') ?></p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/"><?= Yii::t('app', 'Create a new project') ?> &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2><?= Yii::t('app', 'Second step') ?></h2>

                <p><?= Yii::t('app', 'Then you can now create documents to gather requirements.') ?></p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/"><?= Yii::t('app', 'Create a new document') ?> &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2><?= Yii::t('app', "That's all good!") ?></h2>

                <p><?= Yii::t('app', 'You can manage all your requirements!') ?></p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/"><?= Yii::t('app', 'Add some requirements') ?> &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
