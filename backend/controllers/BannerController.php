<?php

namespace backend\controllers;

use Yii;
use common\models\Banner;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Banner::find(),
            'sort' => ['defaultOrder' => ['index' => SORT_ASC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banner model.
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
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banner();
        $model->index = Banner::find()->count();

        if ($model->load(Yii::$app->request->post()) && ProjectController::submitData($model, null, ['imgurl'])) {
            return $this->redirect(['/project/index']);
        }else{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Banner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model_src = $this->findModel($id);
            if (ProjectController::submitData($model, $model_src, ['imgurl']))
                return $this->redirect(['/project/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->db->createCommand('UPDATE `banner` SET `index`=`index`-"1" WHERE `index`>:val')
            ->bindValue(':val', $model->index)
            ->execute();
        $model->delete();
        return $this->redirect(['/project/index']);
    }

    public function actionDecreaseOrder($id)
    {
        $model = $this->findModel($id);
        if ($model->index > 0) {
            $prev = Banner::find()->where(['index' => $model->index - 1])->one();
            if ($prev){
                $prev->index++;
                $prev->save();
            }
            --$model->index;
            $model->save();
        }
        return $this->redirect(['/project/index']);
    }

    public function actionIncreaseOrder($id)
    {
        $model = $this->findModel($id);
        $next = Banner::find()->where(['index' => $model->index + 1])->one();
        if ($next != null) {
            $next->index--;
            $next->save();
            ++$model->index;
            $model->save();
        }
        return $this->redirect(['/project/index']);
    }
    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
