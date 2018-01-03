<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

if (!isset($embed)) {
    $this->title = Yii::t('common', 'Banners');
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('common', 'Create Banner'), ['/banner/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'index',
                'value' => function($model) {return $model->index + 1;}
            ],
            [
                'attribute' => 'imgurl',
                'format' => ['image',['width'=>'120']],
                'value' => function($data){
                    return 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$data->imgurl;
                }
            ],
            'title',
            'linkurl',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{order-decrease}{order-increase}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil" style="margin-right:8px;"></span>', ['/banner/update', 'id' => $model->id], [
                            'title' => Yii::t('common', 'Update'),
                        ]);
                    },
                    'order-decrease' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-arrow-up" style="margin-right:8px;"></span>', ['/banner/decrease-order', 'id' => $model->id], [
                            'title' => Yii::t('common', 'Decrease Order'),
                        ]);
                    },
                    'order-increase' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-arrow-down" style="margin-right:8px;"></span>', ['/banner/increase-order', 'id' => $model->id], [
                            'title' => Yii::t('common', 'Increase Order'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        $options = [
                            'title' => Yii::t('common', 'Delete'),
                            'aria-label' => Yii::t('common', 'Delete'),
                            'data-confirm' => Yii::t('common', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/banner/delete', 'id' => $model->id], $options);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
