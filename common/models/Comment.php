<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property integer $userid
 * @property string $email
 * @property string $url
 * @property integer $post_id
 *
 * @property Post $post
 * @property Commentstatus $status0
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'status', 'userid', 'email', 'post_id'], 'required'],
            [['content'], 'string'],
            [['status', 'create_time', 'userid', 'post_id'], 'integer'],
            [['email', 'url'], 'string', 'max' => 128],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Commentstatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'status' => '状态',
            'create_time' => '发布时间',
            'userid' => '用户',
            'email' => 'Email',
            'url' => 'Url',
            'post_id' => '文章',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Commentstatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userid']);
    }
    
    public function getBeginning()
    {
    	$tmpStr = strip_tags($this->content);
    	$tmpLen = mb_strlen($tmpStr);
    	
    	return mb_substr($tmpStr,0,10,'utf-8').(($tmpLen>10)?'...':'');
    }
    
    public function approve()
    {
    	$this->status = 2; //设置评论状态为已审核
    	return ($this->save()?true:false);
    }
    
    public static function getPengdingCommentCount()
    {
    	return Comment::find()->where(['status'=>1])->count();
    }
    
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
    		if($insert)
    		{
    			$this->create_time=time();
    		}
    		return true;
    	}
    	else  return false;
    }
    
    public static function findRecentComments($limit=10)
    {
    	return Comment::find()->where(['status'=>2])->orderBy('create_time DESC')
    	->limit($limit)->all();
    }

    /**
     * 根据评论的创建时间获取评论所在月的数量
     * @param $time  array
     * @return mixed
     */
    public function getNumberByTime($time)
    {
        if (!is_array($time)) {
            return null;
        }
        $createCount = array();
        foreach ($time as $k => $v) {
            array_push($createCount, $this->getCommentCountByCreateTime($v));
        }
        return $createCount;
    }

    /**
     * @param $time y-m
     * @return int|string
     */
    public function getCommentCountByCreateTime($time)
    {
        $startTime = strtotime($time.'-01');
        $time = str_replace('-', '', $time);
        $nexMonth = $this->getMonth($time, 0);
        $lastTime  = strtotime($nexMonth.'-01');

        return static::find()
            ->where('create_time>=:start AND create_time<:last', [':start' => $startTime, ':last' => $lastTime])
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
}
