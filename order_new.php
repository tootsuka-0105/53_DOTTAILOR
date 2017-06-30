<?php
  require_once('function.php');
  error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
  $visits_date     = post('visits_date');
  $item_id         = post('item_id');
  $item_name       = post('item_name');
  $item_input_name = post('item_input_name');
  $price           = (int)post('price',0);
  $charge_id       = post('charge_id');
  $charge_name     = post('charge_name');
  $measuring_id    = post('measuring_id');
  $company_name    = post('company_name');
  $customer_id     = post('customer_id');
  $customer_name   = post('customer_name');
  $height          = (int)post('height',0);
  $weight          = (int)post('weight',0);

  //顧客マスタアップデート
	$data = '{"prtId":"'.$customer_id.'",';
  $data .= '"entityId":"prtData01"';
  //文字列系
  $data .= ',"strField08":';
  $data .= '"'.$company_name.'"';
  //数字系
  $data .= ',"numField03":';
  $data .= '"'.$height.'"';
  $data .= ',"numField04":';
  $data .= '"'.$weight.'"';
  //日付系
  $data .= ',"dateField01":';
  $data .= '"'.$visits_date.'"';
  //取得したID
  $data .= ',"relField01":';
  $data .= '"'.$charge_id.'"';
  //最後
  $data .= '}';
  $result = sakusakun($data,'update');

  try{
    $pdo = connect();
    $sql = "SELECT
                MAX(CAST(STR_FIELD73 AS SIGNED))
            FROM
                PRT_DATA
            WHERE
                    PRJ_CD    = 'DOTTAILOR'
                AND ENTITY_ID = 'prtData09'";
    $st = $pdo->prepare($sql);
    $st->execute();
    $RJ_NO = $st->fetch(PDO::FETCH_COLUMN);
    $RJ_NO = str_pad(((int)$RJ_NO+1),5,0,STR_PAD_LEFT);
  }
  catch(PDOException $e)
  {
    throw new Exception('Error:'.$e->getMessage());
    //DB切断
    unset( $pdo);
    exit;
  }
  //同顧客同日に販売管理テーブルのデータが有ったらそのIDを使用
  try{
    $sql = "SELECT
                MAX(ID)
            FROM
                PRT_DATA
            WHERE
                    DATE_FORMAT(DATE_FIELD01,'%Y/%m/%d') = :visits_date
                AND REL_FIELD01                          = :customer_id
                AND PRJ_CD                               = 'DOTTAILOR'
                AND ENTITY_ID                            = 'prtData08'";
    $st = $pdo->prepare($sql);
    $st->bindValue(':visits_date', $visits_date, PDO::PARAM_STR);
    $st->bindValue(':customer_id', $customer_id, PDO::PARAM_STR);
    $st->execute();
    $id = $st->fetch(PDO::FETCH_COLUMN);
  }
  catch(PDOException $e)
  {
    throw new Exception('Error:'.$e->getMessage());
    //DB切断
    unset( $pdo);
    exit;
  }
  if (empty($id)) {
    //販売管理テーブル　登録
    $data = '{"prtId":"9999999999",';
    $data .= '"entityId":"prtData08"';
    //来店日
    $data .= ',"dateField01":';
    $data .= '"'.$visits_date.'"';
    //顧客情報
    $data .= ',"relField01":';
    $data .= '"'.$customer_id.'"';
    //顧客名
    $data .= ',"strField06":';
    $data .= '"'.$customer_name.'"';
    //担当者
    $data .= ',"strField07":';
    $data .= '"'.$charge_name.'"';
    //担当者マスタ
    $data .= ',"relField03":';
    $data .= '"'.$charge_id.'"';
    $data .= '}';
    $result = sakusakun($data,'insert');
    try{
      $sql = "SELECT
                  MAX(ID)
              FROM
                  PRT_DATA
              WHERE
                      DATE_FORMAT(DATE_FIELD01,'%Y/%m/%d') = :visits_date
                  AND REL_FIELD01                          = :customer_id
                  AND PRJ_CD                               = 'DOTTAILOR'
                  AND ENTITY_ID                            = 'prtData08'";
      $st = $pdo->prepare($sql);
      $st->bindValue(':visits_date', $visits_date, PDO::PARAM_STR);
      $st->bindValue(':customer_id', $customer_id, PDO::PARAM_STR);
      $st->execute();
      $id = $st->fetch(PDO::FETCH_COLUMN);
    }
    catch(PDOException $e)
    {
      throw new Exception('Error:'.$e->getMessage());
      //DB切断
      unset( $pdo);
      exit;
    }
  }
  //商品販売管理テーブル　登録
  $data = '{"prtId":"9999999999",';
  $data .= '"entityId":"prtData09"';
  //文字列系
  $data .= ',"strField02":';
  $data .= '"'.$item_name.'"';
  $data .= ',"strField12":';
  $data .= '"'.$item_input_name.'"';
  $data .= ',"strField73":';
  $data .= '"'.$RJ_NO.'"';
  $data .= ',"strField78":';
  $data .= '"'.$customer_name.'"';
  //数字系
  $data .= ',"numField01":';
  $data .= '"'.$price.'"';
  $data .= ',"numField45":';
  $data .= '"1"';
  $data .= ',"numField52":';
  $data .= '"'.$height.'"';
  $data .= ',"numField53":';
  $data .= '"'.$weight.'"';
  //日付系
  $data .= ',"dateField01":';
  $data .= '"'.$visits_date.'"';
  //取得したID
  $data .= ',"relField01":';
  $data .= '"'.$id.'"';
  $data .= ',"relField02":';
  $data .= '"'.$item_id.'"';
  $data .= ',"relField03":';
  $data .= '"'.$measuring_id.'"';
  //最後
  $data .= '}';
  $data = preg_replace("/( |　)/", "", $data );
  $result = sakusakun($data,'insert');


  //変数定義
/*
  static ct_id;      //ID
  static ct_dateField01;  // 来店日
  static ct_dateField04;  // 納品日
  static ct_memoField01;  // その他特記事項
  static ct_numField01;  // 受注金額
  static ct_numField02;  // 【計測】総丈
  static ct_numField03;  // 【計測】着丈
  static ct_numField04;  // 【計測】肩幅
  static ct_numField05;  // 【計測】半胴
  static ct_numField06;  // 【計測】袖丈(右)
  static ct_numField07;  // 【計測】袖丈(左)
  static ct_numField08;  // 【計測】バスト(実寸)
  static ct_numField09;  // 【計測】中胴(実寸)
  static ct_numField10;  // 【計測】OB(実寸)
  static ct_numField11;  // 【素材】生地No.
  static ct_numField12;  // 【素材】生地サイズ
  static ct_numField13;  // 【素材】カラークロス
  static ct_numField14;  // 【ジャケット】衿巾
  static ct_numField15;  // 【ジャケット】N点より第一釦
  static ct_numField16;  // 【ジャケット】釦間
  static ct_numField17;  // 【ジャケット】フロント仕様
  static ct_numField18;  // 【パンツ】ループ下り
  static ct_numField19;  // 【パンツ】ウエスト
  static ct_numField20;  // 【パンツ】総丈
  static ct_numField21;  // 【パンツ】股上
  static ct_numField22;  // 【パンツ】股下
  static ct_numField23;  // 【パンツ】ワタリ巾
  static ct_numField24;  // 【パンツ】ヒザ巾
  static ct_numField25;  // 【パンツ】裾巾
  static ct_numField26;  // 【パンツ】ヒップ上がり寸
  static ct_numField27;  // 【パンツ】ヒップ実寸
  static ct_numField28;  // 【パンツ】オビ巾
  static ct_numField29;  // 【パンツ】タック
  static ct_numField30;  // 【パンツ】W
  static ct_numField31;  // 【パンツ】ループ
  static ct_numField32;  // 【パンツ】ループ本
  static ct_numField33;  // 【パンツ】袖釦個数
  static ct_numField34;  // 【ベスト】前丈
  static ct_numField35;  // 【ベスト】背丈
  static ct_numField36;  // 【ベスト】半胴
  static ct_numField37;  // 【ベスト】前釦(左)
  static ct_numField38;  // 【ベスト】前釦(右)
  static ct_numField39  // 【素材】中釦個数
  static ct_numField40  // 【素材】小釦個数
  static ct_numField41  // 【素材】中釦
  static ct_numField42  // 【素材】小釦
  static ct_numField43  // 【コート】右袖丈
  static ct_numField44  // 【コート】左袖丈
  static ct_numField46  // 【スカート】ウエスト
  static ct_numField47  // 【スカート】スカート丈
  static ct_numField48  // 【スカート】中ヒップ実寸
  static ct_numField49  // 【スカート】ヒップ実寸
  static ct_numField50    // スーツNo
  static ct_numField51    // 仮縫
  static ct_numField52    // 身長
  static ct_numField53    // 体重
  static ct_numField54    // 本台場
  static ct_numField62    // 【コート】ウエスト
  static ct_numField63    // 【コート】ヒップ実寸
  static ct_relField01;  // 顧客ID
  static ct_relField02;  // 商品管理
  static ct_relField03;  // 担当者マスタ
  static ct_strField01;  // 【計測】体型
  static ct_strField02;  // 購入品
  static ct_strField03;  // 【計測】体型２
  static ct_strField04;  // 【計測】体型３
  static ct_strField05;  // 【素材】生地No在発
  static ct_strField06;  // 設計書
  static ct_strField07;  // 【素材】シャツ品番
  static ct_strField08;  // 【素材】裏地持工
  static ct_strField09;  // 【素材】釦持工
  static ct_strField10;  // 【素材】裏地
  static ct_strField11;  // 【素材】釦
  static ct_strField12;  // 【素材】品名
  static ct_strField13;  // 【素材】生地No.
  static ct_strField14;  // 【ジャケット】前釦
  static ct_strField15;  // 【ジャケット】衿型
  static ct_strField16;  // 【ジャケット】胸P
  static ct_strField17;  // 【ジャケット】腰P
  static ct_strField18;  // 【ジャケット】ベンツ
  static ct_strField19;  // 【ジャケット】裏仕様
  static ct_strField20;  // 【ジャケット】マーク
  static ct_strField21;  // 【ジャケット】袖釦
  static ct_strField22;  // 【ジャケット】パット
  static ct_strField23;  // 【ジャケット】パット(右)
  static ct_strField24;  // 【ジャケット】パット(左)
  static ct_strField25;  // 【ジャケット】ショルダー
  static ct_strField26;  // 【ジャケット】肩補正
  static ct_strField27;  // 【ジャケット】ゴージライン
  static ct_strField28;  // 【ジャケット】ツキ取り
  static ct_strField29;  // 【ジャケット】モデル
  static ct_strField30;  // 【ジャケット】サイズ
  static ct_strField31;  // 【ジャケット】ピックステッチ
  static ct_strField32;  // 【ジャケット】衿穴糸No.
  static ct_strField33;  // 【ジャケット】前身頃六糸No.1
  static ct_strField34;  // 【ジャケット】前身頃六糸No.２
  static ct_strField35;  // 【ジャケット】前身頃六糸No.３
  static ct_strField36;  // 【ジャケット】前身頃六糸No.４
  static ct_strField37;  // 【素材】生地柄
  static ct_strField38;  // 【ジャケット】袖口六糸No.1
  static ct_strField39;  // 【ジャケット】袖口六糸No.２
  static ct_strField40;  // 【ジャケット】袖口六糸No.３
  static ct_strField41;  // 【ジャケット】袖口六糸No.４
  static ct_strField42;  //  工場名
  static ct_strField43;  // 【パンツ】タックNo.
  static ct_strField44;  // 【パンツ】横P
  static ct_strField45;  // 【パンツ】１右
  static ct_strField46;  // 【パンツ】１左
  static ct_strField47;  // 【パンツ】２右
  static ct_strField48;  // 【パンツ】２左
  static ct_strField49;  // 【パンツ】モデル選択肢
  static ct_strField50;  // 【パンツ】パンツ種類
  static ct_strField51;  // 【パンツ】モデル
  static ct_strField52;  // 【パンツ】サイズ
  static ct_strField53;  // 【パンツ】糸No.
  static ct_strField54;  // 【パンツ】ループNo.
  static ct_strField55;  // 【ベスト】前釦個数
  static ct_strField56;  // 【ベスト】前釦サイズ
  static ct_strField57;  // 【ベスト】衿付
  static ct_strField58;  // 【ベスト】胸P
  static ct_strField59;  // 【ベスト】腰P
  static ct_strField60;  // 【ベスト】尾錠有無
  static ct_strField61;  // 【ベスト】尾錠
  static ct_strField62;  // 【ベスト】ステッチ
  static ct_strField63;  // 【ベスト】裏地持工
  static ct_strField64;  // 【ベスト】ボタン持工
  static ct_strField65;  // 【ベスト】裏地
  static ct_strField66;  // 【ベスト】ボタン
  static ct_strField67;  // ゲージ服
  static ct_strField68;  // ゲージ服
  static ct_strField69;  // 仮縫い体
  static ct_strField70;  // 仮縫い体
  static ct_strField71;  // 完成品
  static ct_strField72;  // 完成品
  static ct_strField73;  // 商品販売管理
  static ct_strField74;  // ネーム
  static ct_strField75;  // ネーム糸色
  static ct_strField76;  // ネーム字体
  static ct_strField77;  // ネーム位置
  static ct_strField78;  // 顧客名
  static ct_strField79;  // 【素材】生地No
  static ct_strField80;  // 裁縫について
*/
?>
