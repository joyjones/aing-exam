<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */

$this->title = Yii::t('common', 'Update Banner'). ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Banners'), 'url' => ['/project/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
