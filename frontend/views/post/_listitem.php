<?php
use yii\helpers\Html;
?>

<?php
    if (!empty($model->pic)) {
        echo '<div style="float: left;"><img src=" '. $model->pic .' "></div>';
    }
?>

<div class="post <?php if (!empty($model->pic)) { echo 'indexPost'; } else {echo 'indexPostB';} ?>">
    <div class="title">
        <strong><h3><a href="<?= $model->url;?>"><?= Html::encode($model->title);?></a></h3></strong>
        <div class="author">
            <span>浏览量：<?= $model->view . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?></span></em>
            <span class="glyphicon glyphicon-time" aria-hidden="true"></span><em><?= date('Y-m-d H:i:s',$model->create_time)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";?></em>
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span><em><?= Html::encode($model->author->nickname);?></em>
        </div>
    </div>
    <br>
    <div class="content">
        <?= $model->beginning;?>
    </div>
    <br>
    <div class="nav">
        <span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
        <?= implode(', ',$model->tagLinks);?>
        <br>
        <?= Html::a("评论 ({$model->commentCount})",$model->url.'#comments')?> | 最后修改于 <?= date('Y-m-s H:i:s',$model->update_time);?>
    </div>
</div>
