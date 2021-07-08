<?php

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\bootstrap4\Html;
use yii\data\ArrayDataProvider;

/* @var $domains array */

$this->title = 'Ключевые фразы';
$this->params['breadcrumbs'][] = $this->title;

$provider = new ArrayDataProvider([
    'allModels' => $domains,
]);

$provider->pagination->pageSize = 100000;

$form = ActiveForm::begin(['action' => ['add']]);
?>

<div class="card">
    <div class="card-header bg-light">
        <h3 class="card-title">Добавить ключевые фразы</h3>
    </div>
    <?= Html::beginForm(['keyword/add'], 'post', ['enctype' => 'multipart/form-data']) ?>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <?= Html::label('Режим добавления') ?>
                    <?= Html::dropDownList('mode', 0, \backend\models\Keyword::MODS, ['class' => 'form-control']) ?>
                </div>
                <div class="form-group">
                    <?= Html::label('Список ключевых фраз') ?>
                    <?= Html::textarea('keywords', '', ['placeholder' => 'Фразы списком. Каждая с новой строки', 'class' => 'form-control', 'rows' => 15]) ?>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <?= Html::label('Список доменов') ?>
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
    <div class="card-footer text-right">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?= Html::endForm() ?>
</div>

<?php ActiveForm::end(); ?>