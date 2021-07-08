<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'domain_id')->textInput() ?>

    <?= $form->field($model, 'keywords_enabled')->textInput() ?>

    <?= $form->field($model, 'keywords_limit')->textInput() ?>

    <?= $form->field($model, 'tag_enabled')->textInput() ?>

    <?= $form->field($model, 'tag_limit')->textInput() ?>

    <?= $form->field($model, 'sitemap_enabled')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
