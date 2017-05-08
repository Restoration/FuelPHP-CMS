<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-check"></i>Task</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
	        </div>
		</div>
	</div>
	<!-- /block -->
</div>
<?php echo \Asset::js('underscore-min.js');?>
<?php echo \Asset::js('backbone-min.js');?>
<?php include APPPATH . 'views/parts/footer.php';?>