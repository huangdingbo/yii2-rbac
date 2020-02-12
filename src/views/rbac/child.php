<?php
/* @var $this yii\web\View */

use dsj\components\assets\LayuiAsset;

LayuiAsset::register($this);
/* @var $model backend\models\AuthItem */
$this->title = '分配权限:' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '添加角色', 'url' => ['index','type' => '1']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="layui-container" style="margin-top: 15px;">
<!--    <div class="layui-btn-group">-->
<!--        <button class="layui-btn all">获取全部数据</button>-->
<!--        <button class="layui-btn left">获取左边数据</button>-->
<!--        <button class="layui-btn right">获取右边数据</button>-->
<!--    </div>-->
    <blockquote class="layui-elem-quote">左边为未拥有的权限，右边为已经拥有的权限</blockquote>
    <div id="root"></div><br><br>
    <button type="button" class="layui-btn layui-btn-fluid submit">提交权限分配</button>
</div>

<?php
$submitUrl = \yii\helpers\Url::toRoute(['rbac/child','id'=>$model->id,'name' => $model->name]);
$successUrl = \yii\helpers\Url::toRoute(['rbac/index','type'=>'1']);
$webPath = Yii::getAlias('@web');
$js = <<<JS
layui.config({
    base:'$webPath/layui/src/layuiadmin/layui_exts/transfer/' //静态资源所在路径
}).use(['transfer'],function() {
     var transfer = layui.transfer,$ = layui.$;
    //未拥有的权限列表
    var data1 = {$notHavePermisson};
    //已经拥有的权限列表
    var data2 = {$havePermission};
    //表格列
    var cols = [{type: 'checkbox', fixed: 'left'},{field: 'name', title: '路由(权限)'},{field: 'description', title: '描述'}]
    //表格配置文件
    var tabConfig = {'page':true,'limits':[10,50,100],'height':400}

    var tb1 = transfer.render({
        elem: "#root", //指定元素
        cols: cols, //表格列  支持layui数据表格所有配置
        data: [data1,data2], //[左表数据,右表数据[非必填]]
        tabConfig: tabConfig //表格配置项 支持layui数据表格所有配置
    })
    
    //transfer.get(参数1:初始化返回值,参数2:获取数据[all,left,right,l,r],参数:指定数据字段)
    // $('.all').on('click',function () {
    //     var data = transfer.get(tb1,'all');
    //     layer.msg(JSON.stringify(data))
    // });
    // $('.left').on('click',function () {
    //     var data = transfer.get(tb1,'left','name');
    //     layer.msg(JSON.stringify(data))
    // });
    // $('.right').on('click',function () {
    //     var data = transfer.get(tb1,'right','name');
    //     layer.msg(JSON.stringify(data))
    // });
    $('.submit').on('click',function () {
        var data = transfer.get(tb1,'right','name');
             $.ajax({
                    type: "GET",
                    url: "$submitUrl",
                    data: {"data":data},
                    dataType: "json",
                    success: function(data){
                        if (data.code == 200) {
                             layer.msg(data.msg, {
                               offset: '15px'
                               ,icon: 1
                               ,time: 1000
                           }, function(){
                               location.href = "{$successUrl}";
                           });
                        } else {
                            refreshCode();
                            layer.msg(data.msg,{offset: '15px',icon: 2 ,time: 1000})
                        }
                    }
			    });
    });
})
JS;
$this->registerJs($js);
?>