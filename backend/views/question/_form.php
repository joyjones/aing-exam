<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\QuestionOption;
use yii\data\ActiveDataProvider;
use kartik\select2\Select2;
use common\models\Question;

/* @var $this yii\web\View */
/* @var $model common\models\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'index')->hiddenInput()->label(false) ?>

    <?php
        echo $form->field($model, 'type')->widget(Select2::classname(), [
            'data' => Question::TYPES,
            'hideSearch' => true,
            'disabled' => $model->isNewRecord ? false : true,
            'options' => [
                'placeholder' => '请选择选项类型...',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
    ?>

    <?= $form->field($model, 'topic')->textInput(['maxlength' => true]) ?>

    <?php
        if (!$model->isNewRecord) {
            $dpOptions = new ActiveDataProvider([
                'query' => QuestionOption::find()->where(['question_id' => $model->id]),
                'sort' => ['defaultOrder' => ['index' => SORT_ASC]]
            ]);
            echo $this->render('/question-option/index', [
                'dataProvider' => $dpOptions,
                'question_id' => $model->id,
                'question' => $model
            ]);
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('common', $model->isNewRecord ? 'Create' : 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
