<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'domain_id') ?>

    <?= $form->field($model, 'keywords_enabled') ?>

    <?= $form->field($model, 'keywords_limit') ?>

    <?= $form->field($model, 'tag_enabled') ?>

    <?php // echo $form->field($model, 'tag_limit') ?>

    <?php // echo $form->field($model, 'sitemap_enabled') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
