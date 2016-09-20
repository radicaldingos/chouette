<?php

use kartik\form\ActiveForm;
use kartik\tree\TreeView;
use kartik\tree\models\Tree;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use app\models\Requirement;
use app\models\Section;
use app\models\Status;
use yii\bootstrap\Tabs;

/**
 * @var View       $this
 * @var Tree       $node
 * @var ActiveForm $form
 * @var string     $keyAttribute
 * @var string     $nameAttribute
 * @var string     $iconAttribute
 * @var string     $iconTypeAttribute
 * @var string     $iconsList
 * @var string     $action
 * @var array      $breadcrumbs
 * @var array      $nodeAddlViews
 * @var mixed      $currUrl
 * @var bool       $showIDAttribute
 * @var bool       $showFormButtons
 */

extract($params);

$nodeIdentifier = strtolower($node->type);

$inputOpts = [];                                      // readonly/disabled input options for node
$flagOptions = ['class' => 'kv-parent-flag'];         // node options for parent/child

// parse parent key
if (empty($parentKey)) {
    $parent = $node->parents(1)->one();
    $parentKey = empty($parent) ? '' : Html::getAttributeValue($parent, $keyAttribute);
}

// get module and setup form
$module = TreeView::module(); // the treemanager module

$statusItems = Status::getOrderedMappedList();

// initialize for create or update
$depth = ArrayHelper::getValue($breadcrumbs, 'depth');
$glue = ArrayHelper::getValue($breadcrumbs, 'glue');
$activeCss = ArrayHelper::getValue($breadcrumbs, 'activeCss');
$untitled = ArrayHelper::getValue($breadcrumbs, 'untitled');
$name = $node->getBreadcrumbs($depth, $glue, $activeCss, $untitled);
if ($node->isNewRecord && !empty($parentKey) && $parentKey !== 'root') {
    /**
     * @var Tree $modelClass
     * @var Tree $parent
     */
    $depth = empty($breadcrumbsDepth) ? null : intval($breadcrumbsDepth) - 1;
    if ($depth === null || $depth > 0) {
        $parent = $modelClass::findOne($parentKey);
        $name = $parent->getBreadcrumbs($depth, $glue, null) . $glue . $name;
    }
}
if ($node->isReadonly()) {
    $inputOpts['readonly'] = true;
}
if ($node->isDisabled()) {
    $inputOpts['disabled'] = true;
}
if ($node->isLeaf()) {
    $flagOptions['disabled'] = true;
}

// show alert helper
$showAlert = function ($type, $body = '', $hide = true) {
    $class = "alert alert-{$type}";
    if ($hide) {
        $class .= ' hide';
    }
    return Html::tag('div', '<div>' . $body . '</div>', ['class' => $class]);
};

// Set selected section in order to make easy requirement creation
$session = Yii::$app->session;
if ($node instanceof Requirement) {
    $session->set('user.current_section', $node->getSection());
} elseif ($node instanceof Section) {
    $session->set('user.current_section', $node);
}
?>

<div class="kv-detail-heading">
    <div class="pull-right">
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ["/$nodeIdentifier/update", 'id' => $node->id], ['class' => 'btn', 'title' => Yii::t('app', 'Edit')]) ?>
        <?= $node instanceof Requirement
            ? Html::a('<i class="glyphicon glyphicon-inbox"></i>', ["/$nodeIdentifier/archive", 'id' => $node->id], ['class' => 'btn', 'title' => Yii::t('app', 'Archive')])
            : ''
        ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ["/$nodeIdentifier/delete", 'id' => $node->id], ['class' => 'btn', 'title' => Yii::t('app', 'Delete')]) ?>
    </div>
    <div class="kv-detail-crumbs"><?= $name ?></div>
    <div class="clearfix"></div>
</div>

<div class="col-sm-6">
    <?php
    echo DetailView::widget([
        'model' => $node,
        'attributes' => $node->getDetailAttributes(),
        /*'template' => function ($attribute, $index, $widget) {
            debug($attribute);
            if (isset($attribute['attribute']) && $attribute['attribute'] == 'created') {
                return "<tr><th>{$attribute['label']}</th><td class=\"status-new\">{$attribute['value']}</td></tr>";
            } else {
                return "<tr><th>{$attribute['label']}</th><td>{$attribute['label']}</td></tr>";
            }
        },*/
    ]);

    if ($node instanceof Requirement) {
        echo $this->render('_status', [
            'node' => $node,
            'statusItems' => $statusItems,
        ]);
    }
    ?>
</div>

<?php if ($node instanceof Requirement) : ?>
<div class="col-sm-6">
    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Comments'),
                'content' => $this->render('_comments', ['node' => $node]),
                'active' => true,
            ],
            [
                'label' => Yii::t('app', 'Older versions'),
                'content' => $this->render('_versions', ['node' => $node]),
            ],
            [
                'label' => Yii::t('app', 'Timeline'),
                'content' => $this->render('_logs', ['node' => $node]),
            ],
        ],
    ]); ?>
    
    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Attachments'),
                'content' => $this->render('_attachments', ['node' => $node]),
                'active' => true,
            ],
        ],
        'headerOptions' => [
            'style' => 'margin-top: 20px;',
        ]
    ]); ?>
</div>
<?php endif; ?>