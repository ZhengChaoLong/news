<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;

/**
 * @var yii\web\View $this
 */
$this->title = '绑定邮箱 - 帐户设置 - ' . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => '帐户设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = '绑定邮箱';
?>

<div class="page-header">
    <h1>帐户设置</h1>
    <?= Nav::widget([
        'items' => [
            ['label' => '个人信息', 'url' => ['site/index']],
            ['label' => '修改头像', 'url' => ['site/avatar']],
            ['label' => '修改密码', 'url' => ['site/password']],
            ['label' => '绑定邮箱', 'url' => ['site/email']],
            ['label' => '第三方登录', 'url' => ['site/third']],
        ],
        'options' => ['class' => 'nav nav-tabs nav-main'],
    ]); ?>
</div>

<div class="post-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'email')->hint('此邮箱不会被公开')->textInput() ?>

    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-6"><?= Html::submitButton('修改绑定邮箱',['class' => 'btn btn-primary'])?></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>