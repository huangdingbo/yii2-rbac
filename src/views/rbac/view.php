<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */
$this->title = '查看权限：' . $model->description;
?>
<div class="auth-item-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
            'rule_id',
            'data',
            [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return date('Y-m-d H:i:s',$model->created_at);
                    }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return date('Y-m-d H:i:s',$model->updated_at);
                }
            ],
        ],
    ]) ?>

</div>
