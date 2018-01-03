<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = Yii::t('common', 'Create Question');
$project = \common\models\Project::findOne($model->project_id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Projects'), 'url' => ['/project/index']];
$this->params['breadcrumbs'][] = ['label' => $project->name, 'url' => ['/project/update', 'id' => $model->project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-create">

    <h1><?= Yii::t('common', Html::encode($this->title)) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
