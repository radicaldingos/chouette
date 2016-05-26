<?php

namespace app\controllers;

use Yii;
use app\models\Section;
use app\models\SectionSearch;
use app\models\forms\SectionForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Exception;
use yii\db\IntegrityException;
use app\models\Item;

/**
 * SectionController implements the CRUD actions for Section model.
 */
class SectionController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Section models.
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Section model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Section model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SectionForm();

        if ($model->load(Yii::$app->request->post())) {
            // POST
            try {
                $section = new Section;
                $section->reference = $model->reference;
                $section->name = $model->name;
                $section->project_id = Yii::$app->session->get('user.current_project')->id;
                $section->created = time();
                $section->icon = 'folder-open';
                
                if ($model->section_id) {
                    $parentSection = Section::findOne($model->section_id);
                    $section->appendTo($parentSection);
                } else {
                    $section->makeRoot();
                }

                Yii::$app->getSession()->setFlash('success', Yii::t('app/success', 'Section <b>{name}</b> has been created.', ['name' => $section->name]));
                return $this->redirect(['/requirement', 'id' => $section->id]);
            } catch (IntegrityException $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', 'Reference must be unique for a given project.'));
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', $e->getMessage()));
            }
        }
        
        // Get items for treeview
        $project = Yii::$app->session->get('user.current_project');
        $query = Item::find()
            ->where("project_id = {$project->id}")
            ->andWhere("type = 'Section'")
            ->addOrderBy('tree, lft');
        
        return $this->render('create', [
            'model' => $model,
            'query' => $query,
        ]);
    }

    /**
     * Updates an existing Section model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new SectionForm();
        $section = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // POST
            try {
                $section->reference = $model->reference;
                $section->name = $model->name;
                $section->project_id = Yii::$app->session->get('user.current_project')->id;
                $section->created = time();
                $section->icon = 'folder-open';
                
                if ($model->section_id) {
                    $parentSection = Section::findOne($model->section_id);
                    $section->appendTo($parentSection);
                } else {
                    $section->makeRoot();
                }

                Yii::$app->getSession()->setFlash('success', Yii::t('app/success', 'Section <b>{name}</b> has been updated.', ['name' => $section->name]));
                return $this->redirect(['/requirement', 'id' => $section->id]);
            } catch (IntegrityException $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', 'Reference must be unique for a given project.'));
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', $e->getMessage()));
            }
        } else {
            $model->reference = $section->reference;
            $model->name = $section->name;
            if ($parentSection = $section->getParentSection()) {
                $model->section_id = $parentSection->id;
            }
        }
        
        // Get items for treeview
        $project = Yii::$app->session->get('user.current_project');
        $query = Item::find()
            ->where("project_id = {$project->id}")
            ->andWhere("type = 'Section'")
            ->addOrderBy('tree, lft');
        
        return $this->render('update', [
            'id' => $id,
            'model' => $model,
            'query' => $query,
        ]);
    }

    /**
     * Deletes an existing Section model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Section the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Section::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
