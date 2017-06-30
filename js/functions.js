//////////////////////////////////////////////////
// 各種設定(お好みで変更して頂いて構いません)
var dataPerPage = 30;      // 一覧ページのページ当たりのデータ件数
//////////////////////////////////////////////////
// 各種設定(システム共通：変更時要注意)
var siteUrl = "http://153.121.60.215/management";
var dataAttrName = 'data-page-number';
//////////////////////////////////////////////////
// 顧客名取得
function getCustomerNameList() {
  var prgName = "顧客名取得";
  var apiName = "customer_list.php";
  $("#strField78").autocomplete({
    source: function(request, response){
      $.ajax({
        url:  siteUrl + "/" + apiName,
        type: "POST",
        dataType: "json",
        data: {
          param1: request.term,
        },
        success: function (data) {
          //console.log("autocompletedata",mode);
          response($.map(data, function (item) {
            var row = {};
            row.label        = item.customer_name ? item.customer_name : '';
            row.customer_id  = item.customer_id ? item.customer_id : '';
            row.company_name = item.company_name ? item.company_name : '';
            row.charge_id    = item.charge_id ? item.charge_id : '';
            var height       = parseInt(item.height,10) ? parseInt(item.height,10) : '';
            row.height       = height;
            var weight       = parseInt(item.weight,10) ? parseInt(item.weight,10) : '';
            row.weight       = weight;
            return row
          }));
        },
        error: function(xhr, ts, err){
          response(['']);
        }
      });
    },
    select: function (event, ui) {
      // console.log("autocomplete_item",ui.item);
      $("#strField78").val(dec(ui.item.label)).attr({"data-customer_name":ui.item.label,"data-customer_id":ui.item.customer_id});
      $("#relField03").val(ui.item.charge_id);
      $("#company_name").val(dec(ui.item.company_name)).attr("data-company_name",ui.item.company_name);
      $("#numField52").val(ui.item.height);
      $("#numField53").val(ui.item.weight);
      return false;
    },
    delay: 500
  });
  // $.ajax({
  //   type: "GET",
  //   url:  siteUrl + "/" + apiName,
  //   data: {},
  //   success: function(data) {
  //     data = $.parseJSON(JSON.stringify(data));
  //
  //     // 顧客サジェスト
  //     var customerList = [];
  //
  //     var dataLength = data.length;
  //     for (var iCnt = 0; iCnt < dataLength; iCnt ++) {
  //       customerList.push(data[iCnt].STR_FIELD02);
  //     }
  //
  //     // 顧客名サジェスド
  //     $("#strField78").autocomplete({
  //       source: customerList
  //     }).prop('autocomplete', 'off');
  //
  //   },
  //   error: function() {
  //     alert('JSONデータ取得エラー：' + prgName);
  //
  //   },
  //   complete: function() {
  //
  //   }
  // });
}


//////////////////////////////////////////////////
// 担当者取得
// function getStaffList() {
//   var prgName = "担当者取得";
//   var apiName = "contact_list.php";
//
//   $.ajax({
//     type: "GET",
//     url:  siteUrl + "/" + apiName,
//     data: {},
//     success: function(data) {
//       data = $.parseJSON(JSON.stringify(data));
//
//       var dataLength = data.length;
//       var a = [];
//       for (var iCnt = 0; iCnt < dataLength; iCnt ++) {
//         a.push('<option value="' + data[iCnt].ID + '">' + data[iCnt].STR_FIELD01 + '</option>');
//         // $('#relField03').append('<option value="' + data[iCnt].ID + '">' + data[iCnt].STR_FIELD01 + '</option>');
//       }
//       $('#relField03,#measuring').append(a.join(''));
//     },
//     error: function() {
//       alert('JSONデータ取得エラー：' + prgName);
//
//     },
//     complete: function() {
//     }
//   });
// }


//////////////////////////////////////////////////
// 受注リスト取得
function getOrderList() {
  var prgName = "受注リスト取得";
  var apiName = "order_list.php";

  $.ajax({
    type: "POST",
    url:  siteUrl + "/" + apiName,
    data: {},
    success: function(data) {
      data = $.parseJSON(JSON.stringify(data));
      var dataLength = data.length;

      var page = parseInt($('#order-list-table').attr('data-page-number') === undefined ? 0 : $('#order-list-table').attr('data-page-number'));

      for (var iCnt = ((page - 1) * dataPerPage); iCnt < dataLength; iCnt ++) {
        if (iCnt >= ((page - 1) * dataPerPage + dataPerPage)) {
          break;
        }

        var listTemplate = $('#order-list-template').html();
        var visits_date  = data[iCnt].visits_date ? getDate(data[iCnt].visits_date) : '';
        listTemplate     = listTemplate.replace(/\{\{ID\}\}/g, data[iCnt].ID);                                    // ID
        listTemplate     = listTemplate.replace(/\{\{visits_date\}\}/g, visits_date);                             // 来店日
        listTemplate     = listTemplate.replace(/\{\{customer_name\}\}/g, data[iCnt].customer_name);              // 顧客名
        listTemplate     = listTemplate.replace(/\{\{item_name\}\}/g, data[iCnt].item_name);                      // 商品名
        listTemplate     = listTemplate.replace(/\{\{price\}\}/g, '&yen; ' + addComma(data[iCnt].price));         // 金額
        listTemplate     = listTemplate.replace(/\{\{measuring_name\}\}/g, data[iCnt].measuring_name);            // 採寸担当
        listTemplate     = listTemplate.replace(/\{\{charge_name\}\}/g, data[iCnt].charge_name);                  // 営業担当
        listTemplate     = listTemplate.replace(/\{\{rj_num\}\}/g, data[iCnt].rj_num);                            // RJ

        listTemplate     = listTemplate.replace(/\{\{.*?\}\}/g, '');                                              // それ以外
        $('#order-list-table > tbody').append(listTemplate);
      }

      // ページネーション出力
      outputPagenation($('[' + dataAttrName + ']').attr(dataAttrName), Math.ceil(dataLength / dataPerPage));

    },
    error: function() {
      alert('JSONデータ取得エラー：' + prgName);
    },
    complete: function() {

    }
  });
}


//////////////////////////////////////////////////
// 受注詳細取得
function getOrderDetail(order_number) {
  var prgName = "受注詳細取得";
  var apiName = "order_detail.php";

  $.ajax({
    type: "POST",
    url:  siteUrl + "/" + apiName,
    data: {
      "order_id" : order_number
    },
    success: function(data) {
      data = $.parseJSON(JSON.stringify(data));
      // 基本情報
      // $('#ctId').attr("data-sales08_id",data.sales08_id);
      $('#company_name').val(dec(data.company_name)).change();
      $('#dateField01').val(moment(data.DATE_FIELD01).format('YYYY/MM/DD')).attr("readonly",true);
      $('#measuring').val(data.REL_FIELD03).change();
      $('#numField01').val(parseInt(data.NUM_FIELD01,10));
      $('#numField52').val(parseInt(data.NUM_FIELD52,10));
      $('#numField53').val(parseInt(data.NUM_FIELD53,10));
      $('#relField02').val(data.REL_FIELD02).change();
      $('#item_input_name').val(dec(data.STR_FIELD12));
      $('#relField03').val(data.charge_id).change();
      $('#strField78').val(dec(data.customer_name))
        .attr({
          "readonly"           : true,
          "data-customer_id"   : data.customer_id,
          "data-customer_name" : data.customer_name
        });
    },
    error: function() {
      alert('JSONデータ取得エラー：' + prgName);
    },
    complete: function() {

    }
  });
}


//////////////////////////////////////////////////
// 顧客リスト取得
function getCustomerList() {
  var prgName = "顧客リスト取得";
  var apiName = "customer_list.php";

  $.ajax({
    type: "POST",
    url:  siteUrl + "/" + apiName,
    data: {"mode":"list"},
    success: function(data) {
      data = $.parseJSON(JSON.stringify(data));
      var dataLength = data.length;

      var page = parseInt($('#customer-list-table').attr('data-page-number') === undefined ? 0 : $('#customer-list-table').attr('data-page-number'));

      for (var iCnt = ((page - 1) * dataPerPage); iCnt < dataLength; iCnt ++) {
        if (iCnt >= ((page - 1) * dataPerPage + dataPerPage)) {
          break;
        }
        var listTemplate = $('#customer-list-template').html();
        listTemplate = listTemplate.replace(/\{\{STR_FIELD01\}\}/g, data[iCnt].STR_FIELD01);            // ID
        listTemplate = listTemplate.replace(/\{\{STR_FIELD02\}\}/g, data[iCnt].STR_FIELD02);            // 顧客名
        listTemplate = listTemplate.replace(/\{\{STR_FIELD07\}\}/g, data[iCnt].STR_FIELD07);            // 顧客名
        listTemplate = listTemplate.replace(/\{\{charge_name\}\}/g, data[iCnt].charge_name);            // 営業担当
        listTemplate = listTemplate.replace(/\{\{.*?\}\}/g, '');                          // それ以外
        $('#customer-list-table > tbody').append(listTemplate);
      }

      // ページネーション出力
      outputPagenation($('[' + dataAttrName + ']').attr(dataAttrName), Math.ceil(dataLength / dataPerPage));

    },
    error: function() {
      alert('JSONデータ取得エラー：' + prgName);
    },
    complete: function() {

    }
  });
}


//////////////////////////////////////////////////
// 顧客詳細取得
// function getCustomerDetail(customer_id) {
//   var prgName = "顧客詳細取得";
//   var apiName = "customer_detail.php";
//
//   $.ajax({
//     type: "GET",
//     url:  siteUrl + "/" + apiName,
//     data: {
//       "customer_id" : customer_id
//     },
//     success: function(data) {
//       data = $.parseJSON(JSON.stringify(data));
//
//
//     },
//     error: function() {
//       alert('JSONデータ取得エラー：' + prgName);
//     },
//     complete: function() {
//
//     }
//   });
// }

//////////////////////////////////////////////////
// 受注フォームサブミット
function orderSubmit() {
  var prgName         = "受注詳細サブミット";
  var msg             = '';
  var order_id        = $('#ctId').val();
  // var sales08_id    = $('#ctId').attr("data-sales08_id");
  var visits_date     = $('#dateField01').val();
  var measuring_id    = $('#measuring').val();
  var company_name    = $('#company_name').val();
  var price           = parseInt($('#numField01').val(),10);
  var height          = parseInt($('#numField52').val(),10);
  var weight          = parseInt($('#numField53').val(),10);
  var item_id         = $('#relField02').val();
  var item_name       = $('#relField02 > option:selected').attr("data-item_name");
  var item_input_name = $('#item_input_name').val();
  var charge_id       = $('#relField03').val();
  var charge_name     = $('#relField03 > option:selected').attr("data-contact_name");
  var customer_id     = $('#strField78').attr("data-customer_id");
  var customer_name   = $('#strField78').attr("data-customer_name");
  var apiName         = order_id ? "order_update.php" : "order_new.php";
  var formats         = ["YYYYMMDD", "YYYY-MM-DD", "YYYY/MM/DD"];
  if (!visits_date) {
    msg += '※来店日が入力されていません\n';
  } else if (!moment(visits_date,formats,true).isValid()) {
    msg += '※来店日が正しくありません\n';
  }
  if (!customer_id) {
    msg += '※お客様を選択して下さい\n';
  }
  if (!item_id) {
    msg += '※商品を選択して下さい\n';
  }
  if (!charge_id) {
    msg += '※担当者を選択して下さい\n';
  }
  if (!measuring_id) {
    msg += '※採寸者を選択して下さい\n';
  }
  if (isNaN(price)) {
    msg += '※正しい金額が入力されていません\n';
  }
  if (isNaN(height)) {
    msg += '※正しい身長が入力されていません\n';
  }
  if (isNaN(weight)) {
    msg += '※正しい体重が入力されていません\n';
  }
  if (msg) {
    alert(msg);
    return false;
  }
  if (!confirm('保存してよろしいですか？')) {
    return false;
  }
  $('#submit_button').attr('disabled',true);

  $.ajax({
    type: "POST",
    url:  siteUrl + "/" + apiName,
    data: {
      "order_id"        : order_id,
    // "sales08_id"    : sales08_id,
      "company_name"    : company_name,
      "visits_date"     : visits_date,
      "measuring_id"    : measuring_id,
      "price"           : price,
      "height"          : height,
      "weight"          : weight,
      "item_id"         : item_id,
      "item_name"       : item_name,
      "item_input_name" : item_input_name,
      "charge_id"       : charge_id,
      "charge_name"     : charge_name,
      "customer_id"     : customer_id,
      "customer_name"   : customer_name,
    },
    success: function(data) {
      data = $.parseJSON(JSON.stringify(data));

    },
    error: function() {
      alert('JSONデータ取得エラー：' + prgName);
    },
    complete: function() {
      location.href = siteUrl;
    }
  });
}

//////////////////////////////////////////////////
// ページネーション出力
function outputPagenation(inCurrentPageNum, inMaxPageNum) {
  for (var iCnt = 1; iCnt <= inMaxPageNum; iCnt ++) {
    var currentClassStr = iCnt == inCurrentPageNum ? 'class="active"' : '';
    $('.pagination').append('<li ' + currentClassStr + '><a href="#" onclick="movePage(\'' + iCnt + '\');">' + iCnt + '</a></li>');
  }
}

//////////////////////////////////////////////////
// ページ移動
function movePage(pageNum) {
  $('.pagination').prev('form').find('[name=page]').val(pageNum);
  $('.pagination').prev('form').submit();
}


//////////////////////////////////////////////////
// 日付整形
function getDate(inDateTimeString) {
  return inDateTimeString.substr(0, 10).replace(/-/g, '/');
}


//////////////////////////////////////////////////
// 金額整形
function addComma(inIntNumber) {
  var num = (parseInt(inIntNumber) + "").replace(/,/g, "");
  while (num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
  return num;
}


function dec(str) {
	var div = document.createElement("div");
	div.innerHTML = str.replace(/</g,"&lt;")
	                   .replace(/>/g,"&gt;")
	                  // .replace(/ /g, "&nbsp;")
	                   .replace(/\r/g, "&#13;")
	                   .replace(/\n/g, "&#10;");
	return div.textContent || div.innerText;
}

function validate_Num(elem){
  var num = $(elem).val();
  //半角数字以外があったら
  if(num.match(/[^0-9]+/gi)) {
    //空白に置き換え
    num = $(elem).val().replace(/[^0-9]+/gi,'');
  }
  //数値型にする、頭に0が入っていたらここで消される　10は10進数のこと
  num = num ? parseInt(num, 10) : '';
  $(elem).val(num);
}
//////////////////////////////////////////////////
// ページパラメータ取得
// function getPageParam() {
//   var page = getParam('page');
//   page = page === '' ? 1 : page;
//   $('[' + dataAttrName + ']').attr(dataAttrName, page);
// }


//////////////////////////////////////////////////
// パラメータ取得
function getParam(inParamKey) {
  var paramStr = decodeURIComponent(location.search.replace(/\?/, ''));
  var params = paramStr.split('&');

  for (var iCnt = 0; iCnt < params.length; iCnt ++) {
    var kvArray = params[iCnt].split('=');
    if (kvArray[0] == inParamKey) {
      return kvArray[1];
    }
  }
  return '';
}


// //////////////////////////////////////////////////
// // 受注フォーム表示/非表示ハンドリング
// function transformHandring() {
//   if ($('[name="relField02"]:checked').length <= 0) {
//     return false;
//   }
//
//   var selected_category = $('[name="relField02"]:checked').attr('data-section-id');
//   var showElems = "";
//   var hideElems = "";
//
//   if (selected_category == 'mens') {
//     showElems = $('#basices, #jacket, #pants, #vest');
//     hideElems = $('#skirt, #coat');
//
//   } else if (selected_category == 'ladies') {
//     showElems = $('#basics, #jacket, #pants, #skirt');
//     hideElems = $('#vest, #coat');
//
//   } else if (selected_category == 'coat') {
//     showElems = $('#basics, #coat');
//     hideElems = $('#jacket, #pants, #skirt, #vest');
//
//   } else if (selected_category == 'shirts') {
//     showElems = $('#basics');
//     hideElems = $('#jacket, #pants, #skirt, #vest, #coat');
//   }
//
//   // フォーム表示/非表示
//   transformOrderForm(showElems, hideElems);
// }
//
// //////////////////////////////////////////////////
// // 受注フォーム表示/非表示
// function transformOrderForm(showElems, hideElems) {
//   var onName = "valCheck".toLowerCase();
//   var offName = "DisabledValCheck".toLowerCase();
//
//   showElems
//     .find('[' + offName + ']').each(function() {
//       $(this).attr(onName, $(this).attr(offName));
//       $(this).removeAttr(offName);
//     }).end()
//     .find('.form-group').removeClass('has-error');
//
//   hideElems
//     .find('[' + onName + ']').each(function() {
//       $(this).attr(offName, $(this).attr(onName));
//       $(this).removeAttr(onName);
//     }).end()
//     .find('.form-group').removeClass('has-error');
//
//   showElems.slideDown();
//   hideElems.slideUp();
// }
