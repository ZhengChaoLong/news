<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

class UserSearch extends User
{
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
        ];
    }
    public function scenarios()
    {

        return [
            self::SCENARIO_DEFAULT => ['username', 'email', 'status']
        ];
    }

    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['status' => $this->status])
                ->andFilterWhere(['like', 'email', $this->email]);
        }
        return $dataProvider;
    }
}
