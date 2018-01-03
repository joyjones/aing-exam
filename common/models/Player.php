<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player".
 *
 * @property integer $id
 * @property string $unique_id
 * @property string $nickname
 * @property integer $head_image
 * @property integer $last_login_at
 * @property integer $register_at
 *
 * @property PlayerAnswer[] $playerAnswers
 */
class Player extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'player';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_id', 'last_login_at', 'register_at'], 'required'],
            [['head_image', 'last_login_at', 'register_at'], 'integer'],
            [['unique_id', 'nickname'], 'string', 'max' => 32],
            [['unique_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'unique_id' => Yii::t('common', 'Unique ID'),
            'nickname' => Yii::t('common', 'Nickname'),
            'head_image' => Yii::t('common', 'Head Image'),
            'last_login_at' => Yii::t('common', 'Last Login At'),
            'register_at' => Yii::t('common', 'Register At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerAnswers()
    {
        return $this->hasMany(PlayerAnswer::className(), ['player_id' => 'id']);
    }

    public function beginEvaluation($projectId)
    {
        if (!Project::find()->where(['id' => $projectId])->exists())
            return ['errcode' => 404];
        $plrEval = new PlayerEvaluation();
        $plrEval->project_id = $projectId;
        $plrEval->player_id = $this->id;
        $plrEval->begin_time = time();
        $quest = Question::find()->where([
            'project_id' => $projectId,
            'index' => 0
        ])->one();
        if ($quest)
            $plrEval->question_index = 0;
        if (!$plrEval->save())
            return ['errcode' => 501];
        return [
            'errcode' => 0,
            'eval_id' => $plrEval->id,
            'firstQuestion' => $quest,
            'options' => $quest ? $quest->questionOptions : null
        ];
    }

    public function answerQuestion($plrEvalId, $optionId)
    {
        $plrEval = PlayerEvaluation::findOne($plrEvalId);
        if (!$plrEval)
            return ['errcode' => 404];
        $opt = QuestionOption::findOne($optionId);
        if (!$opt)
            return ['errcode' => 404];
        $plrAnswer = new PlayerAnswer();
        $plrAnswer->option_id = $optionId;
        $plrAnswer->player_eval_id = $plrEval->id;
        $plrAnswer->time = time();
        if (!$plrAnswer->save())
            return ['errcode' => 501];
        $nextQuest = null;
        $questCount = count($plrEval->project->questions);
        $plrEval->question_index++;
        if ($plrEval->question_index == $questCount){
            $plrEval->finish_time = time();
            $plrEval->project->calcEvaluationResult($plrEval);
        }else{
            $nextQuest = Question::find()->where([
                'project_id' => $plrEval->project->id,
                'index' => $plrEval->question_index
            ])->one();
        }
        $plrEval->save();
        return [
            'errcode' => 0,
            'score' => $opt->score,
            'nextQuestion' => $nextQuest,
            'nextOptions' => $nextQuest ? $nextQuest->questionOptions : null,
            'result' => $plrEval->result_eval_id ? [
                'eval' => $plrEval->resultEval,
                'score' => $plrEval->result_score
            ] : null
        ];
    }

    public function extraFields()
    {
        return [
            'playerAnswers',
            'beginEvaluation' => function($model){
                $reqs = Yii::$app->request->get();
                return $model->beginEvaluation($reqs['projectId']);
            },
            'answerQuestion' => function($model){
                $reqs = Yii::$app->request->get();
                return $model->answerQuestion($reqs['evalId'], $reqs['optionId']);
            }
        ];
    }
}
