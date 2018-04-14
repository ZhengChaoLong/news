<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/4/14
 * Time: 17:42
 */
use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <p>
        <?= Html::a('新增分类', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute'=>'id',
                'contentOptions'=>['width'=>'30px'],
            ],
            'name',
            ['attribute'=>'create_time',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            ['attribute'=>'update_time',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]); ?>
</div>
