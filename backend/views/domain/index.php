<?php

use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DomainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сайты';
$this->params['breadcrumbs'][] = $this->title;

$domainOptions = [
    Url::to(['statistic/update-selected']) => 'Обновить данные',
    Url::to(['statistic/update-all']) => 'Обновить все домены',
];
?>

    <style>
        .action-cell a {
            display: inline-block;
            vertical-align: top;
        }

        .action-cell a + a {
            margin-left: 10px;
        }
    </style>

<div class="domain-index">

    <?= GridView::widget([
        'id' => 'domainsGrid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => '\kartik\grid\SerialColumn'],
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::BS_TABLE_INFO,
                'checkboxOptions' => function ($model) {
                    return ['value' => $model->id];
                },
            ],

            'domain',
            [
                'attribute' => 'countArticles',
                'label' => 'Статей',
                'value' => 'statistic.countArticles'
            ],
            [
                'attribute' => 'countKeywords',
                'label' => 'Keywords',
                'value' => 'statistic.countKeywords'
            ],
            [
                'attribute' => 'countNewKeywords',
                'label' => 'Новых keywords',
                'value' => 'statistic.countNewKeywords'
            ],

            'comment:ntext',
            [
                'class' => 'kartik\grid\ActionColumn',
                'width' => '90px',
                'header' => '',
                'headerOptions' => ['style' => 'width:90px', 'class' => 'action-cell'],
                'contentOptions' => ['style' => 'width:90px', 'class' => 'action-cell'],
                'template' => '{view}{update}{delete}{statisticUpdate}',
                'buttons' => [
                    'statisticUpdate' => function ($url, $model) {
                        return Html::a('<span class="fas fa-sync"></span>', ['statistic/update', 'id' => $model->id]);
                    },
                ],
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['create'], [
                        'class' => 'btn btn-success',
                        'title' => 'Добавить сайт'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['index'], [
                        'class' => 'btn btn-outline-secondary',
                        'title' => 'Обновить таблицу',
                        'data-pjax' => 1,
                    ]),
                'options' => ['class' => 'btn-group mr-2']
            ],
            '{export}',
            '{toggleData}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group'],
        'exportContainer' => ['class' => 'btn-group mr-2'],
        'responsive' => true,
        'panel' => [
            'heading' => '<h3 class="card-title">Список сайтов</h3>',
            'type' => 'default',
            'after' => false,
            'before' =>
                '<div class="form-inline">' .
                '<b class="d-inline-block mr-3">Действие: </b>' .
                Html::dropDownList('domainAction', null, $domainOptions, ['id' => 'domainAction', 'class' => 'form-control mr-2']) .
                '<button id="actionBtn" type="submit" class="btn btn-primary mr-4">Выполнить</button>' .
                '</div>'
        ],
    ]); ?>

</div>

<?php
$js = <<<JS
$(document).on('click', '#actionBtn', function (event) {
    event.preventDefault();

    var Ids = $('#domainsGrid').yiiGridView('getSelectedRows');
    var action = $('#domainAction').val();
    var actionText = $('#domainAction').find('option:selected').text();

    if (confirm('Точно ' + actionText + '?')) {
        $.ajax({
            type: 'POST',
            url: action,
            data: {ids: Ids},
            dataType: 'JSON',
            success: function (resp) {
                if (resp.success) {
                    alert(resp.msg);
                }
                location.reload();
            }
        });
    }
});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);