<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Players');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="player-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <h3><?= "访问总数：$totalCount" ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'unique_id',
            [
                'attribute' => 'last_login_at',
                'value' => function($model){
                    return date('Y-m-d H:i:s', $model->last_login_at);
                }
            ],
            [
                'attribute' => 'register_at',
                'value' => function($model){
                    return date('Y-m-d H:i:s', $model->register_at);
                }
            ],
        ],
    ]); ?>
</div>
