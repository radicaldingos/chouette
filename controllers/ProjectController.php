<?php

namespace app\controllers;

use Yii;
use app\models\Section;
use app\models\Project;
use app\models\ProjectSearch;
use app\models\Release;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * 
     * @param integer $id
     * 
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post())
            && $model->save()
        ) {
            // Create a default section for requirements
            $section = new Section();
            $section->reference = Yii::t('app', 'RQ');
            $section->name = Yii::t('app', 'General Requirements');
            $section->project_id = $model->id;
            $section->created = time();
            if (! $section->makeRoot()) {
                throw new ErrorException('Error');
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // GET, default values
            $model->requirement_pattern = '{project.name}_{section.reference}_{serial}';
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @param integer $id
     * 
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $releaseModel = new Release();
        
        $releaseDataProvider = new ActiveDataProvider([
            'query' => Release::find()
                ->where("project_id = $id"),
        ]);
        
        if ($model->load(Yii::$app->request->post())
            && $model->save()
        ) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        if ($releaseModel->load(Yii::$app->request->post())) {
            $releaseModel->project_id = $id;
            $releaseModel->date_creation = time();
            if (! $releaseModel->save()) {
                throw new Exception("Couldn't save release.");
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }
        
        return $this->render('update', [
            'model' => $model,
            'releaseDataProvider' => $releaseDataProvider,
            'releaseModel' => $releaseModel,
        ]);
    }

    /**
     * Deletes an existing Project model.
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
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @param integer $id
     * 
     * @return mixed
     */
    public function actionDeleteRelease($id, $project)
    {
        Release::findOne($id)->delete();

        return $this->redirect(['update', 'id' => $project]);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id
     * 
     * @return Project the loaded model
     * 
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
