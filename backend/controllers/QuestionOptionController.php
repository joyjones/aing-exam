<?php

namespace backend\controllers;

use common\models\Question;
use Yii;
use common\models\QuestionOption;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuestionOptionController implements the CRUD actions for QuestionOption model.
 */
class QuestionOptionController extends Controller
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
     * Lists all QuestionOption models.
     * @return mixed
     */
    public function actionIndex($question_id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => QuestionOption::find()->where(['question_id' => $question_id]),
            'sort' => ['defaultOrder' => ['index' => SORT_ASC]]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'question_id' => $question_id
        ]);
    }

    /**
     * Displays a single QuestionOption model.
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
     * Creates a new QuestionOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($question_id)
    {
        $model = new QuestionOption();
        $model->index = QuestionOption::find()->where(['question_id' => $question_id])->count();

        if ($model->load(Yii::$app->request->post())) {
            $failed = false;
            if ($model->question->type == Question::TYPE_IMAGE) {
                if (!ProjectController::submitData($model, null, ['content']))
                    $failed = true;
            }else{
                $failed = !$model->save();
            }
            if (!$failed)
                return $this->redirect(['/question/update', 'id' => $model->question_id]);
        }

        $model->question_id = $question_id;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QuestionOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $failed = false;
            if ($model->question->type == Question::TYPE_IMAGE) {
                $model_src = $this->findModel($id);
                if (!ProjectController::submitData($model, $model_src, ['content']))
                    $failed = true;
            }else{
                $failed = !$model->save();
            }
            if (!$failed)
                return $this->redirect(['/question/update', 'id' => $model->question_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing QuestionOption model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->db->createCommand('UPDATE `question_option` SET `index`=`index`-"1" WHERE `question_id`=:qid AND `index`>:val')
            ->bindValue(':qid', $model->question_id)
            ->bindValue(':val', $model->index)
            ->execute();
        $questionId = $model->question_id;

        $model->delete();

        return $this->redirect(['/question/update', 'id' => $questionId]);
    }

    public function actionDecreaseOrder($id)
    {
        $model = $this->findModel($id);
        if ($model->index > 0) {
            $prev = QuestionOption::find()->where([
                'question_id' => $model->question_id,
                'index' => $model->index - 1
            ])->one();
            if ($prev){
                $prev->index++;
                $prev->save();
            }
            --$model->index;
            $model->save();
        }
        return $this->redirect(['/question/update', 'id' => $model->question_id]);
    }

    public function actionIncreaseOrder($id)
    {
        $model = $this->findModel($id);
        $next = QuestionOption::find()->where([
            'question_id' => $model->question_id,
            'index' => $model->index + 1
        ])->one();
        if ($next != null) {
            $next->index--;
            $next->save();
            ++$model->index;
            $model->save();
        }
        return $this->redirect(['/question/update', 'id' => $model->question_id]);
    }
    /**
     * Finds the QuestionOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QuestionOption the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QuestionOption::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
