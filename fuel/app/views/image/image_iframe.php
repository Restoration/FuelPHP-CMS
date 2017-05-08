<?php $utility = new Utility();?>
<!DOCTYPE html>
<html class="no-js">
<head>
<title>FuelPHP CMS</title>
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
														<img src="<?php echo $utility->h($result[$i]['file_saved_to'].$result[$i]['file_saved_as']);?>" alt="<?php echo $utility->h($result[$i]['file_name']);?>" style="width: 260px; height: 180px;" data-image-name="<?php echo $utility->h($result[$i]['file_name']);?>" data-image-id="<?php echo $utility->h($result[$i]['file_id']);?>" data-insert="0" />
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