<!DOCTYPE HTML>
<html>
<header>
<meta charset="utf-8">
<title>自動再発行</title>
</header>
<body>
<h2>Hi. <?php echo $username;?></h2>
<p> Because we have accepted your request to reissue your password,We will issue a new password. </p>
Please login with the new password from the link below. </p>
<p> In addition, the old password can not be used already. </p>
<p>New PassWord : <?php echo $repass;?></p>
<p><?php echo Html::anchor($anchor,'ログイン')?></p>

<p>Thank you for your consideration.</p>

</body>
</html>


