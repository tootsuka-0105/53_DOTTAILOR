<?php
  require_once('function.php');
  error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
  $order_id        = post('order_id');
  // $sales08_id    = post('sales08_id');
  $visits_date     = post('visits_date');
  $item_id         = post('item_id');
  $item_name       = post('item_name');
  $item_input_name = post('item_input_name');
  $price           = (int)post('price',0);
  $charge_id       = post('charge_id');
  // $charge_name   = post('charge_name');
  $measuring_id    = post('measuring_id');
  $company_name    = post('company_name');
  $customer_id     = post('customer_id');
  // $customer_name = post('customer_name');
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

  //商品販売管理テーブル　登録
	$data = '{"prtId":"'.$order_id.'",';
  $data .= '"entityId":"prtData09"';
  //文字列系
  $data .= ',"strField02":';
  $data .= '"'.$item_name.'"';
  $data .= ',"strField12":';
  $data .= '"'.$item_input_name.'"';
  //数字系
  $data .= ',"numField01":';
  $data .= '"'.$price.'"';
  $data .= ',"numField52":';
  $data .= '"'.$height.'"';
  $data .= ',"numField53":';
  $data .= '"'.$weight.'"';
  //取得したID
  $data .= ',"relField02":';
  $data .= '"'.$item_id.'"';
  $data .= ',"relField03":';
  $data .= '"'.$measuring_id.'"';
  //最後
  $data .= '}';
  $data = preg_replace("/( |　)/", "", $data );
  $result = sakusakun($data,'update');
?>
