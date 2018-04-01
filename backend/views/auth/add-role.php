<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2017/6/11
 * Time: 11:16
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '新增角色';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['/auth/role']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title?></h3>
    </div>
    <div class="box-body">
        <?php $form =  ActiveForm::begin(['layout' => 'horizontal']);?>
        <?= $form->field($model, 'id')?>
        <?= $form->field($model, 'name')?>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <?= Html::submitButton('保存', ['class' => 'btn btn-success'])?>
            </div>
        </div>
        <?php ActiveForm::end();?>
    </div>
</div>