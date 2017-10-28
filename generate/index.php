<?php
	require('./inc/header.php')
?>
	
	<div class="container">
		<h1>Welcome to Generate</h1>
		<div id="dateRangeForm_status_msg">
		</div>
		<form id="dateRangeForm">
			<div class="form-group">
				<label for="fromdate">FROM DATE:</label>
				<input type="text" name="from_date" class="form-control datepicker">
			</div>
			<div class="form-group">
				<label for="todate">TO DATE:</label>
				<input type="text" name="to_date" class="form-control datepicker">
			</div>
			<div class="form-group">
				<input type="button" name="submitDate" class="btn btn-success" value="Submit" onclick="generateTable('dateRangeForm')" />
			</div>
		</form>

		<div id="dynamic_table"></div>
	</div>
<?php
	require('./inc/footer.php')
?>