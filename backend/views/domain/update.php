<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Domain */

$this->title = 'Редактировать: ' . $model->domain;
$this->params['breadcrumbs'][] = ['label' => 'Сайты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->domain, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="domain-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
