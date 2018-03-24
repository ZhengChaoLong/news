<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/3/25
 * Time: 0:36
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = '查看' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('.box-body img{max-width:100%}');
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $model->username?></h3>
        <div class="pull-right">
            <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary'])?>
        </div>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'id',
                    'captionOptions' => [
                        'style' => 'width:130px;display:block;'
                    ]
                ],
                'username',
                'email',
                'created_at:datetime',
                [
                    'attribute' => 'updated_at',
                    'label' => '更新时间',
                    'format' => 'datetime',
                ],
            ],
        ])?>
    </div>
</div>
