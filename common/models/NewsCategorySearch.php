<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/4/14
 * Time: 17:50
 */

namespace common\models;

use yii\data\ActiveDataProvider;

/**
 * Class NewsCategory
 * @property string $name
 * @property integer $update_time
 * @package common\models
 */
class NewsCategorySearch extends NewsCategory
{

    public function rules()
    {
        return [
            [['id', 'create_time', 'update_time'], 'integer'],
            [['name',], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['id', 'name']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = NewsCategory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>8
            ],
            'sort'=>[
                'defaultOrder'=>[
                    'id'=>SORT_DESC,
                ],
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}