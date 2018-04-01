<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 10:26
 */

use yii\bootstrap\ActiveForm;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var \yii\web\view $this */
/** @var \common\models\AuthItem $role */
/** @var  $dataProvider */
/** @var \common\models\AuthItem  $permissions */
$this->title = '编辑'.$role->description.'的权限';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['/auth/role']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title?></h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin()?>
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
                    'class' => CheckboxColumn::className(),
                    'name' => 'permission',
                    'checkboxOptions' => function($model) use($permissions){
                        $options = [
                            'value' => $model->name
                        ];
                        if(isset($permissions[$model->name])){
                            $options['checked'] = true;
                        }
                        return $options;
                    }
                ]
            ]
        ])?>
        <?= Html::submitButton('保存', ['class' => 'btn btn-success'])?>
        <?php ActiveForm::end()?>
    </div>
</div>
