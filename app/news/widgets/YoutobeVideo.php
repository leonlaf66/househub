<?php
namespace module\news\widgets;

class YoutobeVideo extends \yii\base\Widget
{
    public $container = '';
    public $videoId = '';
    public $width = '298px';

    public function run()
    {
        return $this->render('youtobe-video.phtml', [
            'videoId'=>$this->videoId,
            'width'=>$this->width
        ]);
    }
}