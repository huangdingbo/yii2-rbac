<?php

namespace dsj\rbac\controllers;

use dsj\components\controllers\WebController;
use dsj\components\server\ToolsServer;
use dsj\rbac\models\RbacAssignment;
use dsj\rbac\models\RbacItem;
use dsj\rbac\models\RbacItemChild;
use dsj\rbac\models\RbacItemSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RbacController implements the CRUD actions for AuthItem model.
 */
class RbacController extends WebController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $type = $this->checkType();

        $searchModel = new RbacItemSearch();
        $searchModel->type = $type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     *
     */
    public function actionCreate()
    {
        $type = $this->checkType();

        $model = new RbacItem();
        $model->type = $type;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','操作成功!');
            return $this->redirect(['rbac/index','type' => $type]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionBatch(){
        $type = $this->checkType();
        $model = new RbacItem();
        $model->type = $type;
        if (Yii::$app->request->isPost){
            $postData = Yii::$app->request->post();
            $data = ArrayHelper::getValue($postData,'RbacItem.products');
            foreach ($data as $item){
                $model = new RbacItem();
                $model->type = $type;
                $model->name = $item['name'];
                $model->description = $item['description'];
                $model->rule_id = $item['rule_id'];
                $model->data = $item['data'];
                if (!$model->save()){
                    Yii::$app->session->setFlash('danger',Json::encode($model->getErrors()));
                    return $this->redirect(['rbac/batch','type' => $type]);
                }
            }
            Yii::$app->session->setFlash('success','批量创建成功');
            return $this->redirect(['rbac/index','type' => $type]);
        }
        return $this->render('batch', [
            'model' => $model,
            'type' => $type,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     *
     */
    public function actionUpdate($id)
    {
        $type = $this->checkType();
        $model = $this->findModel($id);
        $model->type = $type;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','操作成功!');
            return $this->redirect(['index','type' => $type]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     *
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $key = $model->type == 1 ? 'parent' : 'child';
        RbacItemChild::deleteAll([$key => $model->name]);
        if ($model->type == 1){
            RbacAssignment::deleteAll(['item_name' => $model->name]);
        }
        $model->delete();

        return $this->redirect(['index','type' => Yii::$app->request->get('type')]);
    }

    /**
     * @param $id
     * @return RbacItem|null
     * @throws NotFoundHttpException
     *
     */
    protected function findModel($id)
    {
        if (($model = RbacItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function checkType(){
        $getData = Yii::$app->request->get();
        if (!isset($getData['type'])){
            throw new ForbiddenHttpException('请求错误');
        }

        return $getData['type'];
    }

    /**
     * @param $id
     * @param $name
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * 权限分配
     */
    public function actionChild($id,$name){
        $model = $this->findModel($id);
        $allPermission = $this->getAllPermission();
        $havePermission = $this->getHavePerMissionByName($name);
        $notHavePermisson = ToolsServer::getDiffArrayByPk($allPermission,$havePermission,'name');
        if (Yii::$app->request->isAjax){
            $getData = Yii::$app->request->get();
            $role = $getData['name'];
            RbacItemChild::deleteAll(['parent' => $role]);
            $data = $getData['data'];
            $dataArr = explode(',',$data);
            foreach ($dataArr as $item){
                $childModel = new RbacItemChild();
                $childModel->parent = $role;
                $childModel->child = $item;
                if (!$childModel->save()){
                    return Json::encode(['code' => 100,'msg' => Json::encode($childModel->getErrors())]);
                }
            }
            return Json::encode(['code' => 200,'msg' => '权限分配成功']);
        }
        return $this->render('child',[
            'model' => $model,
            'havePermission' => json_encode($havePermission),
            'notHavePermisson' => json_encode($notHavePermisson),
        ]);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * 获取所有权限
     */
    private function getAllPermission(){
        return RbacItem::find()->select('name,description')->where(['type' => '2'])->asArray()->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * 获取用户已经拥有的权限
     */
    private function getHavePerMissionByName($name){
        return RbacItemChild::find()
            ->leftJoin('rbac_item','rbac_item_child.child = rbac_item.name')
            ->select('rbac_item_child.child as name,rbac_item.description')
            ->where(['rbac_item_child.parent' => $name,'rbac_item.type' => '2'])
            ->asArray()
            ->all();
    }


}
