<!DOCTYPE html>
<html>
<head>
<title><?php echo $title; ?></title>
<!-- Bootstrap -->
<link rel="stylesheet" type="text/css" href="vendors/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css">
</link>
<meta charset="UTF-8">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="assets/styles.css" rel="stylesheet" media="screen">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
<?php echo Asset::css('bootstrap.css'); ?>
<?php echo Asset::css('styles.css'); ?>
<?php echo Asset::js('vendors/modernizr-2.6.2-respond-1.1.0.min.js'); ?>
</head>
	<body>
	<?php echo $content; ?>
	<?php echo Asset::js('vendors/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js'); ?>
	<?php echo Asset::js('vendors/jquery-1.9.1.min.js'); ?>
	<?php echo Asset::js('bootstrap/js/bootstrap.min.js'); ?>
	<?php echo Asset::js('vendors/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js'); ?>
	<?php echo Asset::js('vendors/ckeditor/ckeditor.js'); ?>
	<?php echo Asset::js('vendors/ckeditor/adapters/jquery.js'); ?>
	<?php echo Asset::js('vendors/tinymce/js/tinymce/tinymce.min.js'); ?>
	<?php echo Asset::js('assets/scripts.js'); ?>
	</body>
</html>
