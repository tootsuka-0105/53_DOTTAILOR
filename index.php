<?php
session_start();

// require
require 'function.php';
require 'common.php';
require 'login_view.php';
require 'login_controller.php';
require 'order_view.php';
require 'order_controller.php';
require 'customer_view.php';
require 'customer_controller.php';

$posted = post('c');
// ログインフォーム
if ($posted == 'login') {
	login();
}

// ログイン認証
if ($posted == 'auth') {
	auth();

	// 受注リストへ
	order_list($posted);
}

// 仮ログイン認証
if ($posted == 'auth2') {
	$_SESSION['SID'] = $_COOKIE["PHPSESSID"];
	// 受注リストへ
	order_list($posted);
}


// 未ログイン
if (!isset($_SESSION['SID'])) {
	login();
}

// ログアウト
if ($posted == 'logout') {
	logout();

	header("Location: ./");
}

// 受注リスト
if ($posted == 'order_list') {
	order_list($posted);
}

// 受注詳細
if (($posted == 'order_new') || ($posted == 'order_edit')) {
	order_detail($posted);
}

// 顧客リスト
if ($posted == 'customer_list') {
	customer_list();
}


// 受注リスト
order_list($posted);
exit;
?>
