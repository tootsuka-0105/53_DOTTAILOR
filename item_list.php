<?php
	require_once('function.php');
  try{
    $pdo = connect();
    $sql = "SELECT
                 ID
                ,STR_FIELD01
            FROM
                PRT_DATA
            WHERE
                    PRJ_CD    = 'DOTTAILOR'
                AND ENTITY_ID = 'prtData07'
            ORDER BY
                NUM_FIELD01";
    $st = $pdo->prepare($sql);
    //実行
    $st->execute();
    $item_list = $st->fetchAll(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e)
  {
    throw new Exception('Error:'.$e->getMessage());
    //DB切断
    unset( $pdo);
    exit;
  }
  $item_option = array();
  for ($i=0,$l=count($item_list);$i<$l;$i++) {
    $item_option[] = '<option value="'.$item_list[$i]["ID"].'" data-item_name="'.h($item_list[$i]["STR_FIELD01"]).'">'.h($item_list[$i]["STR_FIELD01"]).'</option>';
  }
  $item_option = implode('',$item_option);
?>
