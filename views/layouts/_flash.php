<div class="col-lg-12">
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('warning')): ?>
        <div class="alert alert-warning" role="alert">
            <?= Yii::$app->session->getFlash('warning') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('info')): ?>
        <div class="alert alert-info" role="alert">
            <?= Yii::$app->session->getFlash('info') ?>
        </div>
    <?php endif; ?>
</div>