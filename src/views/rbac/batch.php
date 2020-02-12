<?php

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */

$this->title = $type == '1' ? '批量创建角色' : '批量创建权限';
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['index','type' => '2']];
$this->params['breadcrumbs'][] = $this->title;
$name = $type == '1' ? '角色' : '路由';
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="auth-item-create">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'products')->widget(MultipleInput::className(), [
        'max' => 10,
        'cloneButton' => true,
        'columns' => [
            [
                'name'  => 'name',
                'title' => $name,
            ],
            [
                'name'  => 'description',
                'title' => '描述',
            ],
            [
                'name'  => 'rule_id',
                'title' => '规则名称',
            ],
            [
                'name'  => 'data',
                'title' => '规则数据',
            ]
        ]
    ])->label(false);
    ?>

    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
