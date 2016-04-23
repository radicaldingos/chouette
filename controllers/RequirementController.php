<?php

namespace app\controllers;

use Yii;
use app\models\Requirement;
use app\models\RequirementSearch;
use app\models\RequirementVersion;
use app\models\RequirementForm;
use app\models\RequirementStatus;
use app\models\Section;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Requirement models.
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequirementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Requirement model.
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
     * Creates a new Requirement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * A requirement is created at version 1, revision 0.
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RequirementForm;
        $model->isNewRecord = true;
        
        if ($model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
            $requirement = new Requirement;
            $requirement->section_id = $model->section_id;
            $requirement->created = time();

            if (! $requirement->save()) {
                throw new Exception('Error');
            }
            
            $version = new RequirementVersion;
            $version->requirement_id = $requirement->id;
            $version->type = $model->type;
            $version->code = $model->code;
            $version->title = $model->title;
            $version->description = $model->description;
            $version->version = 1;
            $version->revision = 0;
            $version->priority = $model->priority;
            $version->updated = time();
            $version->status = RequirementStatus::NEW_REQUIREMENT;
            
            if (! $version->save()) {
                throw new Exception('Error');
            }
            
            return $this->redirect(['view', 'id' => $requirement->id]);
        }
        
        $sectionItems = ArrayHelper::map(Section::find()->all(), 'id', 'name');

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
            $version = new RequirementVersion;
            $version->requirement_id = $id;
            $version->type = $requirementData['type'];
            $version->code = $requirementData['code'];
            $version->title = $requirementData['title'];
            $version->description = $requirementData['description'];
            $version->version = $requirement->lastVersion->version;
            $version->revision = $requirement->lastVersion->revision + 1;
            $version->priority = $requirementData['priority'];
            $version->updated = time();
            $version->status = RequirementStatus::NEW_REQUIREMENT;
            
            if (! $version->save()) {
                throw new Exception('Error');
            }
            
            return $this->redirect(['view', 'id' => $id]);
        }
        
        $model->code = $requirement->lastVersion->code;
        $model->title = $requirement->lastVersion->title;
        $model->description = $requirement->lastVersion->description;
        $model->priority = $requirement->lastVersion->priority;
        
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
        if (($model = Requirement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
