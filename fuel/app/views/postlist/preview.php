<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();
	$post = \Session::get_flash('post');
	$id = $_GET['id'];
?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil"></i>Edit Post Article</div>
		</div>
		<div class="block-content collapse in">
			<?php echo \Form::open('postlist/edit');?>
	        <div class="span9">
		    <?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
            <?php echo \Form::hidden('edit[post_id]',$model_main->h($id));?>
            <?php echo \Form::input('edit[post_title]',$model_utility->h($result[0]['post_title']), array('class' => 'form-control span8'));?>
            	<?php echo \Form::input('edit[registerdate]',$model_main->h(str_replace('-','/',substr($result[0]['registerdate'],0,10))), array('class' => 'form-control span8 datepicker','placeholder'=>'Date','readonly'=>'readonly'));?>
              <?php echo \Form::textarea('edit[post_message]',$result[0]['post_message'], array('id'=>'clEditor','class' => 'form-control'));?>
			</div>
			<span class="span3">
				<h5 id="category_wrap_title">Category List<i class="icon-chevron-up"></i></h5>
				<div id="category_wrap">
					<ul>
					<?php for($i=0; $i < count($category_result); $i++){
						echo '<li>';
						$category_array = explode(',',$result[0]['category_id']);
						$checked = false;
						if(!empty($category_array)){
							for($j=0; $j < count($category_array); $j++){
								if($category_result[$i]['category_id'] == $category_array[$j]){
									$checked = true;
								}
							}
						}
						echo \Form::input('edit[category][]',$category_result[$i]['category_id'], array('type'=>'checkbox','class' => 'form-control','checked'=>$checked,'id'=>'form_category-'.$category_result[$i]['category_slug']));
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
						$tag_array = explode(',',$result[0]['tag_id']);
						$checked = false;
						if(!empty($tag_array)){
							for($j=0; $j < count($tag_array); $j++){
								if($tag_result[$i]['tag_id'] == $tag_array[$j]){
									$checked = true;
								}
							}
						}
						echo \Form::input('edit[tag][]',$tag_result[$i]['tag_id'], array('type'=>'checkbox','class' => 'form-control','checked'=>$checked,'id'=>'form_tag-'.$tag_result[$i]['tag_slug']));
						echo \Form::label($tag_result[$i]['tag_name'],'tag-'.$tag_result[$i]['tag_slug']);
						echo '</li>';
					}
					?>
					</ul>
				</div>
			</span>
          	<div class="right_link">
		  		<?php echo \Form::submit('edit[save]','Update', array('class' => 'form-control btn btn-primary'));?>
          		<?php echo \Form::submit('delete','Delete', array('class' => 'form-control btn btn-danger'));?>
			</div>
			<?php echo \Form::close();?>
		</div>
	</div>
	<!-- /block -->
</div>


<div class="modalContent imageWindow">
	<div id="imageWindowInner"></div>
	<div id="iframeFooter">
		<div class="btn_area">
			<button id="insertImageBtn" class="btn btn-primary">Insert</button>
			<button class="closeBtn btn btn-danger">Close</button>
		</div>
	</div>
</div><!-- /.mordalContent  -->

<?php echo \Asset::js('jquery.cleditor.min.js'); ?>
<?php echo \Asset::css('jquery.cleditor.css'); ?>
<?php include APPPATH . 'views/parts/footer.php';?>