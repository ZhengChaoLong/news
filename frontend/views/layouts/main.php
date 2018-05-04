<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '新闻资讯',
    	'brandOptions'=> ['style'=>'color:blue;font-size:23px'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-fixed-top',
        ],
    ]);

    $time = date('Y年m月d日 星期') . mb_substr("日一二三四五六",date("w"),1,"utf-8");
    $menuItemsLeft[] = '<li style="line-height: 50px"><span class="glyphicon glyphicon-time" aria-hidden="false">'. $time .'</li>';

    //获取天气预报信息
    $weatherInfo = \frontend\controllers\PostController::getWeather();
    $menuItemsLeft[] = '<li style="line-height: 50px">'. $weatherInfo .'</li>';

    //文章搜索框
    $searchForm = '<li>
					  <form class="form-inline" action="' .  Yii::$app->urlManager->createUrl(['post/index']) .'" id="w0" method="get">
						  查找文章('. \common\models\Post::find()->count() . ')
						  <div class="form-group">
						    <input type="text" class="form-control" name="PostSearch[title]" id="w0input" placeholder="按标题">
						  </div>
						  <button type="submit" class="btn btn-default">搜索</button>
					</form>
				  </li>';
    $menuItemsLeft[] = $searchForm;

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItemsLeft,
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '注册', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '退出 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>


    <div class="container">
        <?php
        NavBar::begin([
            'brandLabel' => '首页',
            'brandOptions'=> ['style'=>'color:blue;font-size:23px'],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => '',
            ],
        ]);
        $category = [];
        $catData = json_decode(send_request(Yii::$app->params['categoryApi'], ''), true);
        //var_dump($catData);exit;
        if ($catData['errCode'] == 1) {
            foreach ($catData['items'] as $v) {
                $category[] = ['label' => $v['name'], 'url' => '/post/index?catId=' . $v['id']];
            }
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $category,
        ]);
        NavBar::end();
        ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
