<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common/eval', 'Evaluations');
?>
<div class="evaluation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('common/eval', 'Create Evaluation'), ['evaluation/create', 'project_id' => $project->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'score',
            [
                'attribute' => 'image',
                'format' => ['image',['width'=>'80']],
                'value' => function($data){
                    return 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$data->image;
                }
            ],
            'brief',
            [
                'attribute' => 'desc',
                'value' => function($model){return StringHelper::truncate($model->desc, 50);},
                'headerOptions' => ['style' => 'width:35%'],
            ],
            'share_title',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil" style="margin-right:8px;"></span>', ['/evaluation/update', 'id' => $model->id], [
                            'title' => Yii::t('common', 'Update'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        if ($model->score <= 0)
                            return '';
                        $options = [
                            'title' => Yii::t('common', 'Delete'),
                            'aria-label' => Yii::t('common', 'Delete'),
                            'data-confirm' => Yii::t('common', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/evaluation/delete', 'id' => $model->id], $options);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
