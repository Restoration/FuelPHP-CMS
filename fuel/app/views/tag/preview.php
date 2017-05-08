<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil "></i>New tag addition</div>
		</div>
		<div class="block-content collapse in">
			<span class="span12">
			<?php echo \Form::open('tag/edit');?>
				<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
				<?php echo \Form::hidden('edit[tag_id]',$utility->h($id));?>
				<?php echo \Form::input('edit[tag_name]',$utility->h($result[0]['tag_name']), array('class' => 'form-control span8','placeholder'=>'Tag Name'));?>
				<?php echo \Form::input('edit[tag_slug]',$utility->h($result[0]['tag_slug']), array('class' => 'form-control span8','placeholder'=>'Slug Name'));?>
				<?php echo \Form::textarea('edit[tag_description]',$result[0]['tag_description'], array('class' => 'form-control span8','placeholder'=>'Tag Description'));?>
				<div class="right_link">
			  		<?php echo \Form::submit('edit[save]','Update', array('class' => 'form-control btn btn-primary'));?>
              		<?php echo \Form::submit('delete','Delete', array('class' => 'form-control btn btn-danger'));?>
				</div>
			<?php echo \Form::close();?>
			</span>
		</div>
	</div>
	<!-- /block -->
</div>

<?php include APPPATH . 'views/parts/footer.php';?>