<?php

/* @var $this yii\web\View */
/* @var $model common\models\Domain */

$this->title = 'Добавить сайт';
$this->params['breadcrumbs'][] = ['label' => 'Сайты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="domain-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
