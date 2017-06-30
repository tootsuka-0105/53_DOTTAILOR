//------------------------------------
// Form Validation
// Ver: 1.0.3
//
// Author: ENTEREAL LLP
// URL: http://www.entereal.co.jp/
//------------------------------------

(function($) {
	var alertMsgClassName = "";
	$.fn.formValidation = function(options) {
		// デフォルト
		var settings = $.extend({
			'checkRealtime'   : 'false',
			'alertMsgClass'   : 'alert-msg',
			'addIcon'         : 'true',
			'requiredMessage' : '必須項目',
			'invalidMessage'  : '不正形式',
			'inputTooShortMessage'   : '入力/選択 不足',
			'inputTooLongMessage'    : '入力/選択 超過',
			'notMatchedMessage'    : '不一致'
		}, options);

		// 要素を退避
		var elements = this;
		alertMsgClassName = settings.alertMsgClass;

		// 実処理
		elements.each(function() {
// init
			var msgBoxObj = $('<div class="alert-box col-sm-2"></div>');
			var iconObj = $('<span class="form-control-feedback"><i class="fa fa-times"></i></span>');
			var requiredAlertMsgObj = $('<p class="required-msg"></p>').addClass(alertMsgClassName).html(settings.requiredMessage);
			var invalidAlertMsgObj = $('<p class="invalid-msg"></p>').addClass(alertMsgClassName).html(settings.invalidMessage);
			var inputShortAlertMsgObj = $('<p class="short-msg"></p>').addClass(alertMsgClassName).html(settings.inputTooShortMessage);
			var inputLongAlertMsgObj = $('<p class="long-msg"></p>').addClass(alertMsgClassName).html(settings.inputTooLongMessage);
			var notMachedAlertMsgObj = $('<p class="not-matched-msg"></p>').addClass(alertMsgClassName).html(settings.notMatchedMessage);

			elements.find('.form-group [valCheck]:visible').each(function () {
				// メッセージボックス追加
				var parentGroup = $(this).parents('.form-group');
				if (parentGroup.find('.alert-box').length == 0) {
//					parentGroup.append(msgBoxObj.clone());
				}
				var msgBox = parentGroup.find('.alert-box');

				// アイコン追加
				if (settings.addIcon == "true") {
					// 入力系のみ追加対象
					if (($(this).is('[type=text]')) || ($(this).is('[type=number]')) || ($(this).is('[type=email]')) || ($(this).is('[type=password]')) || ($(this).is('select')) || ($(this).is('textarea'))) {

						// 直後に既にアイコンがある場合、後ろにインプットグループのボタン/ラベルが存在する場合には追加しない
						if ((!($(this).next().is('.form-control-feedback'))) && (!($(this).next().is('.input-group-addon'))) && (!($(this).next().is('.input-group-btn')))) {
							parentGroup.addClass('has-feedback');
							$(this).after(iconObj.clone());

							// Small bug fix (input-group with form-control-feedback)
							if ($(this).parents('.input-group').length > 0) {
								$(this).css('border-top-right-radius', '4px').css('border-bottom-right-radius', '4px');
							}
						}
					}
				}

				// 入力必須項目
				if ($(this).is('[valCheck*=required]')) {
					if (msgBox.find('.required-msg').length == 0) {
//						msgBox.append(requiredAlertMsgObj.clone());
					}
				}

				// フォーマットチェック項目
				if (($(this).is('[valCheck*=zip]')) || ($(this).is('[valCheck*=tel]')) || ($(this).is('[valCheck*=email]')) || ($(this).is('[valCheck*=url]')) || ($(this).is('[valCheck*=number]')) || ($(this).is('[valCheck*=kana]')) || ($(this).is('[valCheck*=file]')) || ($(this).is('[valCheck*=length]'))) {
					if (msgBox.find('.invalid-msg').length == 0) {
//						msgBox.append(invalidAlertMsgObj.clone());
					}
				}

				// パスワード長さ/選択個数項目
				if (($(this).is('[valCheck*=passwd]')) || ($(this).is('[valCheck*=groupcheck]'))) {
					if (msgBox.find('.short-msg').length == 0) {
//						msgBox.append(inputShortAlertMsgObj.clone());
					}
					if (msgBox.find('.long-msg').length == 0) {
//						msgBox.append(inputLongAlertMsgObj.clone());
					}
				}

				// 再入力チェック項目
				if ($(this).is('[valCheck*=retype]')) {
					if (msgBox.find('.not-matched-msg').length == 0) {
//						msgBox.append(notMachedAlertMsgObj.clone());
					}
				}

				// バインド リアルタイムチェック
				if (settings.checkRealtime == "true") {
					$(this).on('blur.valiCheckRealtime', function() {
						checkRealtime($(this));
					});
				}

				// サブミット前処理
				if ($(this).is('[valCheck*=submit]')) {
					$(this).off('blur.valiCheckRealtime');

					var originalClickAction = $(this).attr('onclick') === undefined ? "" : $(this).attr('onclick');
					$(this).removeAttr('onclick');

					$(this).on('click.valiSubmit', function () {
						elements.find('.form-group [valCheck]').not('button').each(function () {
							checkRealtime($(this));
						});

						var errorCounts = elements.find('.has-error').length;
						console.log('エラー項目数：' + errorCounts);

						// ここに正常ルートの処理を記入
						if (errorCounts == 0) {
							if (originalClickAction != "") {
								func = new Function("return " + originalClickAction);
								func();
							}

						// 先頭エラーにフォーカス
						} else {
							return false;
							elements.find('.has-error:eq(0)').find('input, select, textarea')[0].focus();
						}
					});
				}
			});
		});

		return this;
	};

	// リアルタイムチェック
	function checkRealtime(targetElement) {
		var requiredResult = "true";
		var hasError = "false";
		var parentGroup = targetElement.parents('.form-group');
		var msgBox = parentGroup.find('.alert-box');

		parentGroup.find('[valCheck]:visible').each(function() {
			targetElement = $(this);

			// 非表示エレメント
			if (targetElement.is('[valCheck*=required]:hidden')) {
				parentGroup.removeClass('has-error');

			// 入力必須エレメント
			} else if (targetElement.is('[valCheck*=required]:visible')) {
				var targetVal = "";

				// radio, checkbox
				if ((targetElement.is('[valCheck*=" check"]')) || (targetElement.is('[valCheck*="groupcheck"]'))) {
					targetVal = $('input[name="' + targetElement.attr('name') + '"]:checked').val();

				// select
				} else if (targetElement.is('[valCheck*="select"]')) {
					// if select an option which doesn't have value attribute
					if (targetElement.find(':selected').attr('value') === undefined) {
						targetVal = '';
					} else {
						targetVal = targetElement.val();
					}

				// text, file, textarea
				} else {
					targetVal = targetElement.val();
				}

				// 入力値判定
				if ((hasError == "true") || (targetVal == '') || (targetVal === undefined)) {
					parentGroup.addClass('has-error');
					msgBox.find('.alert-msg').hide();
					msgBox.find('.required-msg').show();
					requiredResult = "false";
				}
			}

			// 入力必須か問わず、ファイル拡張子チェック
			if ((targetElement.is('[valCheck*="file"]')) && (targetElement.val() !== undefined) && (targetElement.val() !== "")) {
				var acceptExts = targetElement.attr('valCheck').replace('required ', '').replace('file ', '').split(' ');

				var extCheck = "false";
				for (var iCnt = 0; iCnt < acceptExts.length; iCnt ++) {
					var re = new RegExp(acceptExts[iCnt], "i");
					if (targetElement.val().match(re)) {
						extCheck = "true";
					}
				}

				// 拡張子判定結果反映
				if (extCheck !== "true") {
					parentGroup.addClass('has-error');
					msgBox.find('.alert-msg').hide();
					msgBox.find('.invalid-msg').show();
					requiredResult = extCheck;
				}
			}

			// サニタイジング
			if (requiredResult == "true") {
				requiredResult = sanitizeData(targetElement);
			}
		});

		return requiredResult;
	}

	// サニタイジング
	function sanitizeData(targetElement) {
		var sanitizeResult = "true";
		var dataValue = targetElement.val();
		var parentGroup = targetElement.parents('.form-group');
		var msgBox = parentGroup.find('.alert-box');

//		// select
//		if (targetElement.is('[valCheck*=select]')) {
//			return true;
//		}

		// file
		if (targetElement.is('[valCheck*=file]')) {
			return true;
		}

		// except select
		if (!(targetElement.is('[valCheck*=select]'))) {
			dataValue = dataValue.replace(/[　]+/g, ' ');
			dataValue = dataValue.replace(/[ ]+/g, ' ');
			dataValue = dataValue.replace(/(^　+)|(　+$)/g, "");
			dataValue = toOneByteString(dataValue);
		}

		// text
		if (targetElement.is('[valCheck*=text]')) {
			dataValue = dataValue.replace(/[-ー－―ｰ]+/g, 'ー');

			// 英数に続く'ー'を'-'に再置換
			while (dataValue.match(/([\w]+)(ー)/)) {
				dataValue = dataValue.replace(RegExp.$1 + RegExp.$2, RegExp.$1 + '-');
			}
		}

		// kana
		if (targetElement.is('[valCheck*=kana]')) {
			dataValue = dataValue.replace(/[-ー－―ｰ]+/g, 'ー');
			dataValue = dataValue.replace(/ーー/g, 'ー');

			if (!(dataValue.match(/^[ァ-ヶー]*$/))) {
				sanitizeResult = "invalid";
			}
		}

		// number
		if (targetElement.is('[valCheck*=number]')) {
			// is number
			if (dataValue.match(/^\d+$/)) {
				dataValue = (dataValue - 0) + '';

			// is not number
			} else {
				dataValue = "";
				sanitizeResult = "invalid";
			}
		}

		// zip
		if ((targetElement.val != "") && (targetElement.is('[valCheck*=zip]'))) {
			// Right digits
			if (dataValue.replace(/[-ー－―ｰ]+/g, '').match(/([\d]{3})([\d]{4})/)) {
				dataValue = RegExp.$1 + '-' + RegExp.$2;

			// Wrong digits
			} else {
				dataValue = dataValue.replace(/[-ー－―ｰ]+/g, '-');
				sanitizeResult = "invalid";
			}
		}

		// tel
		if (targetElement.is('[valCheck*=tel]')) {
			dataValue = dataValue.replace(/[-ー－―ｰ]+/g, '-');

			// Right digits
			if (((dataValue.replace(/-/g, '').match(/^0[\d]{9}$/)) && ((dataValue.length == 10) || (dataValue.length == 11) || (dataValue.length == 12))) || ((dataValue.replace(/-/g, '').match(/^0[\d]{10}$/)) && ((dataValue.length == 11) || (dataValue.length == 12) || (dataValue.length == 13)))) {
				var len = dataValue.replace(/-/g, '').length;
				var f2 = dataValue.replace(/-/g, '').substr(0, 2);
				var f3 = dataValue.replace(/-/g, '').substr(0, 3);
				var f4 = dataValue.replace(/-/g, '').substr(0, 4);
				var f5 = dataValue.replace(/-/g, '').substr(0, 5);

				// 11 digits
				if  (len == 11) {
					// IP Phone(050) or Mobile Phone(070|080|090)
					if ((f3 == '050') || (f3 == '070') || (f3 == '080') || (f3 == '090')) {
						dataValue.replace(/[-ー－―ｰ]+/g, '').match(/(\d{3})(\d{4})(\d{4})/);
						dataValue = RegExp.$1 + '-' + RegExp.$2 + '-' + RegExp.$3;

					} else {
						sanitizeResult = "invalid";
					}

				// 10 digits
				} else if (len == 10) {
					// Free Dial(0120|0800)
					if ((f4 == '0120') || (f4 == '0800')) {
						dataValue.replace(/[-ー－―ｰ]+/g, '').match(/(\d{4})(\d{3})(\d{3})/);
						dataValue = RegExp.$1 + '-' + RegExp.$2 + '-' + RegExp.$3;

					// Tokyo(03) or Osaka(06) or Saitama(0420|0429) or Kamogawa/Chiba(04700|04709) or Kashiwa/Abiko/Nagareyama/Noda(0471)
					} else if ((f2 == '03') || (f2 == '06') || (f4 == '0420') || (f4 == '0429') || (f5 == '04700') || (f5 == '04709') || (f4 == '0471')) {
						dataValue.replace(/[-ー－―ｰ]+/g, '').match(/(\d{2})(\d{4})(\d{4})/);
						dataValue = RegExp.$1 + '-' + RegExp.$2 + '-' + RegExp.$3;

					// Other area
					} else {
						if ((dataValue.match(/\d{10}/)) || (!(dataValue.match(/-\d{4}$/))) || (!(dataValue.match(/\\d{2,5}-/)))) {
							dataValue.replace(/[-ー－―ｰ]+/g, '').match(/(\d{3})(\d{3})(\d{4})/);
							dataValue = RegExp.$1 + '-' + RegExp.$2 + '-' + RegExp.$3;
						}
					}
				}

			// Wrong digits
			} else {
				sanitizeResult = "invalid";
			}
		}

		// email
		if (targetElement.is('[valCheck*=email]')) {
			if (!targetElement.val().match(/^[A-Za-z0-9]+[\w\._-]+@[\w\.-]+\.\w{2,}$/)) {
				sanitizeResult = "invalid";
			}
		}

		// url
		if (targetElement.is('[valCheck*=url]')) {
			if (!(targetElement.val().match(/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/))) {
				sanitizeResult = "invalid";
			}
		}

		// password
		if (targetElement.is('[valCheck*=passwd]')) {
			var minLength = 0;
			var maxLength = 999;
			var requiredLength = targetElement.attr('valCheck').replace('required', '').replace('passwd_', '').replace('passwd', '').replace(' ', '').split('-');

			if (requiredLength.length > 1) {
				if (requiredLength[0].match(/^\d+$/)) {
					minLength = requiredLength[0] - 0;
				}
				if (requiredLength[1].match(/^\d+$/)) {
					maxLength = requiredLength[1] - 0;
				}
				if (targetElement.is('[valCheck*=required]')) {
					minLength = minLength == 0 ? 1 : minLength;
				}

				// Less length
				if (targetElement.val().length < minLength) {
					sanitizeResult = "short";

				// Over length
				} else if  (targetElement.val().length > maxLength) {
					sanitizeResult = "long";
				}
			}
		}

		// groupcheck
		if (targetElement.is('[valCheck*=groupcheck]')) {
			var minLength = 0;
			var maxLength = 999;
			var requiredCount = targetElement.attr('valCheck').replace('required', '').replace('groupcheck_', '').replace('groupcheck', '').replace(' ', '').split('-');

			if (requiredCount.length > 1) {
				if (requiredCount[0].match(/^\d+$/)) {
					minLength = requiredCount[0] - 0;
				}
				if (requiredCount[1].match(/^\d+$/)) {
					maxLength = requiredCount[1] - 0;
				}
				if (targetElement.is('[valCheck*=required]')) {
					minLength = minLength == 0 ? 1 : minLength;
				}
				var checkedCounts = parentGroup.find('input[name=' + targetElement.attr('name') + ']:checked').length;

				// Less checked
				if (checkedCounts < minLength) {
					sanitizeResult = "short";

				// Over checked
				} else if (checkedCounts > maxLength) {
					sanitizeResult = "long";
				}
			}
		}

		// retype
		if (targetElement.is('[valCheck*=retype]')) {
			parentGroup.find('input[name=' + targetElement.attr('name') + ']').each(function() {
				if ($(this).val() != targetElement.val()) {
					sanitizeResult = "not-matched";
				}
			});

			if ((sanitizeResult == "true") && (msgBox.find('.invalid-msg:visible').length > 0)) {
				return "false";
			}
		}

//		targetElement.val(dataValue);

		if ((targetElement.is('[valCheck*=required]')) && (dataValue == '')) {
			parentGroup.addClass('has-error');
			msgBox.find('.alert-msg').hide();
			msgBox.find('.required-msg').show();
			return "false";
		}
		if (sanitizeResult !== "true") {
			parentGroup.addClass('has-error');
			msgBox.find('.alert-msg').hide();
			msgBox.find('.' + sanitizeResult + '-msg').show();

		} else {
			parentGroup.removeClass('has-error');
			msgBox.find('.alert-msg').hide();
		}
		return sanitizeResult;
	}


// 共通関数
	// 文字列の半角化
	function toOneByteString(str) {
		str = str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
			return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
		});
		return str;
	}
})(jQuery);
