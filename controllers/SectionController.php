<?php

namespace app\controllers;

use Yii;
use app\models\Section;
use app\models\SectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Exception;
use yii\db\IntegrityException;

/**
 * SectionController implements the CRUD actions for Section model.
 */
class SectionController extends Controller
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
        $model = new Section();

        if ($model->load(Yii::$app->request->post())) {
            // POST
            try {
                $parentSection = Section::findOne($model->parentSectionId);
                $model->project_id = Yii::$app->session->get('user.current_project')->id;
                $model->created = time();
                $model->icon = 'folder-open';
                $model->appendTo($parentSection);

                Yii::$app->getSession()->setFlash('success', Yii::t('app/success', 'Section has been created.'));
                return $this->redirect(['/requirement', 'id' => $model->id]);
            } catch (IntegrityException $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', 'Reference must be unique for a given project.'));
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', $e->getMessage()));
            }
        }
        
        $sectionItems = Section::getSectionsWithFullPath(Yii::$app->session->get('user.current_project')->id);
        
        return $this->render('create', [
            'model' => $model,
            'sectionItems' => $sectionItems,
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // POST
            try {
                $parentSection = Section::findOne($model->parentSectionId);
                $model->project_id = Yii::$app->session->get('user.current_project')->id;
                $model->created = time();
                $model->icon = 'folder-open';
                $model->appendTo($parentSection);

                Yii::$app->getSession()->setFlash('success', Yii::t('app/success', 'Section has been updated.'));
                return $this->redirect(['/requirement', 'id' => $model->id]);
            } catch (IntegrityException $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', 'Reference must be unique for a given project.'));
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/error', $e->getMessage()));
            }
        }
        
        $sectionItems = Section::getSectionsWithFullPath(Yii::$app->session->get('user.current_project')->id);
        
        return $this->render('update', [
            'model' => $model,
            'sectionItems' => $sectionItems,
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
