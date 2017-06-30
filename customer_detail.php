<?php
	require_once('function.php');
	error_log('php begin', 3, '/var/www/html/app.log');
	mysql_connect('localhost', 'root', 't3123') or die(mysql_error());
	mysql_select_db('t3_db');
	mysql_query('SET NAMES UTF8');

	$recordSet=array();
	$id = $_GET['customer_id'];
	//$id = '667';

	if (is_null($id)){
		$recordSet[] = array(
			'ID'=> '1'
			/*
			,'STR_FIELD01' => 'a'
			,'STR_FIELD02' => 'a'
			,'STR_FIELD03' => 'b'
			,'STR_FIELD04' => 'c'
			,'STR_FIELD05' => 'd'
			,'STR_FIELD06' => 'e'
			,'STR_FIELD07' => 'e'
			,'STR_FIELD08' => 'e'
			,'STR_FIELD09' => 'e'
			,'STR_FIELD10' => 'e'
			,'STR_FIELD11' => 'e'
			,'STR_FIELD12' => 'e'
			,'STR_FIELD13' => 'e'
			,'STR_FIELD14' => 'e'
			,'STR_FIELD15' => 'e'
			,'STR_FIELD16' => 'e'
			,'STR_FIELD17' => 'e'
			,'STR_FIELD18' => 'e'
			,'STR_FIELD19' => 'e'
			,'STR_FIELD20' => 'e'
			,'STR_FIELD21' => 'e'
			,'STR_FIELD22' => 'e'
			,'STR_FIELD23' => 'e'
			,'STR_FIELD24' => 'e'
			,'STR_FIELD25' => 'e'
			,'STR_FIELD26' => 'e'
			,'STR_FIELD27' => 'e'
			,'STR_FIELD28' => 'e'
			,'STR_FIELD29' => 'e'
			,'STR_FIELD30' => 'e'
			,'STR_FIELD31' => 'e'
			,'STR_FIELD32' => 'e'
			,'STR_FIELD33' => 'e'
			,'STR_FIELD34' => 'e'
			,'STR_FIELD35' => 'e'
			,'STR_FIELD36' => 'e'
			,'STR_FIELD37' => 'e'
			,'STR_FIELD38' => 'e'
			,'STR_FIELD39' => 'e'
			,'STR_FIELD40' => 'e'
			,'STR_FIELD41' => 'e'
			,'STR_FIELD42' => 'e'
			,'STR_FIELD43' => 'e'
			,'STR_FIELD44' => 'e'
			,'STR_FIELD45' => 'e'
			,'STR_FIELD46' => 'e'
			,'STR_FIELD47' => 'e'
			,'STR_FIELD48' => 'e'
			,'STR_FIELD49' => 'e'
			,'STR_FIELD50' => 'e'
			,'NUM_FIELD01' => 'e'
			,'NUM_FIELD02' => 'e'
			,'NUM_FIELD03' => 'e'
			,'NUM_FIELD04' => 'e'
			,'NUM_FIELD05' => 'e'
			,'NUM_FIELD06' => 'e'
			,'NUM_FIELD07' => 'e'
			,'NUM_FIELD08' => 'e'
			,'NUM_FIELD09' => 'e'
			,'NUM_FIELD10' => 'e'
			,'NUM_FIELD11' => 'e'
			,'NUM_FIELD12' => 'e'
			,'NUM_FIELD13' => 'e'
			,'NUM_FIELD14' => 'e'
			,'NUM_FIELD15' => 'e'
			,'NUM_FIELD16' => 'e'
			,'NUM_FIELD17' => 'e'
			,'NUM_FIELD18' => 'e'
			,'NUM_FIELD19' => 'e'
			,'NUM_FIELD20' => 'e'
			,'NUM_FIELD21' => 'e'
			,'NUM_FIELD22' => 'e'
			,'NUM_FIELD23' => 'e'
			,'NUM_FIELD24' => 'e'
			,'NUM_FIELD25' => 'e'
			,'NUM_FIELD26' => 'e'
			,'NUM_FIELD27' => 'e'
			,'NUM_FIELD28' => 'e'
			,'NUM_FIELD29' => 'e'
			,'NUM_FIELD30' => 'e'
			,'NUM_FIELD31' => 'e'
			,'NUM_FIELD32' => 'e'
			,'NUM_FIELD33' => 'e'
			,'NUM_FIELD34' => 'e'
			,'NUM_FIELD35' => 'e'
			,'NUM_FIELD36' => 'e'
			,'NUM_FIELD37' => 'e'
			,'NUM_FIELD38' => 'e'
			,'NUM_FIELD39' => 'e'
			,'NUM_FIELD40' => 'e'
			,'NUM_FIELD41' => 'e'
			,'NUM_FIELD42' => 'e'
			,'NUM_FIELD43' => 'e'
			,'NUM_FIELD44' => 'e'
			,'NUM_FIELD45' => 'e'
			,'NUM_FIELD46' => 'e'
			,'NUM_FIELD47' => 'e'
			,'NUM_FIELD48' => 'e'
			,'NUM_FIELD49' => 'e'
			,'NUM_FIELD50' => 'e'
			,'DATE_FIELD01' => 'e'
			,'DATE_FIELD02' => 'e'
			,'DATE_FIELD03' => 'e'
			,'DATE_FIELD04' => 'e'
			,'DATE_FIELD05' => 'e'
			,'DATE_FIELD06' => 'e'
			,'DATE_FIELD07' => 'e'
			,'DATE_FIELD08' => 'e'
			,'DATE_FIELD09' => 'e'
			,'DATE_FIELD10' => 'e'
			,'DATE_FIELD11' => 'e'
			,'DATE_FIELD12' => 'e'
			,'DATE_FIELD13' => 'e'
			,'DATE_FIELD14' => 'e'
			,'DATE_FIELD15' => 'e'
			,'DATE_FIELD16' => 'e'
			,'DATE_FIELD17' => 'e'
			,'DATE_FIELD18' => 'e'
			,'DATE_FIELD19' => 'e'
			,'DATE_FIELD20' => 'e'
			,'MEMO_FIELD01' => 'e'
			,'MEMO_FIELD02' => 'e'
			,'MEMO_FIELD03' => 'e'
			,'MEMO_FIELD04' => 'e'
			,'MEMO_FIELD05' => 'e'
			,'REL_FIELD01' => 'e'
			,'REL_FIELD02' => 'e'
			,'REL_FIELD03' => 'e'
			,'REL_FIELD04' => 'e'
			,'REL_FIELD05' => 'e'
			,'REL_FIELD06' => 'e'
			,'REL_FIELD07' => 'e'
			,'REL_FIELD08' => 'e'
			,'REL_FIELD09' => 'e'
			,'REL_FIELD10' => 'e'
			*/
			);
	} else {
		$code = "SELECT * FROM PRT_DATA WHERE PRJ_CD = 'DOTTAILOR' AND ENTITY_ID = 'prtData01' AND ID = $id";
		//$code = "SELECT ID,STR_FIELD02,STR_FIELD03,STR_FIELD04,STR_FIELD05 FROM PRT_DATA WHERE PRJ_CD = 't-3' AND ENTITY_ID = 'prtData01' AND ID = '667'";
		$sql = mysql_query($code) or die('QueryFault:' . mysql_error());
		//$sql = mysql_query("SELECT ID,STR_FIELD02,STR_FIELD03,STR_FIELD04,STR_FIELD05 FROM PRT_DATA WHERE PRJ_CD = 't-3' AND ENTITY_ID = 'prtData01' AND ID = '667'") or die('QueryFault:' . mysql_error());
	    while($table = mysql_fetch_object($sql)) {
			 $recordSet[] = array(
			    'ID'=> $table->ID
			    ,'STR_FIELD01' => $table->STR_FIELD01
			    ,'STR_FIELD02' => $table->STR_FIELD02
			    ,'STR_FIELD03' => $table->STR_FIELD03
			    ,'STR_FIELD04' => $table->STR_FIELD04
			    ,'STR_FIELD05' => $table->STR_FIELD05
			    ,'STR_FIELD06' => $table->STR_FIELD06
			    ,'STR_FIELD07' => $table->STR_FIELD07
			    ,'STR_FIELD08' => $table->STR_FIELD08
			    ,'STR_FIELD09' => $table->STR_FIELD09
			    ,'STR_FIELD10' => $table->STR_FIELD10
			    ,'STR_FIELD11' => $table->STR_FIELD11
			    ,'STR_FIELD12' => $table->STR_FIELD12
			    ,'STR_FIELD13' => $table->STR_FIELD13
			    ,'STR_FIELD14' => $table->STR_FIELD14
			    ,'STR_FIELD15' => $table->STR_FIELD15
			    ,'STR_FIELD16' => $table->STR_FIELD16
			    ,'STR_FIELD17' => $table->STR_FIELD17
			    ,'STR_FIELD18' => $table->STR_FIELD18
			    ,'NUM_FIELD01' => $table->NUM_FIELD01
			    ,'NUM_FIELD02' => $table->NUM_FIELD02
			    ,'DATE_FIELD01' => $table->DATE_FIELD01
			    ,'MEMO_FIELD01' => $table->MEMO_FIELD01
			    ,'REL_FIELD01' => $table->REL_FIELD01
			    );
	    }
	}

	# Content-Typeを「application/json」に設定します。
	header("Content-Type: application/json; charset=UTF-8");
	# IEがContent-Typeヘッダーを無視しないようにします(HTML以外のものをHTML扱いしてしまうことを防ぐため)
	header("X-Content-Type-Options: nosniff");

	# 可能な限りのエスケープを行い、JSON形式で結果を返します。
	echo json_encode($recordSet);
?>
