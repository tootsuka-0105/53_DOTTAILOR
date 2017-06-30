<?php
//////////////////////////////////////////////////
// 受注一覧
function order_list($posted) {
  $pageNum = isset($_POST['page']) ? $_POST['page'] : 1;
  headerHtml('受注一覧');
  echo <<< EOS
      <h3 class="clearfix">
        <i class="fa fa-fw fa-list"></i> 受注一覧
        <form action="./" method="POST" class="pull-right"><button type="submit" class="btn btn-sm btn-info" name="c" value="order_new"><i class="fa fa-fw fa-plus-circle"></i> 新規登録</button></form>
      </h3>

      <div class="table-responsive">
        <table class="table table-striped" id="order-list-table" data-page-number="$pageNum">
          <thead>
            <tr>
              <th>操作</th>
              <th>来店日</th>
              <th>顧客名</th>
              <th>商品名</th>
              <th>金額</th>
              <th>採寸担当</th>
              <th>営業担当</th>
              <th>RJ</th>
            </tr>
          </thead>
          <tbody>
            <script type="text/x-template" id="order-list-template">
            <tr data-order-id="{{ID}}">
              <td>
                <form action="./" method="POST">
                  <input type="hidden" name="order_number" value="{{ID}}">
                  <button type="submit" class="btn btn-xs btn-warning" name="c" value="order_edit"><i class="fa fa-fw fa-check-circle"></i> 編集</button><br class="visible-xs">
                </form>
              </td>
              <td>{{visits_date}}</td>
              <td>{{customer_name}}</td>
              <td>{{item_name}}</td>
              <td>{{price}}</td>
              <td>{{measuring_name}}</td>
              <td>{{charge_name}}</td>
              <td>{{rj_num}}</td>
            </tr>
            </script>

          </tbody>
        </table>
      </div>

      <div class="text-center">
        <form action="./" method="POST" class="hidden-xs hidden-sm hidden-md hidden-lg">
          <input type="hidden" name="c" value="{$posted}">
          <input type="hidden" name="page">
        </form>

        <ul class="pagination">

        </ul>
      </div>
EOS;

  $jsScript = <<< EOS
    <script>
    // Fire when the DOM is built
    $(document).ready(function() {
      // 受注リスト取得
      getOrderList();
    });
    </script>
EOS;
  footerHtml($jsScript);
}
//////////////////////////////////////////////////
// 受注詳細
function order_detail($posted='') {
  require('contact_list.php'); //担当者リスト取得
  require('item_list.php'); //商品リスト取得
  if ($posted === 'order_new') {
    $pageTitle = "登録";
  } elseif ($posted === 'order_edit') {
    $pageTitle = "編集";
  } else {
    erroMessage('処理エラー');
  }
  $order_number = isset($_POST['order_number']) ? $_POST['order_number'] : '';
  headerHtml('受注' . $pageTitle);
  echo <<< EOS
      <h3><i class="fa fa-fw fa-shopping-cart"></i> 受注{$pageTitle}</h3>
      <!--
      <p class="text-danger">※ログインAPIが正しく動いていないので担当者名と顧客名サジェストは動きません。<br>　<a href="http://153.121.60.215/management/contact_list.php" target="_blank">ここ</a>、<a href="http://153.121.60.215/management/customer_list.php" target="_blank">ここ</a>をクリックするとAPIの戻り値を確認できます。</p>
      -->
<form action="javascript:orderSubmit();" method="POST" class="form-horizontal form-striped" id="order-form">
  <input type="hidden" name="ctId" id="ctId" value="{$order_number}">

  <div id="basics">
    <h4 class="bg-green">【基本情報】</h4>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="dateField01" class="control-label col-sm-4">来店日</label>
          <div class="col-sm-8">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></span>
              <input type="text" class="form-control datepicker" name="dateField01" id="dateField01" valCheck="required">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="strField78" class="control-label col-sm-4">お客様名</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="strField78" id="strField78" valCheck="required">
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group" style="height:initial">
          <label for="relField02" class="control-label col-sm-2">商品名</label>
          <div class="col-sm-8">
            <select class="form-control" name="relField02" id="relField02" valCheck="required select">
              <option>選択してください</option>
              {$item_option}
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="company_name" class="control-label col-sm-4">会社名</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="company_name" id="company_name" valCheck="required">
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="item_input_name" class="control-label col-sm-2">品名</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="item_input_name" id="item_input_name" valCheck="required">
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="numField52" class="control-label col-sm-4">身長</label>
          <div class="col-sm-8">
            <div class="input-group">
              <input type="text" class="form-control text-right ime-off ime-off" name="numField52" id="numField52" valCheck="required">
              <span class="input-group-addon">cm</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="numField01" class="control-label col-sm-2">金額</label>
          <div class="col-sm-8">
            <div class="input-group">
              <input type="text" class="form-control text-right ime-off ime-off" name="numField01" id="numField01" valCheck="required">
              <span class="input-group-addon">円</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="numField53" class="control-label col-sm-4">体重</label>
          <div class="col-sm-8">
            <div class="input-group">
              <input type="text" class="form-control text-right ime-off ime-off" name="numField53" id="numField53" valCheck="required">
              <span class="input-group-addon">kg</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="measuring" class="control-label col-sm-2">採寸者名</label>
          <div class="col-sm-8">
            <select class="form-control" name="measuring" id="measuring" valCheck="required select">
              <option>選択してください</option>
              {$contact_option}
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="relField03" class="control-label col-sm-4">担当者名</label>
          <div class="col-sm-8">
            <select class="form-control" name="relField03" id="relField03" valCheck="required select">
              <option>選択してください</option>
              {$contact_option}
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--// #basics-->
  <div class="row mt-3x">
    <div class="col-sm-12">
      <div class="form-group text-center">
        <div class="col-sm-12">
          <button type="submit" id="submit_button" class="btn btn-primary" valCheck="submit">$pageTitle</button>
        </div>
      </div>
    </div>
  </div>
</form>
EOS;
  $jsScript = <<< EOS
    <script type="text/javascript" charset="UTF-8" src="js/moment-with-locales.min.js"></script>
    <script type="text/javascript" charset="UTF-8" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" charset="UTF-8" src="js/bootstrap-datetimepicker.min.js"></script>
    <script>
    $(document).ready(function() {
      // フォームバリデーションチェック
      $('#order-form').formValidation({'addIcon': 'false'});
      $(document).on("change","#numField01,#numField52,#numField53",function(){
        validate_Num(this);
      });
EOS;
  // 新規
  if ($posted == 'order_new') {
    $jsScript .= <<< EOS

      var m = moment();
      $('#dateField01').datetimepicker({
        format: "YYYY/MM/DD",
        dayViewHeaderFormat: "YYYY年 MMMM",
        useCurrent: true,
        maxDate: m,
        locale: "ja",
        icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-arrow-up",
          down: "fa fa-arrow-down"
        }
      });
      // 当日日付
      $('#dateField01').val(m.format('YYYY/MM/DD'));
      // 顧客名取得
      getCustomerNameList();
    });
    </script>
EOS;
  // 更新
  } else if ($posted == 'order_edit') {
    $jsScript .= <<< EOS
      // 受注詳細取得
      getOrderDetail({$order_number});
    });
    </script>
EOS;
  }
  footerHtml($jsScript);
}
?>
