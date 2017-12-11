<?php
namespace module\cms\components;

use WS;

class View extends \yii\web\View
{
    public $compressEnabled = false;
    public $keywords = '';
    public $description = '';
    
    public function endPage($ajaxMode = false)
    {
        WS::$app->page->end();

        $this->trigger(self::EVENT_END_PAGE);

        $content = ob_get_clean();

        $content = strtr($content, [
            self::PH_HEAD => $this->renderHeadHtml(),
            self::PH_BODY_BEGIN => $this->renderBodyBeginHtml(),
            self::PH_BODY_END => $this->renderBodyEndHtml($ajaxMode),
        ]);

        if ($this->compressEnabled) {
            echo $this->higridCompressHtml($content);
        } else {
            echo $content;
        }

        $this->clear();
    }

    function higridCompressHtml($html ) 
    { 
        $chunks = preg_split( '/(<pre.*?\/pre>)/ms', $html, -1, PREG_SPLIT_DELIM_CAPTURE ); 
        $html = '';//[higrid.net]修改压缩html : 清除换行符,清除制表符,去掉注释标记 
        foreach ( $chunks as $c ) { 
            if ( strpos( $c, '<pre' ) !== 0 ) { 
                //[higrid.net] remove new lines & tabs 
                $c = preg_replace( '/[\\n\\r\\t]+/', ' ', $c ); 
                // [higrid.net] remove extra whitespace 
                $c = preg_replace( '/\\s{2,}/', ' ', $c ); 
                // [higrid.net] remove inter-tag whitespace 
                $c = preg_replace( '/>\\s</', '><', $c ); 
                // [higrid.net] remove CSS & JS comments 
                $c = preg_replace( '/\\/\\*.*?\\*\\//i', '', $c ); 
            } 
            $html .= $c; 
        } 
        return $html; 
    } 
}