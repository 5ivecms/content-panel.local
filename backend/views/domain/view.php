<?php

use kartik\detail\DetailView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Domain */

$this->title = $model->domain;
$this->params['breadcrumbs'][] = ['label' => 'Сайты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="domain-view">

    <p class="text-right">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'domain',
            'token',
            'comment:ntext',
        ],
    ]) ?>

</div>
