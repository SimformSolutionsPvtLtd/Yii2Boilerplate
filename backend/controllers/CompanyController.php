<?php

namespace backend\controllers;

use backend\models\Company;
use backend\models\CompanyImages;
use backend\models\CompanySearch;
use common\services\ExportService;
use Exception;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Company models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $mailer = Yii::$app->mailer;
                $message = $mailer->compose()
                    ->setTo('kerul@simformsolutions.com')
                    ->setFrom('kerul@simformsolutions.com')
                    ->setSubject('Company Added : ' . $model->company_name)
                    ->setTextBody('You company is added successfully.')
                    ->setHtmlBody('<b>Company name</b> : ' .  $model->company_name)
                    ->send();

                if ($message) {
                    Yii::$app->session->setFlash('success', 'Mail sent successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'Something went wrong in sending mail.');
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $mailer = Yii::$app->mailer;
            $message = $mailer->compose()
                ->setTo('kerul@simformsolutions.com')
                ->setFrom('kerul@simformsolutions.com')
                ->setSubject('Company updated : ' . $model->company_name)
                ->setTextBody('You company is updated successfully.')
                ->setHtmlBody('<b>Company name</b> : ' .  $model->company_name)
                ->send();

            if ($message) {
                Yii::$app->session->setFlash('success', 'Mail sent successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Something went wrong in sending mail.');
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Uploads company images model.
     * If upload is successful, the browser will be redirected to the 'Company view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUploadImages($id)
    {
        $model = new CompanyImages();
        $model->company_id = $id;

        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'image_name');
            if ($model->upload()) {
                // Images upload and save success
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('company-images', ['model' => $model]);
    }

    /**
     * Deletes an existing Company image.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteImage($id)
    {
        $companyImages = CompanyImages::findOne(['id' => $id]);
        $companyId = $companyImages->company_id;

        if ($companyImages->delete()) {
            Yii::$app->session->setFlash('success', 'Image deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Image is not deleted.');
        }

        return $this->redirect(['view', 'id' => $companyId]);
    }

    /**
     * Exports Company data.
     * @return \yii\web\Response
     * @throws Exception if anything went wrong
     */
    public function actionExport()
    {
        try {
            $companies = new CompanySearch();
            $exportData = $companies->search($this->request->queryParams)->getModels();

            // Convert the array of models into an array
            $exportData = array_map(function($model) {
                return $model->attributes;
            }, $exportData);

            // $exportData = $companies->getAll();
            $exportColumns = !empty($exportData) ? array_keys($exportData[0]) : [];

            $labels = $companies->attributeLabels();
            
            $titles = [];
            foreach ($exportColumns as $attribute) {
                $titles[] = array_key_exists($attribute, $labels) ? $labels[$attribute] : $attribute;
            }

            ExportService::exportExcel("Company", $titles, $exportData);
        } catch (Exception $e) {
            // Handle the exception
            echo $e->getMessage();
            echo $e->getTraceAsString();
            return;
        }

        // return $this->redirect('index');
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
