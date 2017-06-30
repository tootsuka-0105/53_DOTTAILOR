<?php

//////////////////////////////////////////////////
// ログイン認証
function auth() {
	$apiUrl = "http://153.121.60.215/management/Login.php";

	// ユーザIDの入力チェック
	if (empty($_POST['userid']) || empty($_POST['password'])) {
		$_SESSION = array();
		errorMessage('ログインID又は、パスワードが未入力です。');
	}

	// ユーザIDとパスワードが入力されていたら認証する
	if (!empty($_POST['userid']) && !empty($_POST['password'])) {
		// ログイン認証URI
		$requestUri = $apiUrl . "/?" .  "userCd=" . $_POST['userid'] . "&Password=" . $_POST['password'];

		// 認証リクエスト
		$json = file_get_contents($requestUri);
		// 文字コード変換
		$json = mb_convert_encoding($json, 'UTF8', 'ASCII, JIS, UTF-8, EUC-JP, SJIS-WIN');
		// 結果JSONの連想配列化
		$authResult = json_decode($json, true);


		// 認証成功
		if ($authResult[0]['result'] == '0') {
			$_SESSION['SID'] = $_COOKIE["PHPSESSID"];

		} else {
			// 認証失敗
			$_SESSION = array();
			errorMessage('ログインIDあるいはパスワードに誤りがあります。');
		}
	}
}


//////////////////////////////////////////////////
// ログアウト処理
function logout() {
	if (isset($_COOKIE["PHPSESSID"])) {
		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	$_SESSION = array();
}

?>
