<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-calendar "></i>Calendar</div>
		</div>
		<div class="block-content collapse in">
			<span class="span12">
				<div id='calendar'></div>
			</span>
		</div>
	</div>
	<!-- /block -->
</div>
<?php echo \Asset::js('moment.min.js'); ?>
<?php echo \Asset::js('fullcalendar.min.js'); ?>
<?php echo \Asset::js('calendar.js'); ?>
<?php echo \Asset::css('fullcalendar.css'); ?>
<?php echo \Asset::css('fullcalendar.print.css'); ?>
<?php include APPPATH . 'views/parts/footer.php';?>
