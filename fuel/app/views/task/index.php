<?php include APPPATH . 'views/parts/header.php';?>
<?php echo \Asset::js('underscore-min.js');?>
<?php echo \Asset::js('backbone-min.js');?>
<?php echo \Asset::js('localstorage.js');?>
<?php echo \Asset::js('_task.js');?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-check"></i>Task</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">



				<form id="addTask">
				    <input type="text" id="title">
				    <input type="submit" value="タスクを追加">
				</form><span id="error"></span>


				<div id="tasks"></div>


				<script type="text/template" id="task-template">
				<%- title %><span class="delete">[Delete]</span>
				</script>

	        </div>
		</div>
	</div>
	<!-- /block -->
</div>
<?php include APPPATH . 'views/parts/footer.php';?>