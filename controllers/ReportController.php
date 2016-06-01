<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Requirement;
use kartik\mpdf\Pdf;

class ReportController extends Controller
{
    //public $layout = 'dashboard';
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionComplete($type = 'pdf')
    {
        $requirements = Requirement::find()
            ->where("type = 'Requirement'")
            ->orderBy('name ASC')
            ->all();
        
        $content = $this->renderPartial('_reportComplete', ['requirements' => $requirements]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, 
            'format' => Pdf::FORMAT_A4, 
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'filename' => 'report.pdf',
            'content' => $content,  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}', 
            'options' => ['title' => Yii::t('app/report', 'Chouette Report')],
            'methods' => [ 
                'SetHeader' => [Yii::t('app/report', 'Chouette Report')], 
                'SetFooter' => ['{PAGENO}'],
            ],
        ]);

        return $pdf->render(); 
    }
}
