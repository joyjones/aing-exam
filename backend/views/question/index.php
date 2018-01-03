<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $projects */

$this->title = Yii::t('common', 'Questions');

?>
<div class="question-index">

    <p>
        <?= Html::a(Yii::t('common', 'Create Question'), ['/question/create', 'project_id' => $project->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'index',
//            'name',
            [
                'attribute' => 'type',
                'value' => function($model) {return \common\models\Question::TYPES[$model->type];}
            ],
            'topic',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{order-decrease}{order-increase}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil" style="margin-right:8px;"></span>', ['/question/update', 'id' => $model->id], [
                            'title' => Yii::t('common', 'Update'),
                        ]);
                    },
                    'order-decrease' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-arrow-up" style="margin-right:8px;"></span>', ['/question/decrease-order', 'id' => $model->id], [
                            'title' => Yii::t('common', 'Decrease Order'),
                        ]);
                    },
                    'order-increase' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-arrow-down" style="margin-right:8px;"></span>', ['/question/increase-order', 'id' => $model->id], [
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
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/question/delete', 'id' => $model->id], $options);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
