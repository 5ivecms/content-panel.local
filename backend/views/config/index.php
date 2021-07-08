<?php

use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Конфигурации';
$this->params['breadcrumbs'][] = $this->title;

$configOptions = [
    Url::to(['config/update-selected']) => 'Обновить данные',
];
?>
    <style>
        .table th {
            line-height: 1.2 !important;
        }
        .table td .popover {
            display: none;
        }
        .text-success .kv-editable-link {
            color: #28a745!important;
        }
        .text-danger .kv-editable-link {
            color: #dc3545!important;
        }
    </style>
    <div class="config-index">

        <?= GridView::widget([
            'id' => 'grid',
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
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'width' => '30px',
                    'header' => '',
                    'headerOptions' => ['style' => 'width:90px', 'class' => 'action-cell'],
                    'contentOptions' => ['style' => 'width:90px', 'class' => 'action-cell'],
                    'template' => '{statisticUpdate}',
                    'buttons' => [
                        'statisticUpdate' => function ($url, $model) {
                            return Html::a('<span class="fas fa-sync"></span>', ['config/update', 'id' => $model->domain->id]);
                        },
                    ],
                ],
                [
                    'attribute' => 'domainName',
                    'vAlign' => 'top',
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->domain->domain;
                    },
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'cron_keywords_enabled',
                    'label' => 'Парсинг keywords',
                    'encodeLabel' => false,
                    'hAlign' => GridView::ALIGN_CENTER,
                    'value' => function ($model, $key, $index, $widget) {
                        return ($model->cron_keywords_enabled) ? 'Вкл.' : 'Выкл.';
                    },
                    'contentOptions' => function ($data) {
                        if ($data->cron_keywords_enabled) {
                            $class = 'text-success';
                        } else {
                            $class = 'text-danger';
                        }
                        return ['class' => $class];
                    },
                    'readonly' => false,
                    'editableOptions' => [
                        'header' => 'Парсинг keywords',
                        'asPopover' => true,
                        'size' => 'md',
                        'inputType' => kartik\editable\Editable::INPUT_SELECT2,
                        'formOptions' => [
                            'action' => '/admin/config/editable'
                        ],
                        'options' => [
                            'data' => [0 => 'Выключить', 1 => 'Включить'],
                        ],
                    ],
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'cron_keywords_limit',
                    'label' => 'Кол-во keywords',
                    'encodeLabel' => false,
                    'hAlign' => GridView::ALIGN_CENTER,
                    'value' => 'cron_keywords_limit',
                    'readonly' => false,
                    'editableOptions' => [
                        'header' => 'Кол-во keywords',
                        'asPopover' => true,
                        'size' => 'md',
                        'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                        'formOptions' => [
                            'action' => '/admin/config/editable'
                        ],
                        'options' => [
                            'pluginOptions' => ['min' => 0, 'max' => 50]
                        ],
                    ],
                ],
            ],
            'toolbar' => [
                [
                    'content' =>
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
                'heading' => '<h3 class="card-title">Конфигурации</h3>',
                'type' => 'default',
                'after' => false,
                'before' =>
                    '<div class="form-inline">' .
                    '<b class="d-inline-block mr-3">С выбранными: </b>' .
                    Html::dropDownList('configAction', null, $configOptions, ['id' => 'configAction', 'class' => 'form-control mr-2']) .
                    '<button id="actionBtn" type="submit" class="btn btn-primary mr-4">Выполнить</button>' .
                    '</div>'
            ],
        ]); ?>
    </div>

<?php
$js = <<<JS
$(document).on('click', '#actionBtn', function (event) {
    event.preventDefault();
    
    var Ids = $('#grid').yiiGridView('getSelectedRows');
    var action = $('#configAction').val();
    var actionText = $('#configAction').find('option:selected').text();
   
    if (Ids.length > 0) {
        if (confirm('Точно ' + actionText + ' у выбронных доменов?')) {
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
    } else {
        alert('Выберите домены');
    }
});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
