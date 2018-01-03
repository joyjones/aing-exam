<?php

namespace backend\controllers;

use common\models\Project;
use common\models\QuestionOption;
use Yii;
use common\models\Question;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
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
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Question::find(),
            'sort' => ['defaultOrder' => ['index' => SORT_ASC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'projects' => ArrayHelper::map(Project::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name')
        ]);
    }

    /**
     * Displays a single Question model.
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
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id)
    {
        $model = new Question();
        $model->index = Question::find()->where(['project_id' => $project_id])->count();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/question/update', 'id' => $model->id]);
        } else {
            $model->project_id = $project_id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/project/update', 'id' => $model->project_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->db->createCommand('UPDATE `question` SET `index`=`index`-"1" WHERE `project_id`=:prj AND `index`>:idx')
            ->bindValue(':prj', $model->project_id)
            ->bindValue(':idx', $model->index)
            ->execute();
        $projectId = $model->project_id;

        $model->delete();

        return $this->redirect(['/project/update', 'id' => $projectId]);
    }

    public function actionDecreaseOrder($id)
    {
        $model = $this->findModel($id);
        if ($model->index > 0) {
            $prev = Question::find()->where(['project_id' => $model->project_id, 'index' => $model->index - 1])->one();
            if ($prev){
                $prev->index++;
                $prev->save();
            }
            --$model->index;
            $model->save();
        }
        return $this->redirect(['/project/update', 'id' => $model->project_id]);
    }

    public function actionIncreaseOrder($id)
    {
        $model = $this->findModel($id);
        $next = Question::find()->where(['project_id' => $model->project_id, 'index' => $model->index + 1])->one();
        if ($next != null) {
            $next->index--;
            $next->save();
            ++$model->index;
            $model->save();
        }
        return $this->redirect(['/project/update', 'id' => $model->project_id]);
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
