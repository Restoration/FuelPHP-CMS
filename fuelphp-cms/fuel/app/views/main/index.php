<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();
	$model_image = new Model_Image();
	$post = \Session::get_flash('post');

	$result_message = Session::get_flash('result_message');
	$error_message = Session::get_flash('error_message');
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
			<div class="muted pull-left"><i class="icon-pencil "></i>New Post</div>
		</div>
		<div class="block-content collapse in">
			<?php echo \Form::open('main/add');?>
			<span class="span9">
				<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
				<?php echo \Form::input('add[post_title]',$model_utility->h($post['post_title']), array('class' => 'form-control span8','placeholder'=>'タイトル'));?>
				<?php echo \Form::input('add[registerdate]',$model_utility->h($post['registerdate']), array('class' => 'form-control span8 datepicker','placeholder'=>'日付','readonly'=>'readonly'));?>
				<?php echo \Form::textarea('add[post_message]',$post['post_message'], array('id'=>'clEditor','class' => 'form-control'));?>
			</span>
			<span class="span3">
				<h5 id="category_wrap_title">Category List<i class="icon-chevron-up"></i></h5>
				<div id="category_wrap">
					<ul>
					<?php for($i=0; $i < count($category_result); $i++){
						echo '<li>';
						$checked = false;
						if(!empty($post['category'])){
							for($j=0; $j < count($post['category']); $j++){
								if($category_result[$i]['category_id'] == $post['category'][$j]){
									$checked = true;
								}
							}
						}
						echo \Form::input('add[category][]',$category_result[$i]['category_id'], array('type'=>'checkbox','class' => 'form-control','checked'=>$checked,'id'=>'form_category-'.$category_result[$i]['category_slug']));
						echo \Form::label($category_result[$i]['category_name'],'category-'.$category_result[$i]['category_slug']);
						echo '</li>';
					}
					?>
					</ul>
				</div>
			</span>
			<span class="span3">
				<h5 id="tag_wrap_title">Tag List<i class="icon-chevron-up"></i></h5>
				<div id="tag_wrap">
					<ul>
					<?php for($i=0; $i < count($tag_result); $i++){
						echo '<li>';
						$checked = false;
						if(!empty($post['tag'])){
							for($j=0; $j < count($post['tag']); $j++){
								if($tag_result[$i]['tag_id'] == $post['tag'][$j]){
									$checked = true;
								}
							}
						}
						echo \Form::input('add[tag][]',$tag_result[$i]['tag_id'], array('type'=>'checkbox','class' => 'form-control','checked'=>$checked,'id'=>'form_tag-'.$tag_result[$i]['tag_slug']));
						echo \Form::label($tag_result[$i]['tag_name'],'tag-'.$tag_result[$i]['tag_slug']);
						echo '</li>';
					}
					?>
					</ul>
				</div>
			</span>
			<div class="right_link"><?php echo Form::submit('add[save]','Send', array('class' => 'form-control btn btn-primary'));?></div>
			<?php echo \Form::close();?>
		</div>
	</div>
	<!-- /block -->
</div>
<div class="modalContent imageWindow">
	<div id="imageWindowInner"></div>
	<div id="iframeFooter">
		<div class="pagenateArea"></div>
		<div class="btn_area">
			<button id="insertImageBtn" class="btn btn-primary">Insert</button>
			<button class="closeBtn btn btn-danger">Close</button>
		</div>
	</div>
</div><!-- /.mordalContent  -->

<?php echo \Asset::js('jquery.cleditor.min.js'); ?>
<?php echo \Asset::css('jquery.cleditor.css'); ?>
<?php include APPPATH . 'views/parts/footer.php';?>
