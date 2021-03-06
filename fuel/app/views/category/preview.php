<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil "></i>Category new addition</div>
		</div>
		<div class="block-content collapse in">
			<span class="span12">
			<?php echo \Form::open('category/edit');?>
				<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
				<?php echo \Form::hidden('edit[category_id]',$utility->h($id));?>
				<?php echo \Form::input('edit[category_name]',$utility->h($result[0]['category_name']), array('class' => 'form-control span8','placeholder'=>'Category Name'));?>
				<?php echo \Form::input('edit[category_slug]',$utility->h($result[0]['category_slug']), array('class' => 'form-control span8','placeholder'=>'Slug Name'));?>
				<?php
					echo '<select type="select" class="form-control span8" name="edit[category_parent_id]" id="form_add[category_parent_id]">';
					foreach($r_category_result as $key => $val){
						foreach($r_category_result[$key] as $k => $v){
							$selected = '';
							if($k == 0){
								$k = $key;
							}
							if($result[0]['category_parent_id'] == 0){
								$selected = '';
							}elseif($result[0]['category_parent_id'] == $k){
								$selected = 'selected';
							}
							echo '<option value="'.$utility->h($k).'" '.$selected.'>';
							echo $utility->h($v['category_name']);
							echo '</option>';
						}
					}
				?>
				<?php echo \Form::textarea('edit[category_description]',$result[0]['category_description'], array('class' => 'form-control span8','placeholder'=>'Category Description'));?>
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