<?php
namespace backend\controllers;

use common\models\Comment;
use common\models\Post;
use common\models\Tag;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\AdminLoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @param $num
     * @return array
     */
    public function getRecentMonth($num)
    {
        $result = array();
        for ($i = 0; $i < $num; $i++) {
            $month = date('Y-m', strtotime('-'. $i .' month'));
            array_push($result, $month);
        }
        return array_reverse($result);
    }

    public function actionIndex()
    {
        //获取最近6个月
        $time = $this->getRecentMonth(6);
        //获取最近十个月
        $currentTenMonth = $this->getRecentMonth(10);
        $postData = $this->getNumberByTime($time);
        $commentModel = new Comment();
        $commentNumber = $commentModel->getNumberByTime($currentTenMonth);
        //获取浏览量最高的前十篇文章 x轴文章id，y轴浏览量,相同浏览量则按时间排序取出前十条
        $viewPostData = (new Post())->getMostViewPost();
        //各标签文章所占比例 (标签，比例)
        $tagPostData = (new Tag())->getTagPostData();
        //var_dump($viewPostData);exit;
        return $this->render('index', [
            //图表一
            'time' => json_encode($time),
            'createCount' => json_encode($postData['createCount']),
            'updateCount' => json_encode($postData['updateCount']),
            //图表二
            'postId' => json_encode($viewPostData['postId']),
            'postIdView' => json_encode($viewPostData['postIdView']),
            //图表三
            'tagPostData' => json_encode($tagPostData),
            //图表四
            'tenMonth' => json_encode($currentTenMonth),
            'commentCount' => json_encode($commentNumber),

        ]);
    }

    /**
     * @param $time array
     * @return mixed
     */
    public function getNumberByTime($time) {
        if (!is_array($time)) {
            return null;
        }
        $createCount = array();
        $updateCount = array();
        $post = new Post();
        foreach ($time as $k => $v) {
            array_push($createCount, $post->getPostCountByCreateTime($v));
            array_push($updateCount, $post->getPostCountByUpdateTime($v));
        }
        $result = array();
        $result['createCount'] = $createCount;
        $result['updateCount'] = $updateCount;
        return $result;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new AdminLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();//登录成功，回到登录前的页面
        } else {
            return $this->render('login', [
                'model' => $model,
            ]); //登录失败，登录页展示错误，让用户填写正确的用户名和密码
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->destroy();
        return $this->goHome();
    }
}
