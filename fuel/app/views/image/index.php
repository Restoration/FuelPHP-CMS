<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-picture"></i>Post an image</div>
			<div class="pull-right"><button id="changeInput" class="btn" data-flg="1">Change</button></div>
		</div>
		<div class="block-content collapse in" id="input-file">
	        <div class="span12">
		        <?php echo \Form::open(
			        array(
			        'enctype'=>'multipart/form-data',
			        'action'=>'image/add'));?>
			    <?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
			    <?php echo \Form::input('inputflg',1,array('type'=>'hidden'));?>
			    <p><?php echo \Form::input('add[image_0]','',array('type'=>'file','class'=>'inputFile'));?></p>
			    <div id="addImage" class="btn btn-success">Add</div>
			    <p class="right_link">
				    <?php echo \Form::submit('add[save]','Send', array('class' => 'form-control btn btn-primary'));?>
				    </p>
				<?php echo \Form::close();?>
			</div>
		</div>
		<div class="block-content collapse in" id="input-drag">
			<div id="drag-area">
				<p class="description">Please drag and drop files to upload.</p>
			</div>
		</div>
	</div>
	<!-- /block -->
</div>

<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-th"></i>Gallery</div>
			<div id="imageCount" class="pull-right"><span class="badge badge-info"><?php echo $count?></span></div>
		</div>
		<div class="block-content collapse in">
			<div id="image_wrap">
				<div class="row-fluid padd-bottom">
				<?php if(empty($result)) :?>
					<p>Image has not been uploaded.</p>
				<?php else :?>
					<?php for($i =0; $i<count($result); $i++):?>
							<?php
								if(!($i == 0)){
									if( ($i % 4) == 0){
										echo '</div>';
										echo '<div class="row-fluid padd-bottom">';
									}
								}
							?>
							<div class="span3 image_area">
								<a href="<?php echo $utility->h($result[$i]['file_saved_abs_to'].$result[$i]['file_saved_as']);?>" data-lightbox="image-set" class="thumbnail">
									<img src="<?php echo $utility->h($result[$i]['file_saved_abs_to'].$result[$i]['file_saved_as']);?>" alt="<?php echo $utility->h($result[$i]['file_name']);?>" style="width: 260px; height: 180px;" />
								</a>
								<i class="icon-remove" data-file-id="<?php echo $utility->h($result[$i]['file_id'])?>" data-file-saved-path="<?php echo $utility->h($result[$i]['file_saved_to'].$result[$i]['file_saved_as'])?>"></i>
							</div>
					<?php endfor;?>
				<?php endif;?>
				</div><!-- /.row-fluid padd-bottom -->
			</div><!-- /#image_wrap -->
		</div>
	</div>
	<!-- /block -->
</div>
<div id="pagination_wrap">
	<?php echo \Pagination::instance('pagination');?>
</div>
<?php include APPPATH . 'views/parts/footer.php';?>