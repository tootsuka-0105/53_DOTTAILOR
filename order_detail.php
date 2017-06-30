<?php
  require_once('function.php');
  $recordSet=array();
  $id = post('order_id');
  if (!$id){
    $recordSet[] = array(
      'ID'=> '1'
      );
  } else {
    try{
      $pdo = connect();
      $sql = "SELECT
                 P1.ID
                -- ,P1.STR_FIELD78
                ,P1.NUM_FIELD01 -- 受注金額
                ,P1.NUM_FIELD52 -- 身長
                ,P1.NUM_FIELD53 -- 体重
                ,P1.DATE_FIELD01 -- 受注日
                ,P1.REL_FIELD02 -- 商品ID
                ,P1.REL_FIELD03 -- 採寸者ID
                ,P1.STR_FIELD12 -- 商品名
                -- ,P2.ID          AS sales08_id -- 販売管理ID
                ,P3.ID          AS customer_id -- 顧客ID(顧客マスタ)
                ,P3.REL_FIELD01 AS charge_id -- 担当者ID(顧客マスタ)
                ,P3.STR_FIELD02 AS customer_name -- お客様名(顧客マスタ)
                ,P3.STR_FIELD08 AS company_name -- 法人名(顧客マスタ)
            FROM
                PRT_DATA P1
                LEFT JOIN
                PRT_DATA P2 ON
                P1.REL_FIELD01 = P2.ID
                LEFT JOIN
                PRT_DATA P3 ON
                P2.REL_FIELD01 = P3.ID
            WHERE
                    P1.PRJ_CD    = 'DOTTAILOR'
                AND P1.ENTITY_ID = 'prtData09'
                AND P1.ID        = :id";
      $st = $pdo->prepare($sql);
      $st->bindValue(':id', $id, PDO::PARAM_STR);
      //実行
      $st->execute();
      $recordSet = $st->fetch(PDO::FETCH_ASSOC);
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
  echo json_encode(h($recordSet));
?>
