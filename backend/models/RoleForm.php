<?php

/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 10:56
 */

namespace backend\models;

use yii\base\Model;

class RoleForm extends Model
{
    public $id;
    public $name;

    public function rules()
    {
        return [
            [['id', 'name'], 'required'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '角色主键',
            'name' => '角色名称',
        ];
    }

    public function save(){
        if($this->validate()){
            $auth = \Yii::$app->authManager;
            $role = $auth->createRole($this->id);
            $role->description = $this->name;
            $auth->add($role);
            return true;
        }
        return false;
    }
}