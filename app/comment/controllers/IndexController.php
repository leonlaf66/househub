<?php
namespace module\comment\controllers;

use models\Comment;
use models\CommentPage;

class IndexController extends \yii\web\Controller
{	
	public function actionList($path)
	{
		$commentPage = CommentPage::find()->where('url=:url', [':url'=>$path])->one();

		$comments = $commentPage->comments ?? [];

		return $this->renderPartial('list.phtml', array(
			'comments'=>$comments
		));
	}

	public function actionSubmit($path)
	{
		$commentPage = CommentPage::find()->where(['url'=>$path])->one();
		if(! $commentPage) {
			$commentPage = new CommentPage();
			$commentPage->url = $path;
			$commentPage->hash = md5($path);
			$commentPage->save();
		}

		$comment = new Comment();
		$comment->attributes = $_POST;
		$comment->user_id = \WS::$app->user->id;
		$comment->page_id = $commentPage->id;
		$comment->save();

		echo json_encode($comment->getErrors());
	}
}
