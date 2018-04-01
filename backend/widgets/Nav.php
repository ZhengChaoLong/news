<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 15:56
 */

namespace backend\widgets;

use common\models\Comment;
use Yii;
use yii\helpers\Html;

class Nav extends \yii\bootstrap\Nav
{
    public static function widget($config = [])
    {
        $menuItems = [
            ['label' => '文章管理', 'url' => ['/post/index']],
            ['label' => '评论管理', 'url' => ['/comment/index']],
            '<li><span class="badge">'.Comment::getPengdingCommentCount().'</span></li>',
            ['label' => '用户管理', 'url' => ['/user/index']],
            [
                'label' => '权限管理',
                'items' => [
                    ['label' => '管理员列表', 'url' => ['/auth/index']],
                    ['label' => '角色列表', 'url' => ['/auth/role']],
                ]
            ],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    '注销 (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>';
        }
        self::checkAccess($menuItems);

        $config['items'] = $menuItems;
        return parent::widget($config);
    }

    public static function checkAccess(&$items){

        foreach ($items as $key => &$item){
            if(isset($item['items'])) {
                self::checkAccess($item['items']);//递归调用
                if (count($item['items']) == 0) {
                    unset($items[$key]);
                }
            }else if(isset($item['url'])){
                $url = substr($item['url'][0], 1);
                if(!Yii::$app->user->can($url)){
                    unset($items[$key]);
                }
            }
        }
    }
}