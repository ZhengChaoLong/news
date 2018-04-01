<?php
namespace backend\models;

use common\models\Adminuser;
use yii\base\Model;
use yii\db\Exception;

class SignupForm extends Model
{
    public $username;
    public $role;
    public $nickname;
    public $email;
    public $password;
    public $password_repeat;
    public $profile;

    public function rules()
    {
        return [
            ['role', 'required'],
            [['username', 'email', 'password', 'nickname'], 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\Adminuser', 'message' => '用户名已经存在.'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\Adminuser', 'message' => '邮件地址已经存在.'],
        	['password_repeat','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致！'],

        ];
    }

    public function attributeLabels()
    {
    	return [
    			'username' => '用户名',
                'role' => '角色',
    			'nickname' => '昵称',
    			'password' => '密码',
    			'password_repeat'=>'重输密码',
    			'email' => 'Email',
    			'profile' => '简介',
    	];
    }

    public function signup()
    {
        if ($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $user = new Adminuser();
                $user->username = $this->username;
                $user->nickname = $this->nickname;
                $user->email = $this->email;
                $user->profile = $this->profile;
                $user->password = \Yii::$app->security->generatePasswordHash($this->password);
                $user->generateAuthKey();
                $user->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
                if(!$user->save()){
                    var_dump($user->getErrors());exit;
                }
                $auth = \Yii::$app->authManager;
                foreach ($this->role as $role){
                    $role_instance = $auth->getRole($role);
                    $auth->assign($role_instance, $user->id);
                }

                $transaction->commit();
                return true;
            }catch (Exception $e){
                $transaction->rollBack();
            }
        }
        return false;
    }

}
