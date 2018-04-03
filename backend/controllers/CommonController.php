<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 21:25
 */

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;

class CommonController extends Controller
{
    //禁止非权限的访问
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            // 没有登录,登录,登录后,返回
            Yii::$app->user->setReturnUrl(Yii::$app->request->getUrl());  // 设置返回的url,登录后原路返回
            Yii::$app->user->loginRequired();
            Yii::$app->end();
        }
        $permission = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        if(!Yii::$app->user->can($permission)){
            throw new ForbiddenHttpException('你没有权限访问');
        }
        return parent::beforeAction($action);
    }
}