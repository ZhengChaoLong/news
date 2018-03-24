<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiichina\select2\Select2;
use yiichina\icheck\ICheck;
use yiichina\icons\Icon;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'roles')->widget(Select2::className(), ['items' => $model->roleItems, 'bootstrapTheme' => true, 'multiple' => true, 'clientOptions' => ['width' => '100%']]) ?>

    <?= $form->field($model, 'group')->widget(Select2::className(), ['items' => $model->groupItems, 'bootstrapTheme' => true, 'clientOptions' => ['width' => '100%']]) ?>

    <?= $form->field($model, 'status')->widget(ICheck::className(), ['type' => ICheck::TYPE_RADIO_LIST, 'items' => $model->statusList]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ?
            Icon::show('plus') . Yii::t('app', '新增') :
            Icon::show(
                    'edit') . Yii::t('app', '更新'), [
                    'class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
