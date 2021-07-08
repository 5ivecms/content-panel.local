<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Domain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="domain-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Форма</h3>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'domain')->textInput(['maxlength' => true, 'placeholder' => 'Домен']) ?>

                    <?= $form->field($model, 'token')->textInput(['maxlength' => true, 'placeholder' => 'Токен']) ?>

                    <?= $form->field($model, 'comment')->textarea(['rows' => 6, 'placeholder' => 'Комментарий']) ?>
                </div>
                <div class="card-footer">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
