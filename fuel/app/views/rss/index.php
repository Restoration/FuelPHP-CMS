<?php include APPPATH . 'views/parts/header.php';?>
<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class=" icon-signal"></i>RSS</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
				<?php echo \Form::open('rss/add');?>
				<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
				<?php echo \Form::input('add[rss][]',$utility->h($result[0]), array('class' => 'form-control span8','placeholder'=>'Please enter RSS URL.'));?>
				<?php echo \Form::input('add[rss][]',$utility->h($result[1]), array('class' => 'form-control span8','placeholder'=>'Please enter RSS URL.'));?>
				<?php echo \Form::input('add[rss][]',$utility->h($result[2]), array('class' => 'form-control span8','placeholder'=>'Please enter RSS URL.'));?>
				<div class="right_link"><?php echo Form::submit('add[save]','Send', array('class' => 'form-control btn btn-primary'));?></div>
				<?php echo \Form::close();?>
	        </div>
		</div>
	</div>
	<!-- /block -->
	<?php if(!empty($data)):?>
		<?php foreach($data as $key => $datas):?>
			<?php if(!empty($datas)): ?>
			<!-- block -->
	        <div class="block">
	            <div class="navbar navbar-inner block-header">
	                <div class="muted pull-left"><?php echo $key;?></div>
	            </div>
	            <div class="block-content collapse in">
	                <div class="span12">
						<table class="table table-bordered">
			              <tbody>
							<?php
							for($i=0; $i < count($datas); $i++){
								echo '<tr>';
								echo '<td><a href="'.$datas[$i]['link'].'">';
								echo $datas[$i]['title'];
								echo '</a></td>';
								echo '</tr>';
							}
							?>
			              </tbody>
			            </table>
	                </div>
	            </div>
	        </div>
	        <!-- /block -->
	        <?php endif;?>
		<?php endforeach;?>
	<?php endif;?>
</div>
<?php include APPPATH . 'views/parts/footer.php';?>