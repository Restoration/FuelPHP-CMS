<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();
	$post = \Session::get_flash('post');
?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil "></i>Category new addition</div>
		</div>
		<div class="block-content collapse in">
			<span class="span12">
			<?php echo \Form::open('category/add');?>
				<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
				<?php echo \Form::input('add[category_name]',$model_utility->h($post['category_name']), array('class' => 'form-control span8','placeholder'=>'Category Name'));?>
				<?php echo \Form::input('add[category_slug]',$model_utility->h($post['category_slug']), array('class' => 'form-control span8','placeholder'=>'Slug Name'));?>
				<?php
					/*
					echo \Form::select('add[category_parent_id]',$model_utility->h($post['category_parent_id']),
					$r_category_result,
					array('type'=>'select','class' => 'form-control span8','placeholder'=>'親'));
					*/
					echo '<select type="select" class="form-control span8" name="add[category_parent_id]" id="form_add[category_parent_id]">';
					foreach($r_category_result as $key => $val){
						foreach($r_category_result[$key] as $k => $v){
							if($k == 0){
								$k = $key;
							}
							echo '<option value="'.$model_utility->h($k).'">';
							echo $model_utility->h($v['category_name']);
							echo '</option>';
						}
					}
					echo '</select>';
				?>
				<?php echo \Form::textarea('add[category_description]',$post['category_description'], array('class' => 'form-control span8','placeholder'=>'Category description'));?>
				<div class="right_link"><?php echo Form::submit('add[save]','Send', array('class' => 'form-control btn btn-primary'));?></div>
			<?php echo \Form::close();?>
			</span>
		</div>
	</div>
	<!-- /block -->
</div>




<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-search"></i>Category Search</div>
		</div>
		<div class="block-content collapse in">
			<div class="span12">
				<?php echo \Form::input('category_search','', array('class' => 'form-control span8','placeholder'=>'Search Word'));?>
			</div>
		</div>
	</div>
	<!-- /block -->
</div>



<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-category"></i>Category List</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
		       <?php if(empty($result)) :?>
			       <p>There are no categories to display.</p>
		       <?php else : ?>
		          <table class="table table-hover" id="table_category_list">
		            <thead>
		              <tr class="post_row">
		                <th>Name</th>
		                <th>Description</th>
		                <th>Slug</th>
		                <th></th>
		              </tr>
		            </thead>
		            <tbody>
					<?php
					foreach($r_category_result as $key => $val){
						foreach($r_category_result[$key] as $k => $v){
							if($key == -1){
								continue;
							}
							if($k == 0){
								$k = $key;
							}
							echo '<tr>';
							echo '<td>'.$v['category_name'].'</td>';
							echo '<td>'.$model_utility->h($v['category_description']).'</td>';
							echo '<td>'.$model_utility->h($v['category_slug']).'</td>';
							echo '<td><a href="'.\Uri::base().'category/preview?id='.$model_utility->h($k).'" class="btn btn-primary">Edit</a></td>';
							echo '</tr>';
						}
					}
					?>
		            </tbody>
		          </table>
		      <?php endif;?>
	        </div>
		</div>
	</div>
	<!-- /block -->
</div>

<div id="pagination_wrap">
	<?php echo \Pagination::instance('pagination');?>
</div>
<?php include APPPATH . 'views/parts/footer.php';?>
