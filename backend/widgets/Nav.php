<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 15:56
 */

namespace backend\widgets;

use dmstr\widgets\Menu;
use Yii;

class Nav extends Menu
{
    public static function widget($config = [])
    {
        $menuItems = [
            ['label' => '文章管理', 'url' => ['/post/index']],
            ['label' => '分类管理', 'url' => ['/category/index']],
            [
                'label' => '评论管理',
                'url' => ['/comment/index']
            ],

            ['label' => '用户管理', 'url' => ['/user/index']],
            [
                'label' => '权限管理',
                'items' => [
                    ['label' => '管理员列表', 'url' => ['/auth/index']],
                    ['label' => '角色列表', 'url' => ['/auth/role']],
                    ['label' => '权限规则列表', 'url' => ['/auth/rule']],
                ]
            ],
        ];
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