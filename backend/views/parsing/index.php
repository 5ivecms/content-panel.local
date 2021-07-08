<?php

use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\data\ArrayDataProvider;

/* @var $domains array */

$this->title = 'Парсинг';

$provider = new ArrayDataProvider([
    'allModels' => $domains,
]);

$provider->pagination->pageSize = 100000;

$form = ActiveForm::begin(['action' => ['start']]);
?>

<div class="form-group text-right">
    <?= Html::submitButton('Запустить', ['class' => 'btn btn-primary']) ?>
</div>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">Настройки</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <?= Html::label('Доменов параллельно') ?>
                            <?= Html::input('number', 'chunk', 1, ['class' => 'form-control', 'min' => 1]) ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <?= Html::label('Запросов на каждый домен') ?>
                            <?= Html::input('number', 'requests', 10, ['class' => 'form-control', 'min' => 1]) ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <?= Html::label('Пауза между чанками') ?>
                            <?= Html::input('number', 'chunk_pause', 0, ['class' => 'form-control', 'min' => 0]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">Домены</h3>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $provider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-sm', 'style' => 'margin:0;'],
                    'summary' => false,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'rowSelectedClass' => GridView::BS_TABLE_INFO,
                            'checkboxOptions' => function ($model) {
                                return ['value' => $model->id];
                            },
                        ],

                        [
                            'contentOptions' => function ($model, $key, $index, $column) {
                                return ['style' => 'width:100%'];
                            },
                            'attribute' => 'domain',
                            'label' => 'Домен',
                            'format' => 'raw',
                            'content' => function ($model, $key) use ($form) {
                                return $model->domain;
                            },
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>