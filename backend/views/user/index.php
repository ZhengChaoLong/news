<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('新增用户', ['create'], ['class' => 'btn btn-primary']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
             'email:email',
            [
                'attribute'=>'status',
                'value'=>'statusStr',
            ],
            [
                'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
        		
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}'
            ],
        ],
    ]); ?>
</div>
