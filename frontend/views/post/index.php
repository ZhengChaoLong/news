<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use frontend\components\TagsCloudWidget;
use frontend\components\RctReplyWidget;
use common\models\Post;
use yii\caching\DbDependency;
use yii\caching;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="container">
	<div class="row">
		<div class="col-md-9">
		<?= ListView::widget([
            'id'=>'postList',
            'dataProvider'=>$dataProvider,
            'itemView'=>'_listitem',//子视图,显示一篇文章的标题等内容.
            'layout'=>'{sorter} {items} {pager}',
            'pager'=>[
                'maxButtonCount'=>8,
                'nextPageLabel'=>Yii::t('app','下一页'),
                'prevPageLabel'=>Yii::t('app','上一页'),
		    ],
            'sorter' => [
                'options' => [
                    'class' => 'nav nav-tabs'
                ]
            ]
		])?>
		</div>
		<div class="col-md-3">
            <div class="tagcloudbox">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span> 标签云
                    </li>
                    <li class="list-group-item">
                        <?php
                        //片段缓存示例代码
                        /*
                        $dependency = new DbDependency(['sql'=>'select count(id) from post']);

                        if ($this->beginCache('cache',['duration'=>600],['dependency'=>$dependency]))
                        {
                            echo TagsCloudWidget::widget(['tags'=>$tags]);
                            $this->endCache();
                        }
                        */
                        ?>
                        <?= TagsCloudWidget::widget(['tags'=>$tags]);?>
                    </li>
                </ul>
            </div>
			
			<div class="commentbox">
				<ul class="list-group">
				  <li class="list-group-item">
				  <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 最新回复
				  </li>
				  <li class="list-group-item">
				  <?= RctReplyWidget::widget(['recentComments'=>$recentComments])?>
				  </li>
				</ul>			
			</div>
		</div>
	</div>
</div>
