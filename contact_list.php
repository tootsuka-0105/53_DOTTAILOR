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
                AND ENTITY_ID = 'prtData02'
            ORDER BY
                NUM_FIELD03";
    $st = $pdo->prepare($sql);
    //実行
    $st->execute();
    $contact_list = $st->fetchAll(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e)
  {
    throw new Exception('Error:'.$e->getMessage());
    //DB切断
    unset( $pdo);
    exit;
  }
  $contact_option = array();
  for ($i=0,$l=count($contact_list);$i<$l;$i++) {
    $contact_option[] = '<option value="'.$contact_list[$i]["ID"].'" data-contact_name="'.h($contact_list[$i]["STR_FIELD01"]).'">'.h($contact_list[$i]["STR_FIELD01"]).'</option>';
  }
  $contact_option = implode('',$contact_option);
?>
