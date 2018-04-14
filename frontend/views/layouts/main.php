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
