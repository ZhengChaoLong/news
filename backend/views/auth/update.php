<?php


/* @var $this yii\web\View */
/* @var $model common\models\Adminuser */

$this->title = '修改管理员: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '管理员', 'url' => ['index']];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="adminuser-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
