<?php
/**
 * Created by PhpStorm.
 * User: Zhengchaolong
 * Date: 2018/4/14
 * Time: 18:30
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NewsCategory */

$this->title = '新增分类';
$this->params['breadcrumbs'][] = ['label' => '分类管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title?></h3>
    </div>
    <div class="box-body">
        <?php $form =  ActiveForm::begin(['layout' => 'horizontal']);?>
        <?= $form->field($model, 'name')?>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <?= Html::submitButton('保存', ['class' => 'btn btn-success'])?>
            </div>
        </div>
        <?php ActiveForm::end();?>
    </div>
</div>
