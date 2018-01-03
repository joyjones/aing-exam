<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_answer".
 *
 * @property integer $id
 * @property integer $player_eval_id
 * @property integer $option_id
 * @property integer $time
 *
 * @property PlayerEvaluation $playerEval
 * @property QuestionOption $option
 */
class PlayerAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'player_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['player_eval_id', 'option_id', 'time'], 'required'],
            [['player_eval_id', 'option_id', 'time'], 'integer'],
            [['player_eval_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlayerEvaluation::className(), 'targetAttribute' => ['player_eval_id' => 'id']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionOption::className(), 'targetAttribute' => ['option_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'player_eval_id' => Yii::t('common', 'Player Eval ID'),
            'option_id' => Yii::t('common', 'Option ID'),
            'time' => Yii::t('common', 'Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerEval()
    {
        return $this->hasOne(PlayerEvaluation::className(), ['id' => 'player_eval_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(QuestionOption::className(), ['id' => 'option_id']);
    }
}
