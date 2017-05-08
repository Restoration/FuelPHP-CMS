<?php include APPPATH . 'views/parts/header.php';?>
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
				<?php echo \Form::input('add[post_title]',$utility->h($post['post_title']), array('class' => 'form-control span8','placeholder'=>'Title'));?>
				<?php echo \Form::input('add[registerdate]',$utility->h($post['registerdate']), array('class' => 'form-control span8 datepicker','placeholder'=>'Date','readonly'=>'readonly'));?>
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
