<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();

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
			<div class="muted pull-left"><i class="icon-search"></i>Search articles</div>
		</div>
		<div class="block-content clearfix in">
			<div class="span12">
				<?php echo \Form::input('post_search','', array('class' => 'form-control span8','placeholder'=>'Search word'));?>
				<div class="controls dropdown" id="post_category_search_wrap">
					<span class="input-xlarge uneditable-input dropdown-toggle" id="category_search_trigger" role="button" data-toggle="dropdown">カテゴリー検索</span>
					<ul class="dropdown-menu"  id="category_search_list"></ul>
					<?php echo \Form::input('post_category_search','', array('type'=>'hidden'));?>
				</div>
				<?php // echo \Form::input('post_tag_search','', array('type'=>'hidden','name'=>'tags','class' => 'form-control span8 tagit-hidden-field','placeholder'=>'タグ検索'));?>
				<input type="text" value="Tag1,Tag2" name="tags" id="form_post_tag_search">
			</div>
		</div>
	</div>
	<!-- /block -->
</div>



<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-list"></i>Post List</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
		       <?php if(empty($result)) :?>
			       <p>There are no articles to display.</p>
		       <?php else : ?>
		          <table class="table table-hover" id="table_post_list">
		            <thead>
		              <tr class="post_row">
		                <th>Date</th>
		                <th>Post's Title</th>
		                <th></th>
		              </tr>
		            </thead>
		            <tbody>
		              <?php for($i =0; $i < count($result); $i++) : ?>
		              <tr>
		                <td><?php echo $model_utility->h(str_replace('-','/',substr($result[$i]['registerdate'],0,10)));?></td>
		                <td><?php echo $model_utility->h($result[$i]['post_title']);?></td>
		                <td><a href="<?php echo \Uri::base().'postlist/preview?id='.$model_utility->h($result[$i]['post_id']);?>" class="btn btn-primary">Edit</a></td>
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
