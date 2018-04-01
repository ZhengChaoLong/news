<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 15:16
 */
namespace console\controllers;

use yii\console\Controller;
use Yii;

class PermissionController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //移除所有的权限
        $auth->removeAllPermissions();

        $all_permission = [
            ['name' => 'user/index', 'description' => '用户管理-列表'],
            ['name' => 'user/update', 'description' => '用户管理-更新'],
            ['name' => 'user/view', 'description' => '用户管理-查看'],
            ['name' => 'user/delete', 'description' => '用户管理-删除'],
            ['name' => 'user/create', 'description' => '用户管理-创建'],

            ['name' => 'auth/index', 'description' => '权限管理-查看管理员'],
            ['name' => 'auth/update', 'description' => '权限管理-更新'],
            ['name' => 'auth/privilege', 'description' => '权限管理-分配角色'],
            ['name' => 'auth/role', 'description' => '权限管理-角色列表'],
            ['name' => 'auth/add-role', 'description' => '权限管理-新增角色'],
            ['name' => 'auth/create', 'description' => '权限管理-新增管理员'],
            ['name' => 'auth/permission', 'description' => '权限管理-分配权限'],


            ['name' => 'comment/create', 'description' => '评论管理-新增'],
            ['name' => 'comment/index', 'description' => '评论管理-列表'],
            ['name' => 'comment/approve', 'description' => '评论管理-审核'],
            ['name' => 'comment/delete', 'description' => '评论管理-删除'],
            ['name' => 'comment/update', 'description' => '评论管理-更新'],

            ['name' => 'post/index', 'description' => '文章管理-列表'],
            ['name' => 'post/view', 'description' => '文章管理-查看'],
            ['name' => 'post/create', 'description' => '文章管理-创建'],
            ['name' => 'post/update', 'description' => '文章管理-更新'],
            ['name' => 'post/delete', 'description' => '文章管理-删除'],

        ];

        //为系统管理员分配所有权限
        $admin = $auth->getRole('admin');

        foreach ($all_permission as $items){
            $permission = $auth->createPermission($items['name']);
            $permission->description = $items['description'];
            $auth->add($permission);
            $auth->addChild($admin, $permission);
        }

    }

}