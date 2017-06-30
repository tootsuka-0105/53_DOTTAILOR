<?php
  require_once('function.php');
  # データを取得します。
  $recordSet=array();
  try{
    $pdo = connect();
    $sql = "SELECT
                 P1.ID                     AS ID
                ,P1.DATE_FIELD01           AS visits_date
                ,P1.NUM_FIELD01            AS price
                ,IFNULL(P1.STR_FIELD02,'') AS item_name
                ,IFNULL(P1.STR_FIELD73,'') AS rj_num
                ,IFNULL(P2.STR_FIELD01,'') AS measuring_name
                ,IFNULL(P4.STR_FIELD02,'') AS customer_name
                ,IFNULL(P5.STR_FIELD01,'') AS charge_name
            FROM
                PRT_DATA P1
                LEFT JOIN
                PRT_DATA P2 ON
                P1.REL_FIELD03 = P2.ID -- 担当者マスタ(採寸者)
                LEFT JOIN
                PRT_DATA P3 ON
                P1.REL_FIELD01 = P3.ID -- 販売管理
                LEFT JOIN
                PRT_DATA P4 ON
                P3.REL_FIELD01 = P4.ID -- 顧客マスタ
                LEFT JOIN
                PRT_DATA P5 ON
                P4.REL_FIELD01 = P5.ID -- 担当者マスタ
            WHERE
                    P1.PRJ_CD      = 'DOTTAILOR'
                AND P1.ENTITY_ID   = 'prtData09'
                AND P1.NUM_FIELD45 > 0
            ORDER BY
                CAST(P1.STR_FIELD73 AS SIGNED) DESC";
    $st = $pdo->prepare($sql);
    //実行
    $st->execute();
    $recordSet = $st->fetchAll(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e)
  {
    throw new Exception('Error:'.$e->getMessage());
    //DB切断
    unset( $pdo);
    exit;
  }
  # Content-Typeを「application/json」に設定します。
  header("Content-Type: application/json; charset=UTF-8");
  # IEがContent-Typeヘッダーを無視しないようにします(HTML以外のものをHTML扱いしてしまうことを防ぐため)
  header("X-Content-Type-Options: nosniff");
  # 可能な限りのエスケープを行い、JSON形式で結果を返します。
  echo json_encode(h($recordSet));
?>
