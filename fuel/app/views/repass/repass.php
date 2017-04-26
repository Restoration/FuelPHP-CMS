<?php include APPPATH . 'views/parts/header.php';?>
<script type="text/javascript">
jQuery(function($){
	G = {};
	G.USER_PASSWORD_LOW_LIMIT = 6; // パスワードの文字数下限
	G.USER_PASSWORD_UPP_LIMIT = 255; // パスワードの文字数上限

	var inputValidation = function(){
		var id = $(this).attr('id')
		var data = {};
		data.id = id;
		switch(id){
			case 'inputPassword':
				var html = '';
				var stringCount = $(this).val().length;
				if (stringCount < G.USER_PASSWORD_LOW_LIMIT || stringCount > G.USER_PASSWORD_UPP_LIMIT){
					html += '<p class="errorMessage">パスワードは'+G.USER_PASSWORD_LOW_LIMIT+'文字以上'+G.USER_PASSWORD_UPP_LIMIT+'文字以下で入力して下さい</p>';
				}
				$('#inputPasswordMessage').html(html);
			break;
			case 'inputPasswordConf':
				var html = '';
				var stringCount = $(this).val().length;
				if($(this).val() != $('#inputPassword').val()){
					html += '<p class="errorMessage">パスワード確認と一致しません</p>';
				}
				if (stringCount < G.USER_PASSWORD_LOW_LIMIT || stringCount > G.USER_PASSWORD_UPP_LIMIT){
					html += '<p class="errorMessage">パスワードは'+G.USER_PASSWORD_LOW_LIMIT+'文字以上'+G.USER_PASSWORD_UPP_LIMIT+'文字以下で入力して下さい</p>';
				}
				$('#inputPassWordConfMessage').html(html);

			break;
		}
	}

	var userInterface = function(){
		$(document).on('change','#inputPassword,#inputPasswordConf',inputValidation);
	}

	var init = function(){
		userInterface();
	}

	init();
});
</script>
	<!-- パスワード再発行
	================================================== -->
	<div id="wrap" class="clearfix">
		<section class="col-md-12">
			<div class="container">
				<h3 class="text-xs-center m-b-3">パスワード再発行</h3>
					<?php if(!empty($result_message) || !empty($error_message)):?>
						<div id="alerts">
							<div class="alert alert-<?php echo $model_utility->h($result_string);?>">
								<?php echo $model_utility->h($alert_message);?>
							</div>
						<?php
							if(!empty($errors)){
								foreach($errors as $key => $val){
									echo $model_utility->h($val).'<br />';
								}
							}
						?>
						</div>
					<?php endif;?>
					<?php echo \Form::open(array('name'=>'repass','method'=>'post')); ?>
					<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>

					<div class="row">
						<div class="col-xl-12">
						<div id="inputPasswordMessage" class="formMessage"></div>
							<div class="form-group form-control-password">
							<label class="sr-only" for="inputEmail">パスワード</label>
							<?php echo \Form::input('repass[password]','', array('type'=>'password','placeholder'=>'パスワード (6文字以上で入力してください)','id'=>'inputPassword','class' => 'form-control form-control-lg','required'=>'required'));?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xl-12">
							<div id="inputPassWordConfMessage" class="formMessage"></div>
							<div class="form-group form-control-confPasswrod">
								<label class="sr-only" for="inputEmail">パスワード確認用</label>
								<?php echo \Form::input('repass[password_conf]','', array('type'=>'password','placeholder'=>'パスワード確認','id'=>'inputPasswordConf','class' => 'form-control form-control-lg','required'=>'required'));?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xl-12">
							<div class="form-group">
								<?php echo \Form::submit('submit','再発行',array('type'=>'submit','class' => 'btn btn-primary btn-block'));?>
							</div>
						</div>
					</div>
				<?php echo \Form::close();?>
			</div>
		</section>
	</div>
<?php include APPPATH . 'views/parts/footer.php';?>