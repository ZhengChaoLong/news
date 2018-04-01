<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 10:09
 */
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;

$this->title = '角色列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
    <div class="box-header with-border">
        <?= Html::a('新增', ['/auth/add-role'], ['class' => 'btn btn-success'])?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'firstPageLabel' => true,
                'lastPageLabel' => true,
            ],
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => '角色主键',
                    'content' => function($model){
                        return Html::a($model->name, ['/auth']);
                    }
                ],
                'description:text:角色名称',
                [
                    'class' => ActionColumn::className(),
                    'header' => '操作',
                    'template' => '{permission}',
                    'buttons' => [
                        'permission' => function($url){
                            return Html::a('权限', $url, ['class' => 'btn btn-sm btn-primary']);
                        }
                    ]
                ]
            ]
        ])?>
    </div>
</div>