<?php
  require_once('function.php');
  # データを取得します。

  $param1 = post('param1');
  $mode   = post('mode');
  if ($mode === 'list') {
    try{
      $pdo = connect();
      $sql = "SELECT
                   P1.ID          AS ID
                  ,P1.STR_FIELD01 AS STR_FIELD01
                  ,P1.STR_FIELD02 AS STR_FIELD02
                  ,P1.STR_FIELD07 AS STR_FIELD07
                  ,P2.STR_FIELD01 AS charge_name
              FROM
                  PRT_DATA P1
                  LEFT JOIN
                  PRT_DATA P2 ON
                  P1.REL_FIELD01 = P2.ID
              WHERE
                      P1.PRJ_CD    = 'DOTTAILOR'
                  AND P1.ENTITY_ID = 'prtData01'
              ORDER BY
                  CAST(P1.STR_FIELD01 AS SIGNED)";
      $st = $pdo->prepare($sql);
      //実行
      $st->execute();
      $customer_list = $st->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
      throw new Exception('Error:'.$e->getMessage());
      //DB切断
      unset( $pdo);
      exit;
    }
  } else {
    try{
      $pdo = connect();
      $sql = "SELECT
                   ID          AS customer_id
                  ,REL_FIELD01 AS charge_id
                  ,STR_FIELD02 AS customer_name
                  ,STR_FIELD08 AS company_name
                  ,NUM_FIELD03 AS height
                  ,NUM_FIELD04 AS weight
              FROM
                  PRT_DATA
              WHERE
                      PRJ_CD      = 'DOTTAILOR'
                  AND ENTITY_ID   = 'prtData01'
                  AND STR_FIELD02 like ?";
      $st = $pdo->prepare($sql);
      $st->execute(array(sprintf('%%%s%%', addcslashes($param1, '\_%'))));
      //実行
      // $st->execute();
      $customer_list = $st->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e)
    {
      throw new Exception('Error:'.$e->getMessage());
      //DB切断
      unset( $pdo);
      exit;
    }
  }
  # Content-Typeを「application/json」に設定します。
  header("Content-Type: application/json; charset=UTF-8");
  # IEがContent-Typeヘッダーを無視しないようにします(HTML以外のものをHTML扱いしてしまうことを防ぐため)
  header("X-Content-Type-Options: nosniff");

  # 可能な限りのエスケープを行い、JSON形式で結果を返します。
  echo json_encode(h($customer_list));
  exit;
?>
