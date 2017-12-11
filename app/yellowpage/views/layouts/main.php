<?php
    $this->registerJsFile('https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js');
    $this->registerCssFile('http://v3.bootcss.com/dist/css/bootstrap.min.css'); 
    
	$this->params['activeNavId'] = 'yellowpage';
?>

<?php $this->beginContent('@module/page/views/layouts/main2.phtml'); ?>
<?php echo $content;?>
<?php $this->endContent()?>