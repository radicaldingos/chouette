<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Item;
use app\models\ItemSearch;
use app\models\Requirement;
use app\models\RequirementAttachment;
use app\models\forms\RequirementCommentForm;
use app\models\forms\RequirementStatusForm;
use app\models\forms\RequirementForm;
use app\models\RequirementSearch;
use app\models\RequirementVersion;
use app\models\Category;
use app\models\Priority;
use app\models\Release;
use app\models\Section;
use app\models\Status;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * RequirementController implements the CRUD actions for Requirement model.
 */
class RequirementController extends Controller
{
    public $layout = 'dashboard';
    
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
        $project = Yii::$app->session->get('user.current_project');
        
        if (! $project) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'No selected project.'));
            return $this->redirect('/site');
        }
        
        $query = Item::find()
            ->where("project_id = {$project->id}")
            ->andWhere("archive = FALSE")
            ->orderBy('tree, lft');
            
        $count = Item::find()
            ->where("project_id = {$project->id}")
            ->andWhere("archive = FALSE")
            ->andWhere("type = 'Requirement'")
            ->count();

        return $this->render('index', [
            'id' => $id,
            'count' => $count,
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
     * A requirement is created at version 1, revision 0, with status "New".
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RequirementForm([
            'scenario' => RequirementForm::SCENARIO_CREATE,
        ]);
        $model->isNewRecord = true;
        
        if ($model->load(Yii::$app->request->post())) {
            // POST
            try {
                if (! $model->validate()) {
                    throw new Exception('Invalid form');
                }

                $section = Section::findOne($model->section_id);
                if (! $section) {
                    throw new Exception('Invalid section.');
                }
                
                $model->attachment = UploadedFile::getInstance($model, 'attachment');
                
                if ($model->attachment
                    && !$model->upload()
                ) {
                    throw new Exception("Attachment file couldn't be uploaded.");
                }

                $requirement = new Requirement;
                $requirement->category_id = $model->category_id;
                $requirement->reference = $model->reference;
                $requirement->name = $model->getCompleteName();
                $requirement->priority_id = $model->priority_id;
                $requirement->project_id = $section->project_id;
                $requirement->created = time();

                if (! $requirement->appendTo($section)) {
                    throw new Exception('Error while saving requirement.');
                }

                $version = new RequirementVersion;
                $version->requirement_id = $requirement->id;
                $version->title = $model->title;
                $version->wording = $model->wording;
                $version->justification = $model->justification;
                $version->target_release_id = $model->target_release_id;
                $version->integrated_release_id = $model->integrated_release_id;
                $version->version = 1;
                $version->revision = 0;
                $version->status_id = Status::NEW_REQUIREMENT;
                $version->updated = time();

                if (! $version->save()) {
                    throw new Exception('Error while saving requirement version.');
                }
                
                if ($model->attachment) {
                    $attachment = new RequirementAttachment();
                    $attachment->name = $model->attachment->name;
                    $attachment->path = $model->attachmentPath;
                    $attachment->requirement_id = $requirement->id;

                    if (! $attachment->save()) {
                        throw new Exception('Error while saving requirement attachment.');
                    }
                }
                
                $requirement->trigger(Requirement::EVENT_CREATE);
                
                Yii::$app->getSession()
                    ->setFlash('success', Yii::t('app/success', 'Requirement <b>{name}</b> has been created.', ['name' => $requirement->name]));
                return $this->redirect(['index', 'id' => $requirement->id]);
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', $e->getMessage()));
            }
        } else {
            // GET, setting default values
            $currentSection = Yii::$app->session->get('user.current_section');
            $model->section_id = $currentSection ? $currentSection->id : null;
            $model->priority_id = Priority::NORMAL;
            if (! $model->reference) {
                $model->reference = Requirement::generateReferenceFromPattern();
            }
        }
        
        // Get items for HTML selects
        $priorityItems = Priority::getOrderedMappedList();
        $categoryItems = Category::getOrderedMappedList();
        $releaseItems = Release::getOrderedMappedList();
        
        // Get items for treeview
        $project = Yii::$app->session->get('user.current_project');
        $query = Item::find()
            ->where("project_id = {$project->id}")
            ->andWhere("type = 'Section'")
            ->addOrderBy('tree, lft');

        return $this->render('create', [
            'model' => $model,
            'query' => $query,
            'priorityItems' => $priorityItems,
            'categoryItems' => $categoryItems,
            'releaseItems' => $releaseItems,
        ]);
    }

    /**
     * Updates an existing Requirement model.
     * 
     * @param integer $id
     * 
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new RequirementForm([
            'scenario' => RequirementForm::SCENARIO_UPDATE,
        ]);
        $requirement = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            // POST
            try {
                if (! $model->validate()) {
                    throw new Exception('Invalid form');
                }
                
                $section = Section::findOne($model->section_id);
                if (! $section) {
                    throw new Exception('Invalid section.');
                }
                
                $model->attachment = UploadedFile::getInstance($model, 'attachment');
                
                if ($model->attachment
                    && !$model->upload()
                ) {
                    throw new Exception("Attachment file couldn't be uploaded.");
                }

                $requirement->category_id = $model->category_id;
                $requirement->reference = $model->reference;
                $requirement->name = $model->getCompleteName();
                $requirement->priority_id = $model->priority_id;

                if (! $requirement->appendTo($section)) {
                    throw new Exception('Error while saving requirement.');
                }

                $submit = Yii::$app->request->post('sub');
                if ($submit == 'version'
                    || $submit == 'revision'
                ) {
                    // If user decide to version or revision the change, we create a
                    // new version of the requirement...
                    $version = new RequirementVersion;
                    
                    // ... and we change the status to "New"
                    $version->status_id = Status::NEW_REQUIREMENT;
                } else {
                    // We update the current version
                    $version = $requirement->lastVersion;
                }

                $version->requirement_id = $id;
                $version->title = $model->title;
                $version->wording = $model->wording;
                $version->justification = $model->justification;
                $version->target_release_id = $model->target_release_id;
                $version->integrated_release_id = $model->integrated_release_id;
                if ($submit == 'version') {
                    $version->version = $requirement->lastVersion->version + 1;
                    $version->revision = 0;
                } elseif ($submit == 'revision') {
                    $version->version = $requirement->lastVersion->version;
                    $version->revision = $requirement->lastVersion->revision + 1;
                }
                $version->updated = time();

                if (! $version->save()) {
                    throw new Exception('Error while saving requirement version.');
                }
                
                if ($model->attachment) {
                    $attachment = new RequirementAttachment();
                    $attachment->name = $model->attachment->name;
                    $attachment->path = $model->attachmentPath;
                    $attachment->requirement_id = $requirement->id;

                    if (! $attachment->save()) {
                        throw new Exception('Error while saving requirement attachment.');
                    }
                }
                
                if ($submit == 'version') {
                    $requirement->trigger(Requirement::EVENT_VERSION);
                } elseif($submit == 'revision') {
                    $requirement->trigger(Requirement::EVENT_REVISION);
                } else {
                    $requirement->trigger(Requirement::EVENT_UPDATE);
                }
                
                Yii::$app->getSession()->setFlash('success', Yii::t('app/success', 'Requirement <b>{name}</b> has been updated.', ['name' => $requirement->name]));
                return $this->redirect(['index', 'id' => $requirement->id]);
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', $e->getMessage()));
            }
        } else {
            // GET, setting default values
            $model->category_id = $requirement->category_id;
            $model->reference = $requirement->reference;
            $model->title = $requirement->lastVersion->title;
            $model->wording = $requirement->lastVersion->wording;
            $model->justification = $requirement->lastVersion->justification;
            $model->target_release_id = $requirement->lastVersion->target_release_id;
            $model->integrated_release_id = $requirement->lastVersion->integrated_release_id;
            $model->priority_id = $requirement->priority_id;
            if ($section = $requirement->getSection()) {
                $model->section_id = $section->id;
            }
        }
        
        // Get items for HTML selects
        $priorityItems = Priority::getOrderedMappedList();
        $categoryItems = Category::getOrderedMappedList();
        $releaseItems = Release::getOrderedMappedList();
        
        // Get items for treeview
        $project = Yii::$app->session->get('user.current_project');
        $query = Item::find()
            ->where("project_id = {$project->id}")
            ->andWhere("type = 'Section'")
            ->addOrderBy('tree, lft');
        
        return $this->render('update', [
            'model' => $model,
            'id' => $id,
            'query' => $query,
            'priorityItems' => $priorityItems,
            'categoryItems' => $categoryItems,
            'releaseItems' => $releaseItems,
        ]);
    }
    
    public function actionArchive($id)
    {
        $requirement = $this->findModel($id);
        $requirement->archive();

        $requirement->trigger(Requirement::EVENT_ARCHIVE);
        
        return $this->redirect(['index']);
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
     * Search for requirements matching with search criteria.
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
    
    /**
     * Post a new comment on selected requirement.
     * 
     * @param int $id Requirement id
     * 
     * @return mixed
     */
    public function actionPost($id)
    {
        $model = new RequirementCommentForm();
        
        if ($model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
            $requirement = $this->findModel($id);
            $requirement->addComment($model);
            
            $requirement->trigger(Requirement::EVENT_POST);
        }
        
        return $this->redirect(['index', 'id' => $id]);
    }
    
    /**
     * Update requirement status.
     * 
     * @param int $id Requirement id
     * 
     * @return mixed
     */
    public function actionUpdateStatus($id)
    {
        $model = new RequirementStatusForm();
        
        if ($model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
            $requirement = $this->findModel($id);
            $requirement->updateStatus($model->status_id);
            
            $requirement->trigger(Requirement::EVENT_UPDATE_STATUS);
            
            Yii::$app->getSession()
                    ->setFlash('success', Yii::t('app/success', 'Requirement status <b>{name}</b> has been updated.', ['name' => $requirement->name]));
        }
        
        return $this->redirect(['index', 'id' => $id]);
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
