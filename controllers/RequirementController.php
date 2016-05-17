<?php

namespace app\controllers;

use Yii;
use app\models\Item;
use app\models\Requirement;
use app\models\RequirementSearch;
use app\models\RequirementVersion;
use app\models\RequirementForm;
use app\models\RequirementCommentForm;
use app\models\Section;
use app\models\Priority;
use app\models\Category;
use app\models\Status;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\models\ItemSearch;

/**
 * RequirementController implements the CRUD actions for Requirement model.
 */
class RequirementController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        //'actions' => [],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['POST'],
                    'postComment' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * Lists all items (documents, sections and requirements) in a tree view.
     * 
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $project = Yii::$app->session->get('user.last_project');
        
        $query = Item::find()->where("project_id = {$project->id}")->addOrderBy('tree, lft');

        return $this->render('index', [
            'id' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'project' => $project,
            'query' => $query,
        ]);
    }

    /**
     * Lists all Requirement models.
     * 
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new RequirementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Requirement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * A requirement is created at version 1, revision 0.
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $requirement = new Requirement;
        $model = new RequirementForm;
        $model->isNewRecord = true;
        
        if ($model->load(Yii::$app->request->post())) {
            $section = Section::findOne($model->section_id);

            $requirement->category_id = $model->category_id;
            $requirement->reference = $model->reference;
            $requirement->name = $model->reference;
            $requirement->priority_id = $model->priority_id;
            $requirement->status_id = Status::NEW_REQUIREMENT;
            $requirement->project_id = $section->project_id;
            $requirement->created = time();

            if (! $requirement->appendTo($section)) {
                die(print_r($requirement->getErrors()));
                throw new Exception('Error');
            }
            
            $version = new RequirementVersion;
            $version->requirement_id = $requirement->id;
            $version->title = $model->title;
            $version->wording = $model->wording;
            $version->justification = $model->justification;
            $version->version = 1;
            $version->revision = 0;
            $version->updated = time();
            
            if (! $version->save()) {
                throw new Exception('Error');
            }
            
            return $this->redirect(['index', 'id' => $requirement->id]);
        }
        
        if (! $model->reference) {
            $model->reference = $requirement::generateReferenceFromPattern();
        }
        $sectionItems = Section::getSectionsWithFullPath(Yii::$app->session->get('user.last_project')->id);
        $priorityItems = Priority::getOrderedMappedList();
        $categoryItems = Category::getOrderedMappedList();
        $statusItems = Status::getOrderedMappedList();

        return $this->render('create', [
            'model' => $model,
            'sectionItems' => $sectionItems,
            'priorityItems' => $priorityItems,
            'categoryItems' => $categoryItems,
            'statusItems' => $statusItems,
        ]);
    }

    /**
     * Updates an existing Requirement model.
     * 
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @param integer $id
     * 
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new RequirementForm;
        $requirement = $this->findModel($id);
        
        if ($requirementData = Yii::$app->request->post('RequirementForm')) {
            $section = Section::findOne($requirementData['section_id']);
            
            $requirement->category_id = $requirementData['category_id'];
            $requirement->reference = $requirementData['reference'];
            $requirement->name = $requirementData['reference'];
            $requirement->priority_id = $requirementData['priority_id'];
            $requirement->status_id = $requirementData['status_id'];
            
            if (! $requirement->appendTo($section)) {
                die(print_r($requirement->getErrors()));
                throw new Exception('Error');
            }
            
            $submit = Yii::$app->request->post('sub');
            if ($submit == 'version'
                || $submit == 'revision'
            ) {
                // If user decide to version or revision the change, we create a
                // new version of the requirement
                $version = new RequirementVersion;
            } else {
                // We update the current version
                $version = $requirement->lastVersion;
            }
            
            $version->requirement_id = $id;
            $version->title = $requirementData['title'];
            $version->wording = $requirementData['wording'];
            $version->justification = $requirementData['justification'];
            if ($submit == 'version') {
                $version->version = $requirement->lastVersion->version + 1;
                $version->revision = 0;
            } elseif ($submit == 'revision') {
                $version->version = $requirement->lastVersion->version;
                $version->revision = $requirement->lastVersion->revision + 1;
            }
            $version->updated = time();

            if (! $version->save()) {
                throw new Exception('Error');
            }
                
            return $this->redirect(['index']);
        }
        
        $model->reference = $requirement->reference;
        $model->title = $requirement->lastVersion->title;
        $model->wording = $requirement->lastVersion->wording;
        $model->justification = $requirement->lastVersion->justification;
        $model->priority_id = $requirement->priority_id;
        
        $sectionItems = ArrayHelper::map(Section::find()->all(), 'id', 'name');
        $priorityItems = Priority::getOrderedMappedList();
        $categoryItems = Category::getOrderedMappedList();
        $statusItems = Status::getOrderedMappedList();

        return $this->render('update', [
            'model' => $model,
            'id' => $id,
            'sectionItems' => $sectionItems,
            'priorityItems' => $priorityItems,
            'categoryItems' => $categoryItems,
            'statusItems' => $statusItems,
        ]);
    }

    /**
     * Deletes an existing Requirement model.
     * 
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @param integer $id
     * 
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Search for requirements matching with search criteria
     * 
     * @param string $q
     * 
     * @return mixed
     */
    public function actionSearch($q)
    {
        $searchModel = new RequirementSearch();
        $dataProvider = $searchModel->searchByCriteria($q);

        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionPost($id)
    {
        $model = new RequirementCommentForm();
        
        if ($model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
            $requirement = $this->findModel($id);
            $requirement->addComment($model);
        }
        
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Requirement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id
     * 
     * @return Requirement the loaded model
     * 
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
