<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 21:25
 */

namespace backend\controllers;

use yii\web\Controller;

class CommonController extends Controller
{
    //禁止非权限的访问
    public function beforeAction($action)
    {
        $permission = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        if(!Yii::$app->user->can($permission)){
            throw new ForbiddenHttpException('你没有权限访问');
        }
        return parent::beforeAction($action);
    }
}