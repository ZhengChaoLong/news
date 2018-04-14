<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form about `common\models\Post`.
 */
class PostSearch extends Post
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['authorName']);
	}
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_time', 'update_time', 'author_id'], 'integer'],          
            [['title', 'content', 'tags','authorName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['id', 'title', 'content', 'status', 'authorName', 'tags']
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
        $query = Post::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
        	    'pageSize'=>8
            ],
        	'sort'=>[
                'defaultOrder'=>[
                    'id'=>SORT_DESC,
                ],
                'attributes'=>[
                    'id' => [
                        'label' => '主键ID'
                    ],
                    'title' => [
                        'label' => '新闻标题'
                    ],
                    'view' => [
                        'label' => '浏览量'
                    ],
                    'create_time' => [
                        'label' => '创建时间'
                    ],
                ],
        	],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
        	'post.id' => $this->id,
            'status' => $this->status,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        $query->join('INNER JOIN','Adminuser','post.author_id = Adminuser.id');
        $query->andFilterWhere(['like','Adminuser.nickname',$this->authorName]);

        return $dataProvider;
    }
}

