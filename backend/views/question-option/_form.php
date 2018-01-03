<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\QuestionOption */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'index')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'question_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'score')->textInput() ?>

    <?php
        if ($model->question->type == \common\models\Question::TYPE_TEXT)
            echo $form->field($model, 'content')->textarea(['maxlength' => true]);
        else if ($model->question->type == \common\models\Question::TYPE_IMAGE) {
            $openPreview = strlen($model->content) > 0;
            echo $form->field($model, 'content')->widget(FileInput::classname(), [
                'name' => $model->content,
                'options'=>['accept'=>'image/*'],
                'pluginOptions'=> [
                    'allowedFileExtensions'=> ['jpg', 'jpeg', 'png'],
                    'showUpload' => false,
                    'initialPreview' => $openPreview ? 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$model->content : '',
                    'initialPreviewAsData' => $openPreview,
                ]
            ]);
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('common', $model->isNewRecord ? 'Create' : 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
