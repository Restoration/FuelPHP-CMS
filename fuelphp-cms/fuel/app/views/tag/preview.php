<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();
	$post = \Session::get_flash('post');
	$id = $_GET['id'];
	$result_message = \Session::get_flash('result_message');
	$error_message = \Session::get_flash('error_message');
	$errors = \Session::get_flash('errors');

	if($result_message != null){
		$result_string = 'success';
		$alert_message = $result_message;
	} else {
		$result_string = 'error';
		$alert_message = $error_message;
	}

?>
<?php if(!empty($result_message) || !empty($error_message)):?>
<div class="row-fluid">
    <div class="alert alert-<?php echo $model_utility->h($result_string);?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><?php echo $model_utility->h(ucfirst($result_string));?></h4><?php echo $model_utility->h($alert_message);?>
	    <?php
		if(!empty($errors)){
			echo '<p>';
	        foreach ($errors as $error){
	            echo $model_utility->h($error).'<br />';
	        }
	        echo '</p>';
	    }
	    ?>
    </div>
</div>
<?php endif;?>



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
				<?php echo \Form::hidden('edit[tag_id]',$model_main->h($id));?>
				<?php echo \Form::input('edit[tag_name]',$model_utility->h($result[0]['tag_name']), array('class' => 'form-control span8','placeholder'=>'Tag Name'));?>
				<?php echo \Form::input('edit[tag_slug]',$model_utility->h($result[0]['tag_slug']), array('class' => 'form-control span8','placeholder'=>'Slug Name'));?>
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