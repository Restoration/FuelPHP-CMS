<?php
	$model_utility = new Model_Utility();
?>

	<!-- モーダルウィンドウの中身 -->
    <!-- Login
    ================================================== -->
	<section class="section-login modal fade" id="myModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="container">
					<div class="modal-header">
						<p class="closeBtn" data-dismiss="modal"><?php echo \Asset::img('close-btn.png', array('width'=>'32', 'height'=>'32', 'alt'=>'閉じる')); ?></p>
			        	<h4 class="modal-title">ログイン</h4>
					</div>
					<div class="modal-body">
						<?php echo \Form::open('app/login');?>
						<?php
							$username = !empty($username)? $username : '';
							if(!empty($errmsg)){
								echo '<div class="alert alert-error">';
								foreach($errmsg as $key => $val){
									echo $model_utility->h($val).'<br />';
								}
								echo '</div>';
							}
						?>
						<div class="row">
							<div class="col-xl-12">
								<div class="form-group form-control-name">
									<label class="sr-only" for="inputName">ユーザー名</label>
										<?php echo \Form::input('username',\Input::post('username'),array('type'=>'text','id'=>'inputName','placeholder'=>'ユーザー名','class'=>'form-control form-control-lg','required'=>'required'));?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-12">
								<div class="form-group form-control-email">
									<label class="sr-only" for="inputPassWord">パスワード</label>
									<?php echo \Form::input('password',\Input::post('password'),array('type'=>'password','id'=>'inputPassWord','placeholder'=>'パスワード','class'=>'form-control form-control-lg','required'=>'required'));?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xl-12">
								<?php echo \Html::anchor('auth/login/twitter', '<span class="fa fa-twitter"></span> Sign in with Twitter',array('class'=>'btn btn-block btn-social btn-twitter'));?>

								<?php echo \Html::anchor('auth/login/facebook', '<span class="fa fa-facebook"></span> Sign in with facebook',array('class'=>'btn btn-block btn-social btn-facebook'));?>
							</div>
						</div>

						<div class="row">
							<div class="col-xl-12">
								<div class="form-group">
									<p>ユーザーアカウントをお持ちでない方は<?php echo Html::anchor('user/add','こちら');?></p>
									<?php echo \Html::anchor('repass','パスワードをお忘れですか?');?>
									<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
									<button type="submit" class="btn btn-primary btn-block">ログイン</button>
								</div>
							</div>
						</div>
						<?php echo \Form::close();?>
					</div>
				</div>
			</div>
		</div>
	</section>





<!DOCTYPE html>
<html class="no-js">
<head>
<title></title>
<!-- Bootstrap -->
<link rel="stylesheet" type="text/css" href="vendors/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css">
</link>
<meta charset="UTF-8">
<?php echo Asset::css('bootstrap.min.css'); ?>
<?php echo Asset::css('bootstrap-responsive.min.css'); ?>
<?php echo Asset::css('style.css'); ?>
<?php echo Asset::css('styles.css'); ?>
<?php echo Asset::css('lightbox.css'); ?>
<?php echo Asset::js('vendors/jquery-2.2.0.min.js'); ?>
<?php echo Asset::js('vendors/modernizr-2.6.2-respond-1.1.0.min.js'); ?>
<?php echo Asset::js('bootstrap-datepicker.js'); ?>
<?php echo Asset::js('bootstrap-datepicker.ja.js'); ?>
<?php echo Asset::js('lightbox.min.js'); ?>
<?php echo Asset::js('vendors/modernizr-2.6.2-respond-1.1.0.min.js'); ?>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
	<body>
		<div class="container-fluid">
			<div id="imageIframe">
				<div class="row-fluid">
					<!-- block -->
					<div class="block">
						<div class="navbar navbar-inner block-header">
							<div class="muted pull-left"><i class="icon-th"></i>Gallery</div>
							<div id="imageCount" class="pull-right"><span class="badge badge-info"><?php echo $count?></span></div>
						</div>
						<div class="block-content collapse in">
							<div id="image_wrap">
									<div id="imageView" class="block-content collapse in">
										<div class="row-fluid padd-bottom">
										<?php if(empty($result)) :?>
											<p>Image has not been uploaded.</p>
										<?php else :?>

												<?php for($i =0; $i<count($result); $i++):?>
													<div class="span3 image_area">
														<img src="<?php echo $model_utility->h($result[$i]['file_saved_to'].$result[$i]['file_saved_as']);?>" alt="<?php echo $model_utility->h($result[$i]['file_name']);?>" style="width: 260px; height: 180px;" data-image-name="<?php echo $model_utility->h($result[$i]['file_name']);?>" data-image-id="<?php echo $model_utility->h($result[$i]['file_id']);?>" data-insert="0" />
													</div>
												<?php endfor;?>
										<?php endif;?>
										</div>
										<div class="pagenateArea"><?php echo Pagination::instance('pagination');?></div>
									</div>
							</div><!-- /#image_wrap -->
						</div>
					</div>
					<!-- /block -->
				</div>
			</div>
		</div>
		<?php echo Asset::js('bootstrap/js/bootstrap.min.js'); ?>
		<?php echo Asset::js('assets/scripts.js'); ?>
    </body>
</html>