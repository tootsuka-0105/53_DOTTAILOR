<?php
function errorMessage($msg) {
	headerHtml('エラー');
	echo <<< EOS
			<h3><i class="fa fa-fw fa-exclamation-triangle"></i> エラー</h3>

			<div class="text-center">
				<p class="text-center">
					$msg
				</p>
				<button type="button" class="btn btn-info" onclick="history.back();">戻る</button>
			</div>
EOS;
	footerHtml('');
}


function headerHtml($pageTitle) {
	$style_css = '<link rel="stylesheet" type="text/css" href="css/style.css?date='.echo_filedate("js/functions.js").'">';
	echo <<< EOS
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="keywords" content="">
		<meta name="description" content="">
		<meta name="author" content="ENTEREAL">
		<meta name="format-detection" content="telephone=no">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

		<title>$pageTitle</title>

		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Raleway:900'>
		<link rel='stylesheet' type='text/css' href='css/jquery-ui.min.css'>
		<link rel='stylesheet' type='text/css' href='css/bootstrap-datetimepicker.min.css'>
		<link rel="stylesheet" type="text/css" href="css/formValidationV3-1.0.3.css">
		{$style_css}

		<!--[if lt IE 9]>
			<script type="text/javascript" charset="UTF-8" src="http://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script type="text/javascript" charset="UTF-8" src="http://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<header>
			<h1><a href="./">DOTTAILOR</a></h1>
EOS;

	if (isset($_SESSION['SID'])) {
		echo <<< EOS

			<form action="./" method="POST">
				<ul id="top-menu">
					<li><button type="submit" name="c" value="order_list"><i class="fa fa-fw fa-shopping-cart"></i> 受注一覧</button></li>
					<li><button type="submit" name="c" value="customer_list"><i class="fa fa-fw fa-user"></i> 顧客一覧</button></li>
					<li class="hidden-xs">　　　</li>
					<li><button type="submit" name="c" value="logout"><i class="fa fa-fw fa-power-off"></i> ログアウト</button></li>
				</ul>
			</form>
EOS;
	}


	echo <<< EOS
		</header>

		<div class="container-fluid">
EOS;
}

function footerHtml($jsScript) {
	$functions_js = '<script type="text/javascript" charset="UTF-8" src="js/functions.js?date='.echo_filedate("js/functions.js").'"></script>';
	echo <<< EOS
		</div>

		<script type="text/javascript" charset="UTF-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript" charset="UTF-8" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script type="text/javascript" charset="UTF-8" src="js/formValidationV3-1.0.3.js"></script>
		{$functions_js}
$jsScript
	</body>
</html>
EOS;
exit;
}
?>
