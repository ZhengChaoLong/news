<?php
use yii\bootstrap\ActiveForm;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var \yii\web\view $this */
/** @var \common\models\AuthItem $role */
/** @var  $dataProvider */
/** @var \common\models\AuthItem  $permissions */
$this->title = '查看权限规则列表';
$this->params['breadcrumbs'][] = ['label' => '权限规则列表', 'url' => ['/auth/role']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
    <div class="box-header with-border">
        <?= Html::a('新增权限规则', ['add-rule'], ['class' => 'btn btn-primary']); ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'firstPageLabel' => true,
                'lastPageLabel' => true,
            ],
            'columns' => [
                'name:text:名称',
                'description:text:描述',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function ($url, $dataProvider) {
                            return Html::a('编辑', ['update-rule', 'name' => $dataProvider->name], ['class' => 'btn btn-sm btn-primary']);
                        },
                    ]
                ],
            ]
        ])?>
    </div>
</div>
