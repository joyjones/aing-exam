<?php

namespace backend\controllers;

use common\models\Evaluation;
use common\models\Question;
use common\models\Project;
use common\models\Banner;
use common\models\Resource;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Project::find(),
            'sort' => ['defaultOrder' => ['utime' => SORT_ASC]]
        ]);

        $dataBannerProvider = new ActiveDataProvider([
            'query' => Banner::find(),
            'sort' => ['defaultOrder' => ['index' => SORT_ASC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'bannerData' => $dataBannerProvider
        ]);
    }

    /**
     * Displays a single Project model.
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
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        $model->utime = time();
        $model->status = Project::STATUS_TESTING;

        if ($model->load(Yii::$app->request->post()) && self::submitData($model, null, ['logo','headimg_q','headimg_a', 'qrcode_img'])) {
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->utime = time();

        if ($model->load(Yii::$app->request->post())) {
            $model_src = $this->findModel($id);
            if (ProjectController::submitData($model, $model_src, ['logo','headimg_q','headimg_a', 'qrcode_img']))
                return $this->redirect(['/project/index']);
        }
        $questData = new ActiveDataProvider([
            'query' => Question::find()->where(['project_id' => $id]),
            'sort' => ['defaultOrder' => ['index' => SORT_ASC]]
        ]);
        $evalData = new ActiveDataProvider([
            'query' => Evaluation::find()->where(['project_id' => $id]),
            'sort' => ['defaultOrder' => ['score' => SORT_ASC]]
        ]);
        return $this->render('update', [
            'model' => $model,
            'questData' => $questData,
            'evalData' => $evalData
        ]);
    }

    public function actionUpdateFields($id)
    {
        $model = $this->findModel($id);
        $model->utime = time();

        $model_src = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && self::submitData($model, $model_src, ['logo','headimg_q','headimg_a', 'qrcode_img'])) {
            return $this->redirect(['index']);
        }

        return $this->render('update-fields', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Project model.
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
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
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

    public static function submitData($model, $model_src, $fields)
    {
        foreach ($fields as $field){
            // get the uploaded file instance. for multiple file uploads the following data will return an array
            $image = UploadedFile::getInstance($model, $field);
            $modified = $image != null;
            if (!$modified) {
                if (!$model_src || !strlen($model_src[$field]))
                    return false;
                $model[$field] = $model_src[$field];
            } else {
                // store the file name
                $es = explode(".", $image->name);
                $ext = end($es);
                // generate a unique file name
                $randName = Yii::$app->security->generateRandomString().".{$ext}";

                $ps = Yii::$app->params;
                $file = $ps['uploadPath'] . $randName;
                if (!$image->saveAs($file))
                    return false;
                $md5 = md5_file($file);
                $res = Resource::find()->where(['md5' => $md5])->one();
                if ($res != null){
                    $model[$field] = $res->filename;
                }else{
                    $model[$field] = $randName;
                    $qiniu = new Qiniu($ps['qiniu']['key'], $ps['qiniu']['secret'], $ps['qiniu']['domain'], $ps['qiniu']['bucket']);
                    $qiniu->uploadFile($file, $model[$field]);
                    //$url = $qiniu->getLink($model[$field]);
                    $res = new Resource();
                    $res->filename = $randName;
                    $res->md5 = $md5;
                    $res->media_type = "image/$ext";
                    $res->save();
                }
            }
        }

        return $model->save();
    }
}
