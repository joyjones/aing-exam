<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_option".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $index
 * @property string $content
 * @property integer $score
 *
 * @property Question $question
 */
class QuestionOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'index'], 'required'],
            [['question_id', 'index', 'score'], 'integer'],
            [['content'], 'string', 'max' => 500],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'question_id' => Yii::t('common', 'Question ID'),
            'index' => Yii::t('common', 'Index'),
            'content' => Yii::t('common', 'Content'),
            'score' => Yii::t('common', 'Score'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    public function getIndexChar()
    {
        return chr(ord('A') + $this->index);
    }
}
