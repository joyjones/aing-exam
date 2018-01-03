<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Question;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Question Options');

?>
<div class="question-option-index">
    <p>
        <?= Html::a(Yii::t('common', 'Create Question Option'), ['/question-option/create', 'question_id' => $question_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
        $opts = [
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'index',
                    'value' => function($model){
                        return $model->indexChar;
                    }
                ]
            ]
        ];
        if ($question->type == Question::TYPE_TEXT)
            $opts['columns'][] = 'content';
        else
            $opts['columns'][] = [
                'attribute' => 'content',
                'format' => ['image',['width'=>'80','height'=>'80']],
                'value' => function($data){
                    return 'http://'.Yii::$app->params['qiniu']['domain'].'/'.$data->content;
                }
            ];
        $opts['columns'][] = 'score';
        $opts['columns'][] = [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{order-decrease}{order-increase}{delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil" style="margin-right:8px;"></span>', ['/question-option/update', 'id' => $model->id], [
                        'title' => Yii::t('common', 'Update'),
                    ]);
                },
                'order-decrease' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-arrow-up" style="margin-right:8px;"></span>', ['/question-option/decrease-order', 'id' => $model->id], [
                        'title' => Yii::t('common', 'Decrease Order'),
                    ]);
                },
                'order-increase' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-arrow-down" style="margin-right:8px;"></span>', ['/question-option/increase-order', 'id' => $model->id], [
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
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/question-option/delete', 'id' => $model->id], $options);
                },
            ]
        ];
        echo GridView::widget($opts);
    ?>
</div>
