<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $project_id
 * @property integer $index
 * @property string $topic
 *
 * @property PlayerAnswer[] $playerAnswers
 * @property Project $project
 * @property QuestionOption[] $questionOptions
 */
class Question extends \yii\db\ActiveRecord
{
    const TYPE_TEXT = 1;
    const TYPE_IMAGE = 2;
    const TYPES = [
        self::TYPE_TEXT => '文字',
        self::TYPE_IMAGE => '图片'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'required'],
            [['type', 'project_id', 'index'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['topic'], 'string', 'max' => 255],
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
            'name' => Yii::t('common', 'Name'),
            'type' => Yii::t('common', 'Type'),
            'project_id' => Yii::t('common', 'Project ID'),
            'index' => Yii::t('common', 'Index'),
            'topic' => Yii::t('common', 'Topic'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerAnswers()
    {
        return $this->hasMany(PlayerAnswer::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionOptions()
    {
        return $this->hasMany(QuestionOption::className(), ['question_id' => 'id'])->orderBy('index');
    }

    public function extraFields()
    {
        return [
            'questionOptions'
        ];
    }
}
