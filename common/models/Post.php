<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\Html;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 * @property integer $view
 * @property Comment[] $comments
 * @property Adminuser $author
 * @property Poststatus $status0
 */
class Post extends \yii\db\ActiveRecord
{
	private $_oldTags;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'author_id'], 'required'],
            [['content', 'tags'], 'string'],
            [['status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminuser::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Poststatus::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'tags' => '标签',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'author_id' => '作者',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    public function getActiveComments()
    {
    	return $this->hasMany(Comment::className(), ['post_id' => 'id'])
    	->where('status=:status',[':status'=>2])->orderBy('id DESC');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Adminuser::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Poststatus::className(), ['id' => 'status']);
    }
    
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
    		if($insert)
    		{
    			$this->create_time = time();
    			$this->update_time = time();
    		}
    		else 
    		{
    			$this->update_time = time();
    		}
    		
    		return true;
    			
    	}
    	else 
    	{
    		return false;
    	}
    } 
    
    public function afterFind()
    {
    	parent::afterFind();
    	$this->_oldTags = $this->tags;
    }
    
    public function afterSave($insert, $changedAttributes)
    {
    	parent::afterSave($insert, $changedAttributes);
    	Tag::updateFrequency($this->_oldTags, $this->tags);
    }
    
    public function afterDelete()
    {
    	parent::afterDelete();
    	Tag::updateFrequency($this->tags, '');
    }
    
    public function getUrl()
    {
    	return Yii::$app->urlManager->createUrl(
    			['post/detail','id'=>$this->id,'title'=>$this->title]);
    }
    
    public function getBeginning($length=288)
    {
    	$tmpStr = strip_tags($this->content);
    	$tmpLen = mb_strlen($tmpStr);
    	 
    	$tmpStr = mb_substr($tmpStr,0,$length,'utf-8');
    	return $tmpStr.($tmpLen>$length?'...':'');
    }
    
    public function  getTagLinks()
    {
    	$links=array();
    	foreach(Tag::string2array($this->tags) as $tag)
    	{
    		$links[]=Html::a(Html::encode($tag),array('post/index','PostSearch[tags]'=>$tag));
    	}
    	return $links;
    }

    public function getCommentCount()
    {
    	return Comment::find()->where(['post_id'=>$this->id,'status'=>2])->count();
    }

    // $time = 'y-m' 根据文章创建时间每个月的数量
    public function getPostCountByCreateTime($time)
    {
        $startTime = strtotime($time.'-01');
        $time = str_replace('-', '', $time);
        $nexMonth = $this->getMonth($time, 0);
        $lastTime  = strtotime($nexMonth.'-01');

        return static::find()
            ->where('create_time>=:create_time AND create_time<:lastTime', [':create_time' => $startTime, ':lastTime' => $lastTime])
            ->count();
    }

    // $time = 'y-m' 根据文章时间每个月的数量
    public function getPostCountByUpdateTime($time)
    {
        $startTime = strtotime($time.'-01');
        $time = str_replace('-', '', $time);
        $nexMonth = $this->getMonth($time, 0);
        $lastTime  = strtotime($nexMonth.'-01');

        return static::find()
            ->where('update_time' != 'create_time')
            ->andWhere('update_time>=:start AND update_time<:last', [':start' => $startTime, ':last' => $lastTime])
            ->count();
    }

    //$time = Ym
    public function getMonth($time, $sign = "1")
    {
        //得到系统的年月
        $tmp_date = $time;
        //切割出年份
        $tmp_year = substr($tmp_date,0,4);
        //切割出月份
        $tmp_mon = substr($tmp_date,4,2);
        $tmp_nextmonth = mktime(0,0,0,$tmp_mon+1,1,$tmp_year);
        $tmp_forwardmonth = mktime(0,0,0,$tmp_mon-1,1,$tmp_year);
        if($sign == 0){
            //得到当前月的下一个月
            return $fm_next_month = date("Y-m",$tmp_nextmonth);
        }else{
            //得到当前月的上一个月
            return $fm_forward_month = date("Y-m",$tmp_forwardmonth);
        }
    }

    //获取浏览量最高的前十篇文章 postid => view
    public function getMostViewPost()
    {
        $result = array();
        $viewPost =  (new Query())->select(['id', 'view', 'create_time'])
            ->from('post')
            ->orderBy(['view' => SORT_DESC, 'create_time' => SORT_DESC])
            ->limit(10)
            ->all();
        if (empty($viewPost)) {
            return $result;
        }
        $postIdArray = array();
        $postIdView  = array();
        foreach ($viewPost as $v) {
            array_push($postIdArray, $v['id']);
            array_push($postIdView, $v['view']);
        }
        $result['postId'] = $postIdArray;
        $result['postIdView'] = $postIdView;
        return $result;
    }

    //获取带有该标签的文章数量
    public static function getTagPostNumber($tag)
    {
        return static::find()->andFilterWhere(['like', 'tags', $tag])->count();
    }
}
