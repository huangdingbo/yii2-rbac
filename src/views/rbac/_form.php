<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
if ($model->type == '1'){
    $name_label = '角色';
}else{
    $name_label = '路由';
}
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        if ($model->name){
            echo $form->field($model, 'name')->textInput(['maxlength' => true,'readonly' => true])->label($name_label);
        }else{
            echo $form->field($model, 'name')->textInput(['maxlength' => true])->label($name_label);
        }
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'rule_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
