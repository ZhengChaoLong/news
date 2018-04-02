<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/4/2
 * Time: 14:01
 */

namespace backend\models;

use yii\base\Model;

class RuleForm extends Model
{
    public $description;
    public $name;

    public function rules()
    {
        return [
            [['description', 'name'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'description' => '权限规则描述',
            'name' => '控制器/方法',
        ];
    }

    public function save()
    {
        if($this->validate()){
            $auth = \Yii::$app->authManager;
            $permission = $auth->createPermission($this->name);
            $permission->description = $this->description;
            $auth->add($permission);
            return true;
        }
        return false;
    }

    public function update()
    {
        if($this->validate()){
            $auth = \Yii::$app->authManager;
            $permission = $auth->createPermission($this->name);
            $permission->description = $this->description;
            $auth->update($this->name, $permission);
            return true;
        }
        return false;
    }
}