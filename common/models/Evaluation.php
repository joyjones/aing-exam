<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "evaluation".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $image
 * @property integer $score
 * @property string $brief
 * @property string $desc
 * @property string $share_title
 *
 * @property Project $project
 */
class Evaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'evaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'score', 'brief', 'desc'], 'required'],
            [['project_id', 'score'], 'integer'],
            [['image', 'share_title'], 'string', 'max' => 255],
            [['brief'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 1000],
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
            'project_id' => Yii::t('common', 'Project ID'),
            'image' => Yii::t('common/eval', 'Evaluation Image'),
            'score' => Yii::t('common/eval', 'Evaluation Score'),
            'brief' => Yii::t('common/eval', 'Evaluation Brief'),
            'desc' => Yii::t('common/eval', 'Evaluation Description'),
            'share_title' => Yii::t('common/eval', 'Share Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
}
