<?php

namespace app\controllers;

use Yii;
use app\models\Profile;
use app\models\Project;
use app\models\User;
use app\models\UserProject;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Exception;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $model->hashPassword();
            $model->generateAuthKey();
            $model->access_token = $model->getAuthKey();
            if (! $model->save()) {
                die(print_r($model->getErrors()));
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userProjectModel = new UserProject;
        
        $projectDataProvider = new ActiveDataProvider([
            'query' => UserProject::find()->with('project', 'profile'),
        ]);
        
        $projectItems = Project::getOrderedMappedList();
        $profileItems = Profile::getOrderedMappedList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        if ($userProjectModel->load(Yii::$app->request->post())) {
            $userProjectModel->user_id = Yii::$app->user->id;
            if (! $userProjectModel->save()) {
                throw new Exception("Couldn't save project.");
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }
        
        return $this->render('update', [
            'model' => $model,
            'userProjectModel' => $userProjectModel,
            'projectDataProvider' => $projectDataProvider,
            'projectItems' => $projectItems,
            'profileItems' => $profileItems,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteRole($user_id, $project_id, $profile_id)
    {
        UserProject::find()
            ->where("user_id = {$user_id} AND project_id = {$project_id} AND profile_id = {$profile_id}")
            ->one()
            ->delete();

        return $this->redirect(['update', 'id' => $user_id]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
