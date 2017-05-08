<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-tag"></i>File List</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
		        <div class="right_link">
			       <?php echo Html::anchor('markdown/edit','Add',array('class' => 'form-control btn btn-primary'));?>
			    </div>
		       <?php if(empty($result)) :?>
			       <p>There are no file to display.</p>
		       <?php else : ?>
		          <table class="table table-hover" id="table_tag_list">
		            <thead>
		              <tr class="post_row">
		                <th>Name</th>
		                <th></th>
		              </tr>
		            </thead>
		            <tbody>
		              <?php for($i =0; $i < count($result); $i++) : ?>
		              <tr>
		                <td><?php echo $utility->h($result[$i]['title']);?></td>
		                <td><a href="<?php echo \Uri::base().'markdown/edit?id='.$utility->h($result[$i]['id']);?>" class="btn btn-primary">Edit</a></td>
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