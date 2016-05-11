<?php

namespace app\controllers;

use Yii;
use app\models\Item;
use app\models\Requirement;
use app\models\RequirementSearch;
use app\models\RequirementVersion;
use app\models\RequirementForm;
use app\models\RequirementStatus;
use app\models\RequirementCommentSearch;
use app\models\RequirementCommentForm;
use app\models\Section;
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

        return $this->render('index', [
            'id' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'project' => $project,
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
        
        if ($model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
            $section = Section::findOne($model->section_id);

            $requirement->category = $model->category;
            $requirement->code = $model->code;
            $requirement->name = $model->code;
            $requirement->priority = $model->priority;
            $requirement->status = RequirementStatus::NEW_REQUIREMENT;
            $requirement->project_id = $section->project_id;
            $requirement->created = time();

            if (! $requirement->appendTo($section)) {
                die(print_r($requirement->getErrors()));
                throw new Exception('Error');
            }
            
            $version = new RequirementVersion;
            $version->requirement_id = $requirement->id;
            $version->statement = $model->statement;
            $version->version = 1;
            $version->revision = 0;
            $version->updated = time();
            
            if (! $version->save()) {
                throw new Exception('Error');
            }
            
            return $this->redirect(['index', 'id' => $requirement->id]);
        } else {
            $model->code = $requirement::generateCodeFromPattern();
        }
        
        $sectionItems = Section::getSectionsWithFullPath(Yii::$app->session->get('user.last_project')->id);

        return $this->render('create', [
            'model' => $model,
            'sectionItems' => $sectionItems,
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
            
            $requirement->category = $requirementData['category'];
            $requirement->code = $requirementData['code'];
            $requirement->name = $requirementData['code'];
            $requirement->priority = $requirementData['priority'];
            $requirement->status = $requirementData['status'];
            
            
            if (! $requirement->save()) {
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
            $version->statement = $requirementData['statement'];
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
        
        $model->code = $requirement->code;
        $model->statement = $requirement->lastVersion->statement;
        $model->priority = $requirement->priority;
        
        $sectionItems = ArrayHelper::map(Section::find()->all(), 'id', 'name');

        return $this->render('update', [
            'model' => $model,
            'id' => $id,
            'sectionItems' => $sectionItems,
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
