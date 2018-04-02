<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/4/2
 * Time: 14:08
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '新增权限规则';
$this->params['breadcrumbs'][] = ['label' => '权限规则列表', 'url' => ['/auth/role']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title?></h3>
    </div>
    <div class="box-body">
        <?php $form =  ActiveForm::begin();?>
        <?= $form->field($model, 'name')?>
        <?= $form->field($model, 'description')?>
        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success'])?>
        </div>
        <?php ActiveForm::end();?>
    </div>
</div>