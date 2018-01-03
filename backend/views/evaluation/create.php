<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Evaluation */

$this->title = Yii::t('common/eval', 'Create Evaluation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $project->name, 'url' => ['/project/update', 'id' => $model->project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="evaluation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
