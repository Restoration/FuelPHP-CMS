<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil "></i>Markdown Editor</div>
		</div>
		<div class="block-content collapse in">
			<span id="markdown_editor" class="span12">
				<?php echo \Form::open(array('method'=>'post','action'=>'markdown/update'));?>
					<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
					<?php echo Form::hidden('edit[id]',$result['id']);?>
					<?php echo \Form::input('edit[title]',$result['title'],array('class' => 'form-control span8','placeholder'=>'File Name'));?>
				<div>
					<span class="span6">
						<?php echo \Form::textarea('edit[text]',$result['text'],array('class' => 'form-control', 'id' => 'editor'));?>
					</span>
					<span class="span6">
						<div id="result_wrap"><div id="result"></div>
						<?php echo Form::hidden('edit[content]','',array('id'=>'hidden_result'));?>
					</span>
				</div>
				<div class="right_link">
					<?php echo Form::submit('edit[save]','Download', array('class' => 'form-control btn btn-success'));?>
					<?php echo Form::submit('edit[save]','Save', array('class' => 'form-control btn btn-primary'));?>
					<?php echo Form::submit('edit[delete]','Delete', array('class' => 'form-control btn btn-danger'));?>
				</div>
				<?php echo \Form::close();?>
			</span>
		</div>
	</div>
	<!-- /block -->
</div>
<?php echo \Asset::js('marked.min.js');?>
<?php echo \Asset::js('markdown-editor.js');?>
<?php include APPPATH . 'views/parts/footer.php';?>