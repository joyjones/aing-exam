<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\QuestionOption */

$this->title = Yii::t('common', 'Create Question Option');

$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Projects'), 'url' => ['/project/index']];
$this->params['breadcrumbs'][] = ['label' => $model->question->project->name, 'url' => ['/project/update', 'id' => $model->question->project->id]];
$this->params['breadcrumbs'][] = ['label' => $model->question->name, 'url' => ['/question/update', 'id' => $model->question->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-option-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
