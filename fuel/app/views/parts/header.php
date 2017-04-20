<?php
	$model_utility = new Model_Utility();
?>
<!DOCTYPE html>
<html class="no-js">
<head>
<title>FuelPHP CMS</title>
<meta charset="UTF-8">
<?php echo Asset::css('bootstrap.min.css'); ?>
<?php echo Asset::css('bootstrap-responsive.min.css'); ?>
<?php echo Asset::css('style.css'); ?>
<?php echo Asset::css('styles.css'); ?>
<?php echo Asset::css('lightbox.css'); ?>
<?php echo Asset::css('jquery.tagit.css'); ?>
<?php echo Asset::css('jquery-ui.css'); ?>
<?php echo Asset::js('vendors/jquery-2.2.0.min.js'); ?>
<?php echo Asset::js('jquery-ui.min.js'); ?>
<?php echo Asset::js('lightbox.min.js'); ?>
<?php echo Asset::js('bootstrap-datepicker.js'); ?>
<?php echo Asset::js('bootstrap-datepicker.ja.js'); ?>
<?php echo Asset::js('tag-it.js'); ?>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body data-controller="<?php echo $model_utility->h(Request::main()->controller);?>">
<?php echo Form::input('base_url',$model_utility->h(Uri::base()),array('type'=>'hidden','id'=>'base_url'));?>
<div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                    </a>
                    <?php echo Html::anchor('main/index', 'DashBoards',array('class'=>'brand'));?>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span3" id="sidebar">
	                 <?php include APPPATH . 'views/parts/sidebar.php';?>
                </div>
                <!--/span-->
                <div class="span9" id="content">
                    <div class="row-fluid">
	                    <?php include APPPATH . 'views/parts/message.php';?>