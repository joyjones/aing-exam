<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $logo
 * @property string $headimg_q
 * @property string $headimg_a
 * @property string $caption
 * @property string $desc
 * @property string $dialog
 * @property boolean $show_score
 * @property integer $utime
 * @property integer $status
 * @property string $qrcode_img
 *
 * @property Evaluation[] $evaluations
 * @property Question[] $questions
 * @property PlayerEvaluation[] $playerEvaluations
 */
class Project extends \yii\db\ActiveRecord
{
    const TYPE_QUESTION = 1;
    const TYPES = [
        self::TYPE_QUESTION => '问答'
    ];
    const STATUS_INVALID = 0;
    const STATUS_TESTING = 1;
    const STATUS_PUBLISH = 2;
    const STATUS = [
        self::STATUS_INVALID => '无效',
        self::STATUS_TESTING => '测试中',
        self::STATUS_PUBLISH => '已发布',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'status'], 'required'],
            [['type', 'utime', 'status'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['show_score'], 'boolean'],
            [['logo', 'headimg_q', 'headimg_a', 'caption', 'qrcode_img'], 'string', 'max' => 255],
            [['desc', 'dialog'], 'string', 'max' => 500],
            [['logo'], 'file', 'extensions'=>'jpg, jpeg, png']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'type' => Yii::t('common', 'Type'),
            'logo' => Yii::t('common', 'Logo'),
            'headimg_q' => Yii::t('common', 'Headimg Q'),
            'headimg_a' => Yii::t('common', 'Headimg A'),
            'caption' => Yii::t('common/eval', 'Evaluation Caption'),
            'desc' => Yii::t('common', 'Project Description'),
            'dialog' => Yii::t('common', 'Project Dialog'),
            'utime' => Yii::t('common', 'Utime'),
            'status' => Yii::t('common', 'Status'),
            'qrcode_img' => Yii::t('common', 'Qrcode Img'),
            'show_score' => Yii::t('common', 'Show Score'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluations()
    {
        return $this->hasMany(Evaluation::className(), ['project_id' => 'id'])->orderBy('score');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['project_id' => 'id'])->orderBy('index');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerEvaluations()
    {
        return $this->hasMany(PlayerEvaluation::className(), ['project_id' => 'id']);
    }

    public function getPlayerCount()
    {
        return $this->hasMany(PlayerEvaluation::className(), ['project_id' => 'id'])->count();
    }

    public function extraFields()
    {
        return ['questions', 'evaluations', 'playerCount'];
    }

    /**
     * @param PlayerEvaluation $playerEval
     * @return bool
     */
    public function calcEvaluationResult($playerEval)
    {
        $score = 0;
        $anwsers = $playerEval->playerAnswers;
        foreach ($anwsers as $a){
            $score += $a->option->score;
        }
        $evals = $this->evaluations;
        $resultEval = null;
        $lastEval = null;
        foreach ($evals as $e){
            $lastEval = $e;
            if ($e->score > $score)
                break;
            else
                $resultEval = $e;
        }
        if ($resultEval == null && $lastEval != null)
            $resultEval = $lastEval;
        if ($resultEval != null){
            $playerEval->result_score = $score;
            $playerEval->result_eval_id = $resultEval->id;
            return true;
        }
        return false;
    }
}
