<?php

function customer_list() {
	$pageNum = isset($_POST['page']) ? $_POST['page'] : 1;

	headerHtml('顧客一覧');
	echo <<< EOS
			<h3 class="clearfix">
				<i class="fa fa-fw fa-list"></i> 顧客一覧
			</h3>
			<!--
			<p class="text-danger">※ログインAPIが正しく動いていないのでエラーが出ます。<br>　<a href="http://153.121.60.215/management/customer_list.php" target="_blank">ここ</a>をクリックするとAPIの戻り値を確認できます。</p>
			-->

			<div class="table-responsive">
				<table class="table table-striped" id="customer-list-table" data-page-number="$pageNum">
					<thead>
						<tr>
							<th>顧客ID</th>
							<th>顧客名</th>
							<th>電話番号</th>
							<th>営業担当</th>
						</tr>
					</thead>
					<tbody>
						<script type="text/x-template" id="customer-list-template">
						<tr>
							<td>{{STR_FIELD01}}</td>
							<td>{{STR_FIELD02}}</td>
							<td>{{STR_FIELD07}}</td>
							<td>{{charge_name}}</td>
						</tr>
						</script>
					</tbody>
				</table>
			</div>

			<div class="text-center">
				<form action="./" method="POST" class="hidden-xs hidden-sm hidden-md hidden-lg">
					<input type="hidden" name="c" value="{$_POST['c']}">
					<input type="hidden" name="page" value="">
				</form>

				<ul class="pagination">

				</ul>
			</div>
EOS;

	$jsScript = <<< EOS
		<script>
		// Fire when the DOM is built
		$(document).ready(function() {
			// 顧客リスト取得
			getCustomerList();
		});
		</script>
EOS;

	footerHtml($jsScript);
}


?>
