<?php
namespace frontend\components;

use yii\base\Widget;
use yii\helpers\Html;

class RctReplyWidget extends Widget
{
	public $recentComments;
	
	public function init()
	{
		parent::init();
	}
	
	public function run()
	{
		$commentString='';

		foreach ($this->recentComments as $comment)
		{
		    $userName = '';
		    if (!empty($comment->user)) {
		        $userName = $comment->user->username;
            }

            $commentPostTitle = '';
		    if (!empty($comment->post)) {
		        $commentPostTitle = $comment->post->title;
            }
			$commentString.='<div class="post">'.
					'<div class="title">'.
					'<p style="color:#777777;font-style:italic;">'.
					nl2br($comment->content).'</p>'.
					'<p class="text"> <span class="glyphicon glyphicon-user" aria-hidden="ture">
							</span> '.Html::encode($userName).'</p>'.
					
					'<p style="font-size:8pt;color:bule">
							《<a href="'.$comment->url.'">'.Html::encode($commentPostTitle).'</a>》</p>'.
					'<hr></div></div>';
		}
		return  $commentString;
	}
}