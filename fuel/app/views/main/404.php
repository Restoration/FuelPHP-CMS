<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();
	$model_image = new Model_Image();
	$post = \Session::get_flash('post');
 ?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil "></i>New Post</div>
		</div>
		<div class="block-content collapse in">





		</div>
	</div>
	<!-- /block -->
</div>


<?php echo \Asset::js('jquery.cleditor.min.js'); ?>
<?php echo \Asset::css('jquery.cleditor.css'); ?>
<?php include APPPATH . 'views/parts/footer.php';?>
