<?php
namespace module\cms\widgets;

class LiveChat extends\yii\base\Widget
{
    public function run()
    {
        echo <<<EOT
            <!-- Start of LiveChat (www.livechatinc.com) code -->
            <script type="text/javascript">
            window.__lc = window.__lc || {};
            window.__lc.license = 7739171;
            (function() {
              var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
              lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
            })();
            </script>
            <!-- End of LiveChat code -->
EOT;
    }
}