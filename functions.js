//////////////////////////////////////////////////
// 各種設定(お好みで変更して頂いて構いません)
var dataPerPage = 1000;			// 一覧ページのページ当たりのデータ件数


//////////////////////////////////////////////////
// 各種設定(システム共通：変更時要注意)
var siteUrl = "http://153.121.60.215";
var dataAttrName = 'data-page-number';


//////////////////////////////////////////////////


//////////////////////////////////////////////////
// 顧客名取得
function getCustomerNameList() {
	var prgName = "顧客名取得";
	var apiName = "customer_list.php";

	$.ajax({
		type: "GET",
		url:  siteUrl + "/" + apiName,
		data: {},
		success: function(data) {
			data = $.parseJSON(JSON.stringify(data));

			// 顧客サジェスト
			var customerList = [];

			var dataLength = data.length;
			for (var iCnt = 0; iCnt < dataLength; iCnt ++) {
				customerList.push(data[iCnt].STR_FIELD02);
			}

			// 顧客名サジェスド
			$("#strField78").autocomplete({
				source: customerList
			}).prop('autocomplete', 'off');

		},
		error: function() {
			alert('JSONデータ取得エラー：' + prgName);

		},
		complete: function() {

		}
	});
}


//////////////////////////////////////////////////
// 担当者取得
function getStaffList() {
	var prgName = "担当者取得";
	var apiName = "contact_list.php";

	$.ajax({
		type: "GET",
		url:  siteUrl + "/" + apiName,
		data: {},
		success: function(data) {
			data = $.parseJSON(JSON.stringify(data));

			var dataLength = data.length;
			for (var iCnt = 0; iCnt < dataLength; iCnt ++) {
				$('#relField03').append('<option value="' + data[iCnt].ID + '">' + data[iCnt].STR_FIELD01 + '</option>');
			}
		},
		error: function() {
			alert('JSONデータ取得エラー：' + prgName);

		},
		complete: function() {

		}
	});
}


//////////////////////////////////////////////////
// 受注リスト取得
function getOrderList() {
	var prgName = "受注リスト取得";
	var apiName = "order_list.php";

	$.ajax({
		type: "GET",
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

				listTemplate = listTemplate.replace(/\{\{ID\}\}/g, data[iCnt].ID);											// ID
				listTemplate = listTemplate.replace(/\{\{DATE_FIELD01\}\}/g, getDate(data[iCnt].DATE_FIELD01));				// 来店日
				listTemplate = listTemplate.replace(/\{\{STR_FIELD02_01\}\}/g, data[iCnt].STR_FIELD02_01);					// 顧客名
				listTemplate = listTemplate.replace(/\{\{STR_FIELD02_02\}\}/g, data[iCnt].STR_FIELD02_02);					// 商品名
				listTemplate = listTemplate.replace(/\{\{NUM_FIELD01\}\}/g, '&yen; ' + addComma(data[iCnt].NUM_FIELD01));	// 金額
				listTemplate = listTemplate.replace(/\{\{STR_FIELD01\}\}/g, data[iCnt].STR_FIELD01);						// 採寸担当
				listTemplate = listTemplate.replace(/\{\{STR_FIELD73\}\}/g, data[iCnt].STR_FIELD01);						// 営業担当
				listTemplate = listTemplate.replace(/\{\{STR_FIELD02_03\}\}/g, data[iCnt].STR_FIELD01);						// RJ

				listTemplate = listTemplate.replace(/\{\{.*?\}\}/g, '');													// それ以外
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
		type: "GET",
		url:  siteUrl + "/" + apiName,
		data: {
			"order_id" : order_number
		},
		success: function(data) {
			data = $.parseJSON(JSON.stringify(data));

			// 基本情報
			$('[name="relField02"][value="' + data[0].STR_FIELD02 + '"]').prop("checked", true);
			$('#dateField01').val(moment(data[0].DATE_FIELD01).format('YYYY/MM/DD'));
			$('#relField03 > option[value="' + data[0].REL_FIELD03_01 + '"]').prop("selected", true);
			$('#strField78').val(data[0].STR_FIELD78);
			$('#strField33').val(data[0].STR_FIELD33);
			$('#numField52').val(data[0].NUM_FIELD52);
			$('#numField51').val(data[0].NUM_FIELD51);
			$('#relField03').val(data[0].REL_FIELD03);
			$('#strField08').val(data[0].STR_FIELD08);
			$('#numField53').val(data[0].NUM_FIELD53);

			$('#strField79').val(data[0].STR_FIELD79);
			$('[name="strField05"][value="' + data[0].STR_FIELD05 + '"]').prop("checked", true);
			$('#strField13').val(data[0].STR_FIELD13);
			$('#strField37').val(data[0].STR_FIELD37);
			$('#strField10').val(data[0].STR_FIELD10);
			$('[name="strField08"][value="' + data[0].STR_FIELD08 + '"]').prop("checked", true);
			$('#numField39').val(data[0].NUM_FIELD39);
			$('#numField03').val(data[0].NUM_FIELD03);
			$('#numField09').val(data[0].NUM_FIELD09);
			$('#strField12').val(data[0].STR_FIELD12);
			$('#numField12').val(data[0].NUM_FIELD12);
			$('#strField07').val(data[0].STR_FIELD07);
			$('#strField11').val(data[0].STR_FIELD11);
			$('[name="strField09"][value="' + data[0].STR_FIELD09 + '"]').prop("checked", true);
			$('#numField40').val(data[0].NUM_FIELD40);
			$('#numField08').val(data[0].NUM_FIELD08);
			$('#numField01').val(data[0].NUM_FIELD01);

			// ジャケット
			$('#strField14').val(data[0].STR_FIELD14);
			$('#strField19').val(data[0].STR_FIELD19);
			$('#strField18').val(data[0].STR_FIELD18);

			// パンツ
			$('#numField19').val(data[0].NUM_FIELD19);
			$('#numField23').val(data[0].NUM_FIELD23);
			$('#strField50').val(data[0].STR_FIELD50);
			$('#numField30').val(data[0].NUM_FIELD30);
			$('#numField22').val(data[0].NUM_FIELD22);
			$('#numField27').val(data[0].NUM_FIELD27);

			// ベスト
			$('#strField65').val(data[0].STR_FIELD65);
			$('[name="strField63"][value="' + data[0].STR_FIELD63 + '"]').prop("checked", true);
			$('#numField41').val(data[0].NUM_FIELD41);
			$('[name="strField61"][value="' + data[0].STR_FIELD61 + '"]').prop("checked", true);
			$('#strField66').val(data[0].STR_FIELD66);
			$('[name="strField64"][value="' + data[0].STR_FIELD64 + '"]').prop("checked", true);
			$('#numField42').val(data[0].NUM_FIELD42);

			// スカート
			$('#numField46').val(data[0].NUM_FIELD46);
			$('#numField48').val(data[0].NUM_FIELD48);
			$('#numField47').val(data[0].NUM_FIELD47);
			$('#numField49').val(data[0].NUM_FIELD49);

			// コート
			$('#numField19').val(data[0].NUM_FIELD19);
			$('#numField43').val(data[0].NUM_FIELD43);
			$('#strField42').val(data[0].STR_FIELD42);
			$('#numField27').val(data[0].NUM_FIELD27);
			$('#numField44').val(data[0].NUM_FIELD44);
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
		type: "GET",
		url:  siteUrl + "/" + apiName,
		data: {},
		success: function(data) {
			data = $.parseJSON(JSON.stringify(data));
			var dataLength = data.length;

			var page = parseInt($('#customer-list-table').attr('data-page-number') === undefined ? 0 : $('#customer-list-table').attr('data-page-number'));

			for (var iCnt = ((page - 1) * dataPerPage); iCnt < dataLength; iCnt ++) {
				if (iCnt >= ((page - 1) * dataPerPage + dataPerPage)) {
					break;
				}

				var listTemplate = $('#customer-list-template').html();

				listTemplate = listTemplate.replace(/\{\{ID\}\}/g, data[iCnt].ID);											// ID
				listTemplate = listTemplate.replace(/\{\{STR_FIELD02\}\}/g, data[iCnt].STR_FIELD02);						// 顧客名
				listTemplate = listTemplate.replace(/\{\{STR_FIELD07\}\}/g, data[iCnt].STR_FIELD07);						// 顧客名
				listTemplate = listTemplate.replace(/\{\{STR_FIELD01\}\}/g, data[iCnt].STR_FIELD01);						// 営業担当


				listTemplate = listTemplate.replace(/\{\{.*?\}\}/g, '');													// それ以外
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
function getCustomerDetail(customer_id) {
	var prgName = "顧客詳細取得";
	var apiName = "customer_detail.php";

	$.ajax({
		type: "GET",
		url:  siteUrl + "/" + apiName,
		data: {
			"customer_id" : customer_id
		},
		success: function(data) {
			data = $.parseJSON(JSON.stringify(data));


		},
		error: function() {
			alert('JSONデータ取得エラー：' + prgName);
		},
		complete: function() {

		}
	});
}


//////////////////////////////////////////////////
// ページネーション出力
function outputPagenation(inCurrentPageNum, inMaxPageNum) {
	for (var iCnt = 1; iCnt <= inMaxPageNum; iCnt ++) {
		var currentClassStr = iCnt == inCurrentPageNum ? 'class="active"' : '';
		$('.pagination').append('<li ' + currentClassStr + '><a href="#" onclick="movePage(\'' + iCnt + '\');">' + iCnt + '</a></li>')
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


//////////////////////////////////////////////////
// ページパラメータ取得
function getPageParam() {
	var page = getParam('page');
	page = page === '' ? 1 : page;
	$('[' + dataAttrName + ']').attr(dataAttrName, page);
}


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


//////////////////////////////////////////////////
// 受注フォーム表示/非表示ハンドリング
function transformHandring() {
	if ($('[name="relField02"]:checked').length <= 0) {
		return false;
	}

	var selected_category = $('[name="relField02"]:checked').attr('data-section-id');
	var showElems = "";
	var hideElems = "";

	if (selected_category == 'mens') {
		showElems = $('#basices, #jacket, #pants, #vest');
		hideElems = $('#skirt, #coat');

	} else if (selected_category == 'ladies') {
		showElems = $('#basics, #jacket, #pants, #skirt');
		hideElems = $('#vest, #coat');

	} else if (selected_category == 'coat') {
		showElems = $('#basics, #coat');
		hideElems = $('#jacket, #pants, #skirt, #vest');

	} else if (selected_category == 'shirts') {
		showElems = $('#basics');
		hideElems = $('#jacket, #pants, #skirt, #vest, #coat');
	}

	// フォーム表示/非表示
	transformOrderForm(showElems, hideElems);
}

//////////////////////////////////////////////////
// 受注フォーム表示/非表示
function transformOrderForm(showElems, hideElems) {
	var onName = "valCheck".toLowerCase();
	var offName = "DisabledValCheck".toLowerCase();

	showElems
		.find('[' + offName + ']').each(function() {
			$(this).attr(onName, $(this).attr(offName));
			$(this).removeAttr(offName);
		}).end()
		.find('.form-group').removeClass('has-error');

	hideElems
		.find('[' + onName + ']').each(function() {
			$(this).attr(offName, $(this).attr(onName));
			$(this).removeAttr(onName);
		}).end()
		.find('.form-group').removeClass('has-error');

	showElems.slideDown();
	hideElems.slideUp();
}


//////////////////////////////////////////////////
// 受注フォームサブミット
function orderSubmit() {
	var prgName = "受注詳細サブミット";
	var apiName = "";
	var orderNum = $('#ctId').val();
	var formParams = $('#order-form').serialize();

	// 新規
	if (orderNum == "") {
		apiName = "order_new.php";
	// 編集
	} else {
		apiName = "order_update.php";
	}

	$.ajax({
		type: "GET",
		url:  siteUrl + "/" + apiName + "?" + formParams,
		data: {},
		success: function(data) {
			data = $.parseJSON(JSON.stringify(data));

			console.log(data);

		},
		error: function() {
			alert('JSONデータ取得エラー：' + prgName + formParams);
		},
		complete: function() {

		}
	});
}
