<?php
namespace module\comment\widgets;
class Review extends \yii\base\Widget
{  
	public $path = 'base';
	public $onSubmitExecUrl = '';
    
    public function init()
    {  
    }

    public function run()
    {  
        $user = \Yii::$app->user->getIdentity();
        
        return $this->render('comment.phtml');  
    }

    public function getItemsUrl()
    {
        return \WS::$app->urlManager->createUrl(['/comment/index/list', 'path'=>$this->path]);
    }

    public function getSublimitUrl()
    {
        return \WS::$app->urlManager->createUrl(['/comment/index/submit', 'path'=>$this->path]);
    }
}