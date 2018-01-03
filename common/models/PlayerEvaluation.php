<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_evaluation".
 *
 * @property integer $id
 * @property integer $player_id
 * @property integer $project_id
 * @property integer $question_index
 * @property integer $result_eval_id
 * @property integer $result_score
 * @property integer $begin_time
 * @property integer $finish_time
 *
 * @property PlayerAnswer[] $playerAnswers
 * @property Evaluation $resultEval
 * @property Player $player
 * @property Project $project
 */
class PlayerEvaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'player_evaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['player_id', 'project_id', 'begin_time'], 'required'],
            [['player_id', 'project_id', 'question_index', 'result_eval_id', 'result_score', 'begin_time', 'finish_time'], 'integer'],
            [['result_eval_id'], 'exist', 'skipOnError' => true, 'targetClass' => Evaluation::className(), 'targetAttribute' => ['result_eval_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => Player::className(), 'targetAttribute' => ['player_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'player_id' => Yii::t('common', 'Player ID'),
            'project_id' => Yii::t('common', 'Project ID'),
            'question_index' => Yii::t('common', 'Question Index'),
            'result_eval_id' => Yii::t('common', 'Result Eval ID'),
            'result_score' => Yii::t('common', 'Result Score'),
            'begin_time' => Yii::t('common', 'Begin Time'),
            'finish_time' => Yii::t('common', 'Finish Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerAnswers()
    {
        return $this->hasMany(PlayerAnswer::className(), ['player_eval_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultEval()
    {
        return $this->hasOne(Evaluation::className(), ['id' => 'result_eval_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(Player::className(), ['id' => 'player_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
}
