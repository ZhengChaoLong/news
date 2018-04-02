<?php

namespace backend\controllers;

use backend\models\RuleForm;
use backend\models\SignupForm;
use Yii;
use common\models\Adminuser;
use common\models\AdminuserSearch;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use backend\models\ResetpwdForm;
use common\models\AuthItem;
use common\models\AuthAssignment;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use backend\models\RoleForm;

/**
 * AuthController implements the CRUD actions for Adminuser model.
 */
class AuthController extends CommonController
{

    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new AdminuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', '新增管理员成功');
            return $this->redirect(['index']);
        }

        $auth = Yii::$app->authManager;
        $allRoles = [];
        foreach ($auth->getRoles() as &$role){
            $allRoles[$role->name] = $role->description;
        }

        return $this->render('create', [
                'model' => $model,
                'allRoles' => $allRoles,
            ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Adminuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionResetPwd($id)
    {
    	$model = new ResetpwdForm();
    
    	if ($model->load(Yii::$app->request->post())) {
    		
    		if($model->resetPassword($id))
    		{
    			return $this->redirect(['index']);
    		}
    	}
    	 
    	return $this->render('resetpwd', [
    			'model' => $model,
    	]);
    
    }
    
    public function actionPrivilege($id)
    {
    	//step1. 找出所有权限,提供给checkboxlist
    	$allPrivileges = AuthItem::find()->select(['name','description'])
    	->where(['type'=>1])->orderBy('description')->all();

    	$allPrivilegesArray = [];
    	/** @var \common\models\AuthItem $pri */
        foreach ($allPrivileges as $pri)
    	{
    		$allPrivilegesArray[$pri->name]=$pri->description;
    	}
    	//step2. 当前用户的权限
    	 
    	$AuthAssignments=AuthAssignment::find()->select(['item_name'])
    	->where(['user_id'=>$id])->orderBy('item_name')->all();
    	 
    	$AuthAssignmentsArray = array();
    	 /** @var \common\models\AuthAssignment $AuthAssignment */
        foreach ($AuthAssignments as $AuthAssignment)
    	{
    		array_push($AuthAssignmentsArray,$AuthAssignment->item_name);
    	}
    	 
    	//step3. 从表单提交的数据,来更新AuthAssignment表,从而用户的角色发生变化
    	if(isset($_POST['newPri']))
    	{
    		AuthAssignment::deleteAll('user_id=:id',[':id'=>$id]);
    		$newPri = $_POST['newPri'];
    		$length = count($newPri);
    
    		for($x=0;$x<$length;$x++)
    		{
                $aPri = new AuthAssignment();
                $aPri->item_name = $newPri[$x];
                $aPri->user_id = $id;
                $aPri->created_at = time();
                $aPri->save();
    		}

    		Yii::$app->session->setFlash('success', '设置成功');
    		return $this->redirect(['index']);
    	}
    	 
    	//step4. 渲染checkBoxList表单
    
        return $this->render('privilege',[
            'id'=>$id,
            'AuthAssignmentArray'=>$AuthAssignmentsArray,
            'allPrivilegesArray'=>$allPrivilegesArray
        ]);
    }

    public function actionRole()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $roles,
            'sort' => [
                'attributes' => [
                    'name',
                ],
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('role', ['dataProvider' => $dataProvider]);
    }

    public function actionAddRole()
    {
        $model = new RoleForm();
        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['/auth/role']);
        }
        return $this->render('add-role', ['model' => $model]);
    }

    public function actionPermission($id)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($id);
        $permissions = $auth->getPermissionsByRole($id);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $auth->getPermissions()
        ]);
        if(Yii::$app->request->isPost){
            $newAuth = Yii::$app->request->post('permission')? :[];
            foreach ($dataProvider->getModels() as $permission){
                if(in_array($permission->name, $newAuth)){
                    if(!$auth->hasChild($role, $permission)){
                        $auth->addChild($role, $permission);
                    }
                }else{
                    if($auth->hasChild($role, $permission)){
                        $auth->removeChild($role,$permission);
                    }
                }
            }
            //修改了,需要重新获取权限
            $permissions = $auth->getChildren($role->name);
            Yii::$app->session->setFlash('success', '保存成功');
        }
        return $this->render('permission', [
            'role' => $role,
            'permissions' => ArrayHelper::getColumn($permissions, 'name'),
            'dataProvider' => $dataProvider
        ]);
    }

    //访问规则 ，name = 控制器/方法， describe = 描述
    public function actionRule()
    {
        $auth = Yii::$app->authManager;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $auth->getPermissions()
        ]);
        return $this->render('rule', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionAddRule()
    {
        $model = new RuleForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '新增规则成功');
            return $this->redirect(['/auth/rule']);
        }
        return $this->render('add-rule', ['model' => $model]);
    }

    /**
     * @param $name
     * @return mixed
     * 更新规则信息
     */
    public function actionUpdateRule($name)
    {
        if (empty($name)) {
            throw new HttpInvalidParamException('参错缺失，请联系管理员');
        }
        $auth = Yii::$app->authManager;
        $info = $auth->getPermission($name);
        $model = new RuleForm();
        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            Yii::$app->session->setFlash('success', '修改规则成功');
            return $this->redirect(['/auth/rule']);
        }
        $model->name = $info->name;
        $model->description = $info->description;
        return $this->render('update-rule', ['model' => $model]);
    }
}
