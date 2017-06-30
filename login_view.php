<?php

//////////////////////////////////////////////////
// ログイン
function login() {
	headerHtml('ログイン');
	echo <<< EOS
			<h3><i class="fa fa-fw fa-key"></i> ログイン</h3>
			<!--
			<p class="text-danger">※ログインAPIが正しく動いていないのでログインできません。<br>　<a href="http://153.121.60.215/management/Login.php" target="_blank">ここ</a>をクリックするとAPIの戻り値を確認できます。</p>
			-->

			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<form action="./" method="POST" class="form-horizontal form-striped" id="login-form">
						<div class="form-group">
							<label for="userid" class="control-label col-sm-5">ログインID</label>
							<div class="col-sm-7">
								<input type="text" class="form-control ime-off" name="userid" id="userid" placeholder="" valCheck="required">
							</div>
						</div>

						<div class="form-group">
							<label for="password" class="control-label col-sm-5">ログインパスワード</label>
							<div class="col-sm-7">
								<input type="password" class="form-control ime-off" name="password" id="password" placeholder="" valCheck="required">
							</div>
						</div>

						<div class="form-group mt-3x">
							<div class="col-sm-12 text-center">
								<button type="submit" class="btn btn-primary" name="c" value="auth" valCheck="submit">ログイン</button>
							</div>
						</div>
					</form>
				</div>
			</div>



<form action="./" method="POST">
<button type="submit" class="btn btn-primary" name="c" value="auth2" valCheck="submit">仮ログイン</button>
</form>
EOS;

	$jsScript = <<< EOS
		<script>
		// Fire when the DOM is built
		$(document).ready(function() {
			$('#login-form').formValidation({'addIcon': 'false'});
		});
		</script>
EOS;

	footerHtml($jsScript);
}

?>
