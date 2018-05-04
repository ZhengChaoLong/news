<?php

namespace frontend\controllers;

use Yii;
use common\models\Post;
use common\models\PostSearch;
use common\models\PostCollection;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Tag;
use common\models\Comment;
use common\models\User;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
	public $added=0; //0代表还没有新回复
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        		
        		
            'access' =>[
                    'class' => AccessControl::className(),
                    'rules' =>
                    [
                            [
                                    'actions' => ['index', 'auto-collection', 'auto-jie-xi'],
                                    'allow' => true,
                                    'roles' => ['?'],
                                    ],
                                    [
                                            'actions' => ['index', 'detail', 'auto-collection', 'auto-jie-xi'],
                                            'allow' => true,
                                            'roles' => ['@'],

                                    ],
                            ],
                    ],
        		
//        	'pageCache'=>[
//        			'class'=>'yii\filters\PageCache',
//        			'only'=>['index'],
//        			'duration'=>600,
//        			'variations'=>[
//        					Yii::$app->request->get('page'),
//        					Yii::$app->request->get('PostSearch'),
//        			],
//        			'dependency'=>[
//        					'class'=>'yii\caching\DbDependency',
//        					'sql'=>'select count(id) from post',
//        			],
//        	],
        		
//        	'httpCache'=>[
//        			'class'=>'yii\filters\HttpCache',
//        			'only'=>['detail'],
//        			'lastModified'=>function ($action,$params){
//        				$q = new Query();
//        				return $q->from('post')->max('update_time');
//        			},
//        			'etagSeed'=>function ($action,$params) {
//        				$post = $this->findModel(Yii::$app->request->get('id'));
//        				return serialize([$post->title,$post->content]);
//        			},
//        			'cacheControlHeader' => 'public,max-age=600',
//        	],
        ];
    }


    public function actionIndex()
    {
    	$tags = Tag::findTagWeights();
    	$recentComments = Comment::findRecentComments();
    	
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'tags'=>$tags,
        	'recentComments'=>$recentComments,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function addViewAndFindPost($id)
    {
        $model = $this->findModel($id);
        $model->view = $model->view + 1;
        if ($model->save()) {
            return $model;
        } else {
            throw new Exception('浏览量自增失败');
        }
    }
    
    public function actionDetail($id)
    {
    	//step1. 准备数据模型
        $model = $this->addViewAndFindPost($id);
    	$tags=Tag::findTagWeights();
    	$recentComments=Comment::findRecentComments();
    	
    	$userMe = User::findOne(Yii::$app->user->id);
    	$commentModel = new Comment();
    	$commentModel->email = $userMe->email;
    	$commentModel->userid = $userMe->id;
    	
    	//step2. 当评论提交时，处理评论
    	if($commentModel->load(Yii::$app->request->post()))
    	{
    		$commentModel->status = 1; //新评论默认状态为 pending
    		$commentModel->post_id = $id;
    		if($commentModel->save())
    		{
    			$this->added=1;
    		}
    	}
    	
    	//step3.传数据给视图渲染
    	
    	return $this->render('detail',[
    			'model'=>$model,
    			'tags'=>$tags,
    			'recentComments'=>$recentComments,
    			'commentModel'=>$commentModel, 
    			'added'=>$this->added, 			
    	]);
    }

    public static function getWeather($cityName = '广州')
    {
        //设置天气预报的redisKey为weatherData
        $redis = new \Redis();
        $redis->connect(Yii::$app->params['redisHost']);
        if ($data = $redis->get(Yii::$app->params['weatherDataKey'])) {
            $redis->close();
            return $data;
        }
        //调用聚合数据天气预报的开放接口
        $appKey = '7d14569c454bec1e4a7c90a17974e94e';
        $cityNameEncode = urlencode($cityName);
        $url = 'http://v.juhe.cn/weather/index?format=2&cityname='. $cityNameEncode .'&key='.$appKey;
        $data = json_decode(file_get_contents($url), true);
        $todayData = $data['result']['today'];
        $cacheData = '<a target="_blank" href="http://tianqi.qq.com/?province=%E5%B9%BF%E4%B8%9C%E7%9C%81&city=%E5%B9%BF%E5%B7%9E%E5%B8%82&district=">'.
            $cityName.'市 '.$todayData['temperature'].'/'.$todayData['weather'].'</a>';
        //将数据缓存进redis中
        $lastTime = strtotime(date('y-m-d')) + 3600*24;
        $redis->set(Yii::$app->params['weatherDataKey'], $cacheData, ($lastTime - time()));
        $redis->close();
        return $cacheData;
    }

    //这里的类型参照分类表
    public $allType = [
        '1' => 'shehui',
        '2' => 'keji',
        '3' => 'tiyu',
        '4' => 'top',
        '5' => 'caijing',
    ];

    //http://localhost:8888/post/auto-collection
    //定时任务，采集今天的新闻数据到数据库中，调用的是聚合数据的新闻接口,保存每个要抓取的新闻url
    public function actionAutoCollection()
    {
        ignore_user_abort(); //浏览器关闭，程序继续执行
        set_time_limit(0);  //忽略到默认30秒超时
        $appKey = '5dea4f0512274d5045165fb8bcfc7d9a';
        foreach ($this->allType as $v) {
            $url = 'http://v.juhe.cn/toutiao/index?type='. $v .'&key='.$appKey;
            //将数据保存到post_collection表中
            $data = json_decode(file_get_contents($url), true);
            PostCollection::collectionData($data["result"]["data"]);
        }
    }


    public $category = [
        '1' => '社会',
        '2' => '科技',
        '3' => '体育',
        '4' => '头条',
        '5' => '财经',
    ];
    /**
     * http://localhost:8888/post/auto-jie-xi
     * 到post_collection表中解析未处理过的新闻url
     */
    public function actionAutoJieXi()
    {
        ignore_user_abort(); //浏览器关闭，程序继续执行
        set_time_limit(0);  //忽略到默认30秒超时
        $needJieXi = (new PostCollection())->needJieXe($this->category);
        //var_dump($needJieXi);exit;
        if (!empty($needJieXi)) {
            (new Post())->collectionData($needJieXi, $this->category);
        }
    }
}
