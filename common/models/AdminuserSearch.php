<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdminuserSearch represents the model behind the search form about `common\models\Adminuser`.
 */
class AdminuserSearch extends Adminuser
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'nickname', 'password', 'email', 'profile', 'auth_key', 'password_hash', 'password_reset_token'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['username', 'nickname', 'email']
        ];
    }

    public function search($params)
    {
        $query = Adminuser::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'username',
                ]
            ]
        ]);


        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'nickname', $this->nickname])
                ->andFilterWhere(['like', 'email', $this->email]);
        }

        return $dataProvider;
    }
}
