<?php
session_start();
$user_check = $_SESSION['login_user'];

if (isset($user_check)) {
	
?>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/bootstrap-table.css">
<link rel="stylesheet" href="../css/bootstrap-editable.css">
<link href="../css/stylish-portfolio.css" rel="stylesheet">
<div class="success-message"></div>
<div class="error-message"></div>
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title">All Orders -- 

			<?php

			try {

				$dbh = new PDO("mysql:host=162.251.83.139;dbname=cashew", "cashew", "kaju@007");
				$sql = "SELECT sum(quantity) as qty FROM registrations";
				$statement = $dbh -> prepare($sql);
				$statement -> execute();
				$results = $statement -> fetchAll(PDO::FETCH_ASSOC);
				print_r($results[0]['qty']);
			} catch(PDOException $e) {
				echo $e -> getMessage();
			}
			?> </span>
	</div>
	<div class="panel-body">
		<div class="table-primary">
			<table class="table table-striped table-bordered" data-unique-id="id" data-toggle="table" data-url="assets/scripts/enlist.php" style="font-size: 14px" data-filter-control="true" data-detail-view="true" data-row-style="rowStyle" data-detail-formatter="detailFormatter" id="table">
				<thead>
					<tr>
						<th data-field="id" data-sortable="true">ID</th>
						<th id="user_name" data-url="/update.php?id=" data-field="user_name" data-filter-control="input" data-sortable="true">User Name</th>
						<th id="email" data-field="email" data-filter-control="input"  data-sortable="true">Email</th>
						<th id="addr1" data-field="addr1" data-sortable="true">Address Line 1</th>
						<th id="addr1" data-field="addr2" data-sortable="true">Address Line 2</th>
						<th data-field="city" data-filter-control="select" data-sortable="true">City</th>
						<th data-field="order_date" data-filter-control="select"  data-sortable="true">Order Date</th>
						<th data-field="order_time" data-filter-control="select"  data-sortable="true">Order Time</th>
						<th data-field="order_status" data-filter-control="select"  data-sortable="true">Order Status</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootstrap-table.js"></script>
<script src="../js/bootstrap-table-filter-control.js"></script>
<script src="../js/bootstrap-table-editable.js"></script>
<script src="../js/bootstrap-editable.js"></script>

<script>
	function detailFormatter(index, row) {
		var html = [];
		// $.each(row, function (key, value) {
		// html.push('<p><b>' + key + ':</b> ' + value + '</p>');
		html.push('<button id="btnConfirm" onclick="confirmOrder(' + row.id + ', this.id)" style="color: #333333" class="btn btn-default">Confirm Order</button>&nbsp;&nbsp;&nbsp;');
		html.push('<button onclick="rejectOrder()" id="btnEdit" style="color: #333333" class="btn btn-warning">Reject Order</button>');
		// });

		return html.join('');
	}

	function priceFormatter(value) {
		// 16777215 == ffffff in decimal
		var color = '#' + Math.floor(Math.random() * 6777215).toString(16);
		return '<div  style="color: ' + color + '">' + '<i class="glyphicon glyphicon-usd"></i>' + value.substring(1) + '</div>';
	}

	function rowStyle(row, index) {
		var classes = ['active', 'success', 'info', 'warning', 'danger'];

		if (row.order_status == 2) {
			return {
				classes : 'danger'
			};
		} else if (row.order_status == 1) {
			return {
				classes : 'success'
			};
		}
		return {};
	}

	function editRecord() {
		$("#user_name").attr("data-editable", "true");
	}

	function confirmOrder(order, id) {

		var $table = $('#table');
		var postdata = JSON.stringify($table.bootstrapTable('getRowByUniqueId', order));
		// alert($table.bootstrapTable('getRowByUniqueId', order));
		// alert(id);
		$.ajax({
			type : 'POST',
			url : 'assets/scripts/update.php',
			data : {
				id : order,
				action : "confirm"
			},
			dataType : 'json',
			success : function(response) {
				if (response == 0) {
					$('.error-message').html("Error occured while proccesing order");
					$('.error-message').fadeIn();
				} else {
					$('#' + id).html("Order Confirmed");
					$('#' + id).attr('class', 'btn btn-success');
					// $('#'+id).fadeIn();

				}
			}
		});

	}

	function rejectOrder() {
		$("#user_name").attr("data-editable", "true");
	}
</script>
<?php
} else {

}
?>