<?php

namespace app\controllers;

use Yii;
use app\models\RequirementAttachment;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
/**
 * AttachmentController implements the CRUD actions for RequirementAttachment model.
 */
class AttachmentController extends Controller
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
     * Download an attachment.
     * 
     * @return mixed
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        
        $uploadDir = isset(Yii::$app->params['attachmentsUploadDir'])
                ? Yii::$app->params['attachmentsUploadDir']
                : RequirementAttachment::DEFAULT_UPLOAD_DIR;
        $fileFullPath = Yii::getAlias('@app') . $uploadDir . $model->path;
        
        if (!file_exists($fileFullPath)) {
            throw new ServerErrorHttpException(Yii::t('app/error', 'File access failed: permission denied.'));
        }

        /*if ( !is_resource($response->stream = fopen($fileFullPath, 'r')) ) {
           throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        }*/
        
        return Yii::$app->response->sendFile($fileFullPath, $model->name);
    }

    /**
     * Finds the Attachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id
     * 
     * @return Attachment the loaded model
     * 
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RequirementAttachment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
