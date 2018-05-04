<?php
namespace common\models;
use yii\db\Query;

/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/5/3
 * Time: 17:14
 */

/**
 * Class PostCollection
 * @package common\models
 * @property string $uniquekey
 * @property string $title
 * @property string $date
 * @property string $category
 * @property string $author_name
 * @property string $url
 * @property string $status
 * @property string $thumbnail_pic_s
 */
class PostCollection extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uniquekey', 'title', 'date', 'category', 'author_name', 'url', 'thumbnail_pic_s'], 'required'],
            [['status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uniquekey' => '唯一标识',
            'title' => '标题',
            'date' => '时间',
            'category' => '分类',
            'author_name' => '来源',
            'url' => '新闻url',
            'thumbnail_pic_s' => '图片',
        ];
    }

    /**
     * 将url数据存进数据库
     * 要记得去重
     * @param $data array
     */
    public static function collectionData($data)
    {
        if (is_array($data)) {
            foreach ($data as $v) {
                if (empty(PostCollection::isCollection($v['url']))) {
                    $model = new PostCollection();
                    $model->uniquekey = $v['uniquekey'];
                    $model->title = $v['title'];
                    $model->category = $v['category'];
                    $model->date = $v['date'];
                    $model->author_name = $v['author_name'];
                    $model->url = $v['url'];
                    $model->thumbnail_pic_s = $v['thumbnail_pic_s'];
                    $model->save();
                }
            }
        }
    }

    /**
     * 判断当前url是否在数据库中存在
     * @param url
     * @return mixed
     */
    public static function isCollection($url)
    {
        return (new Query())
            ->select(['url', 'status'])
            ->from('post_collection')
            ->where('url=:url', [':url' => $url])
            ->all();
    }

    /**
     * 获取需要解析的数据
     * 每次拿取30条
     * @param $category
     * @return mixed
     */
    public function needJieXe($category)
    {
        if (!is_array($category)) {
            return [];
        }
        return static::find()->where('status=0')->limit(20)->all();
    }

    /**
     * 修改抓取过的url的状态
     * @param $url
     */
    public function changeStatus($url)
    {
        if (!empty($url)) {
            $model = static::find()->where('url=:url', [':url' => $url])->one();
            $model->status = 1;
            $model->save();
        }
    }

    /**
     *修改采集状态为采集失败
     * @param $url
     */
    public function changeFail($url)
    {
        if (!empty($url)) {
            $model = static::find()->where('url=:url', [':url' => $url])->one();
            $model->status = -1;
            $model->save();
        }
    }
}