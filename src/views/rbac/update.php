<?php

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */

$this->title = '更新权限：' . $model->description;

?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
