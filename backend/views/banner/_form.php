<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'index')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php
        $openPreview = strlen($model->imgurl) > 0;
        echo $form->field($model, 'imgurl')->widget(FileInput::classname(), [
            'name' => $model->imgurl,
            'options'=>['accept'=>'image/*'],
            'pluginOptions'=> [
                'allowedFileExtensions'=> ['jpg', 'jpeg', 'png'],
                'showUpload' => false,
                'initialPreview' => $openPreview ? 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$model->imgurl : '',
                'initialPreviewAsData' => $openPreview,
            ]
        ]);
    ?>

    <?= $form->field($model, 'linkurl')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
