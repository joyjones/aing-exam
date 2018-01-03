<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Projects');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('common', 'Create Project'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'logo',
                'format' => ['image',['width'=>'80']],
                'value' => function($data){
                    return 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$data->logo;
                }
            ],
            'name',
            [
                'attribute' => 'name',
                'value' => function($data){
                    return "[$data->id] $data->name";
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($data){
                    return \common\models\Project::TYPES[$data->type];
                },
                'headerOptions' => ['style' => 'width:5%'],
            ],
            [
                'attribute' => 'status',
                'value' => function($data){
                    return \common\models\Project::STATUS[$data->status];
                },
                'headerOptions' => ['style' => 'width:7%'],
            ],
            [
                'attribute' => 'headimg_q',
                'format' => ['image',['width'=>'80']],
                'value' => function($data){
                    return 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$data->headimg_q;
                }
            ],
            [
                'attribute' => 'headimg_a',
                'format' => ['image',['width'=>'80']],
                'value' => function($data){
                    return 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$data->headimg_a;
                }
            ],
            'caption',
            [
                'attribute' => 'desc',
                'headerOptions' => ['style' => 'width:25%'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil" style="margin-right:8px;"></span>', ['project/update-fields', 'id' => $model->id], [
                            'title' => Yii::t('common', 'Update'),
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
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                    },
                ]
            ]
        ],
    ]); ?>
    <p>
        <?= $this->render('/banner/index', ['dataProvider' => $bannerData]) ?>
    </p>
</div>
