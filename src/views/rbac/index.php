<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if ($searchModel->type == 1){
    $this->title = '角色管理';
    $button_name = '创建角色';
    $name = '角色';
}else{
    $this->title = '权限管理';
    $button_name = '创建权限';
    $name = '路由';
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <p>
        <?= Html::a($button_name, ['create','type' => Yii::$app->request->get('type')], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量创建', ['batch','type' => Yii::$app->request->get('type')], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>"首页",
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [

            [
                'attribute' => 'name',
                'label' => $name,
            ],
            'description:ntext',
            'rule_id',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->updated_at);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{child}{delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class = "glyphicon glyphicon-saved"></span>&nbsp;&nbsp;', $url, [
                            'title' => Yii::t('yii','修改'),
                            'aria-label' => Yii::t('yii','修改'),
                            'data-toggle' => 'modal',
                            'data-target' => '#update-modal',
                            'class' => 'data-update',
                            'data-id' => $key,
                        ],['color'=>'red']);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class = "glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;', $url, [
                            'title' => Yii::t('yii','查看'),
                            'aria-label' => Yii::t('yii','查看'),
                            'data-toggle' => 'modal',
                            'data-target' => '#view-modal',
                            'class' => 'data-view',
                            'data-id' => $key,
                        ]);
                    },
                    'child' => function ($url, $model, $key) {
                        if ($model->type == '1'){
                            $url .= "&name={$model->name}";
                            return Html::a('<span class = "glyphicon glyphicon-share"></span>&nbsp;&nbsp;', $url, [
                                'title' => Yii::t('yii','分配权限'),
                                'aria-label' => Yii::t('yii','分配权限'),
                            ]);
                        }else{
                            return false;
                        }

                    },
                    'delete' => function($url,$model,$key)use ($type){
                        $options = [
                            'title' => Yii::t('yii','删除'),
                            'aria-label' => Yii::t('yii','删除'),
                            'data-confirm' => Yii::t('yii','你确定要删除吗？'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        $url .= "&type={$type}";
                        return Html::a('<span class = "glyphicon glyphicon-trash"></span>',$url,$options);
                    }
                ],
            ],
        ],
    ]); ?>

    <?php
    // 更新操作
    Modal::begin([
        'id' => 'update-modal',
        'header' => '<h4 class="modal-title" style="color: #0d6aad">修改</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
        'size' => 'modal-lg',
    ]);
    Modal::end();
    $requestUpdateUrl = Url::toRoute(['update','type' => $type]);
    $updateJs = <<<JS
    $('.data-update').on('click', function () {
        $.get('{$requestUpdateUrl}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
    $this->registerJs($updateJs);
    ?>

    <?php
    // 查看操作
    Modal::begin([
        'id' => 'view-modal',
        'header' => '<h4 class="modal-title" style="color: #0d6aad">查看</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
        'size' => 'modal-lg',
    ]);
    Modal::end();
    $requestViewUrl = Url::toRoute('view');
    $viewJs = <<<JS
    $('.data-view').on('click', function () {
        $.get('{$requestViewUrl}', { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
    $this->registerJs($viewJs);
    ?>

</div>
