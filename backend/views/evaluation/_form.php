<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Evaluation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="evaluation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'project_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'score')->textInput() ?>

    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'name' => $model->image,
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=> [
            'allowedFileExtensions'=> ['jpg', 'jpeg', 'png'],
            'showUpload' => false,
            'initialPreview' => (strlen($model->image) > 0 ? 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$model->image : ''),
            'initialPreviewAsData' => strlen($model->image) > 0,
        ]
    ])
    ?>

    <?= $form->field($model, 'brief')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'share_title')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
