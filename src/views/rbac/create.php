<?php


/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */

$this->title = $model->type == '1' ? '创建角色' : '创建权限';
$this->params['breadcrumbs'][] = ['label' => '添加权限', 'url' => ['index','type' => '2']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
