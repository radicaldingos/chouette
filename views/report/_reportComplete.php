<?php
/* @var $this yii\web\View */
?>

<?php
foreach ($requirements as $requirement) {
    echo $this->render('_requirement', ['model' => $requirement]);
}
