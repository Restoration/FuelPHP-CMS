<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();
	$post = \Session::get_flash('post');
?>

<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil "></i>New tag addition</div>
		</div>
		<div class="block-content collapse in">
			<span class="span12">
			<?php echo \Form::open('tag/add');?>
				<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
				<?php echo \Form::input('add[tag_name]',$utility->h($post['tag_name']), array('class' => 'form-control span8','placeholder'=>'Tag Name'));?>
				<?php echo \Form::input('add[tag_slug]',$utility->h($post['tag_slug']), array('class' => 'form-control span8','placeholder'=>'Slug Name'));?>
				<?php echo \Form::textarea('add[tag_description]',$post['tag_description'], array('class' => 'form-control span8','placeholder'=>'Tag Description'));?>
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
			<div class="muted pull-left"><i class="icon-search"></i>Tag Search</div>
		</div>
		<div class="block-content collapse in">
			<div class="span12">
				<?php echo \Form::input('tag_search','', array('class' => 'form-control span8','placeholder'=>'Search Word'));?>
			</div>
		</div>
	</div>
	<!-- /block -->
</div>



<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-tag"></i>Tag List</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
		       <?php if(empty($result)) :?>
			       <p>There are no tags to display.</p>
		       <?php else : ?>
		          <table class="table table-hover" id="table_tag_list">
		            <thead>
		              <tr class="post_row">
		                <th>Name</th>
		                <th>Description</th>
		                <th>Slug</th>
		                <th></th>
		              </tr>
		            </thead>
		            <tbody>
		              <?php for($i =0; $i < count($result); $i++) : ?>
		              <tr>
		                <td><?php echo $utility->h($result[$i]['tag_name']);?></td>
		                <td><?php echo $utility->h($result[$i]['tag_description']);?></td>
		                <td><?php echo $utility->h($result[$i]['tag_slug']);?></td>
		                <td><a href="<?php echo \Uri::base().'tag/preview?id='.$utility->h($result[$i]['tag_id']);?>" class="btn btn-primary">Edit</a></td>
		              </tr>
		              <?php endfor;?>
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
