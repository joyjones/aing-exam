<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use common\models\Project;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php
        echo $form->field($model, 'type')->widget(Select2::classname(), [
            'data' => Project::TYPES,
            'hideSearch' => true,
            'disabled' => $model->isNewRecord ? false : true,
            'options' => [
                'placeholder' => '请选择项目类型...',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
    ?>

    <?= $form->field($model, 'logo')->widget(FileInput::classname(), [
            'name' => $model->logo,
            'options'=>['accept'=>'image/*'],
            'pluginOptions'=> [
                'allowedFileExtensions'=> ['jpg', 'jpeg', 'png'],
                'showUpload' => false,
                'initialPreview' => (strlen($model->logo) > 0 ? 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$model->logo : ''),
                'initialPreviewAsData' => strlen($model->logo) > 0,
            ]
        ])
    ?>

    <?= $form->field($model, 'headimg_q')->widget(FileInput::classname(), [
        'name' => $model->headimg_q,
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=> [
            'allowedFileExtensions'=> ['jpg', 'jpeg', 'png'],
            'showUpload' => false,
            'initialPreview' => (strlen($model->headimg_q) > 0 ? 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$model->headimg_q : ''),
            'initialPreviewAsData' => strlen($model->headimg_q) > 0,
        ]
    ])
    ?>

    <?= $form->field($model, 'headimg_a')->widget(FileInput::classname(), [
        'name' => $model->headimg_a,
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=> [
            'allowedFileExtensions'=> ['jpg', 'jpeg', 'png'],
            'showUpload' => false,
            'initialPreview' => (strlen($model->headimg_a) > 0 ? 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$model->headimg_a : ''),
            'initialPreviewAsData' => strlen($model->headimg_a) > 0,
        ]
    ])
    ?>

    <?= $form->field($model, 'qrcode_img')->widget(FileInput::classname(), [
        'name' => $model->qrcode_img,
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=> [
            'allowedFileExtensions'=> ['jpg', 'jpeg', 'png'],
            'showUpload' => false,
            'initialPreview' => (strlen($model->qrcode_img) > 0 ? 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$model->qrcode_img : ''),
            'initialPreviewAsData' => strlen($model->qrcode_img) > 0,
        ]
    ])
    ?>

    <?= $form->field($model, 'caption')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'dialog')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'show_score')->checkbox() ?>

    <?php
        echo $form->field($model, 'status')->widget(Select2::classname(), [
            'data' => Project::STATUS,
            'hideSearch' => true,
            'disabled' => $model->isNewRecord,
            'options' => [
                'placeholder' => '请设置项目状态...',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('common', $model->isNewRecord ? 'Create' : 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
