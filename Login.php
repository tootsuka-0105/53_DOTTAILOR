<?php
	require_once('function.php');
	/* テキストに出力するためのコンバート処理 */
	function raw_json_encode($input) {

	    return preg_replace_callback(
	        '/\\\\u([0-9a-zA-Z]{4})/',
	        function ($matches) {
	            return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
	        },
	        json_encode($input)
	    );

	}

	// ini_set('display_errors',"1");
	// error_reporting(E_ALL); と同じ
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('error_reporting', E_ALL);

	//変数定義
	static $ct_userCd;		//ID
	static $ct_Password;		//ID

	//文字列01
	if(isset($_GET['userCd'])) {
	      $ct_userCd = $_GET['userCd'];
	  }
	if(isset($_GET['Password'])) {
	      $ct_Password = $_GET['Password'];
	  }
	//NG
	#$ct_userCd = "admin";
	#$ct_Password = "password";

	//OK
	//$ct_userCd = "admin";
	//$ct_Password = "Password!";

	//システムのURL経由でAPIを指定
	$url = SAKUSAKUNURL.'PrismApi?method=auth';

	$data = '{"prjCd":"DOTTAILOR",';
	$data .= ',"userId":';
	$data .= '"'.$ct_userCd.'"';
	$data .= ',"password":';
	$data .= '"'.$ct_Password.'"';
	//最後
	$data .= '}';

	$headers = array(
			'Content-Type: application/x-www-form-urlencoded',
	);

	$params = array(
		'prjCd' => 'DOTTAILOR',
		'userId' => $ct_userCd,
		'password' => $ct_Password,
	);


	$context = stream_context_create(array(
			'http' => array(
					'method' => 'POST',
					'header' => implode("\n", $headers),
					'ignore_errors' => true,
					'content' => http_build_query($params, '', '&'),
			),
	));

	$result = file_get_contents( $url, false, $context );
	$pos = strpos($http_response_header[0], '200');
	preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
	$status_code = $matches[1];
	switch ($status_code) {
	    case '200':
	        // 200の場合
		    $userData[]=array(
		    'result'=>'0'
		    );
		    break;
	    case '404':
	        // 404の場合
		    $userData[]=array(
		    'result'=>'1'
		    );
		    break;
	    default:
		    $userData[]=array(
		    'result'=>'2'
		    );
		    break;
	}
	echo json_encode($userData);

	//ファイル出力用処理（テキストファイル提示用）
	$filename = './json.txt';
	if (!file_exists($filename)) {
		touch($filename);
	} else {
		echo ('すでにファイルが存在しています。file name:' . $filename);
	}

	if (!file_exists($filename) && !is_writable($filename)
		|| !is_writable(dirname($filename))) {
		echo "書き込みできないか、ファイルがありません。",PHP_EOL;
		exit(-1);
	}

	$fp = fopen($filename,'a') or dir('ファイルを開けません');

	fwrite($fp, sprintf(raw_json_encode($userData)));

	fclose($fp);
?>
