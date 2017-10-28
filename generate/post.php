<?php
require('./database/connectdb.php');

if($_POST['type'] == 'generate_table'){

	$status = false;
	$statusMsg = $returnData = '';
	$returnArray = array();
	$formData = $_POST['postData'];
	$fromDate = $toDate = '';
	foreach($formData as $rangeKey=>$rangeVal){

		if($rangeVal['name'] == 'from_date'){

			$fromDate = $rangeVal['value'];
		}
		if($rangeVal['name'] == 'to_date'){

			$toDate = $rangeVal['value'];
		}
	}
	if($fromDate != '' && $toDate != ''){

		if(strtotime($toDate) < strtotime($fromDate)){

			$statusMsg = 'From Date must be less than To Date';
		}
		else{

			$dateTH = '';
			$begin = new DateTime($fromDate);
			$end = new DateTime( date('Y-m-d',strtotime($toDate) + 86400));
			$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
			foreach($daterange as $date){

    			$allDatesArr[] = $date->format("Y-m-d");
    			$dateTH .= '<th>'.$date->format('Y-m-d').'</th>';
			}
			// get companies from database
			$companyArr = array();
			$companyKey = 0;
			$companiesSql = "SELECT * FROM companies";
			$companyResult = $conn->query($companiesSql);
			if($companyResult->num_rows > 0){
    			// output data of each companyRow
    			while($companyRow = $companyResult->fetch_assoc()){

    				$companyArr[$companyKey]['id'] = $companyRow['id'];
    				$companyArr[$companyKey]['name'] = $companyRow['name'];
    				$companyKey++;
    			}

    			// get shares from database
    			$shareArr = array();
    			$shareKey = 0;
    			$sharesSql = "SELECT * FROM shares";
    			$shareResult = $conn->query($sharesSql);
    			if($shareResult->num_rows > 0){

    				// output data of each companyRow
    				while($shareRow = $shareResult->fetch_assoc()){

    					$shareArr[$shareKey]['id'] = $shareRow['id'];
    					$shareArr[$shareKey]['name'] = $shareRow['name'];
    					$shareKey++;
    				}

    				$tableTR = '';
    				foreach ($companyArr as $companyKey => $companyValue) {
    					
    					$companyId = $companyValue['id'];
    					$companyName = $companyValue['name'];
    					foreach ($shareArr as $shareKey => $shareValue) {
    						
    						$shareId = $shareValue['id'];
    						$shareName = $shareValue['name'];
    						$tableTD = '';
    						foreach ($allDatesArr as $key => $date) {
    							
    							$tableTD .= '<td><input type="text" name="'.$companyId.'^*^'.$shareId.'^*^'.$date.'" class="form-control"></td>';
    						}
    						$tableTR .= '<tr>
	      									<th>'.$companyName.'</th>
		      								<th>'.$shareName.'</th>'
		      								.$tableTD.'
      									</tr>';
    					}
    				}

    				$status = true;
    				$statusMsg = 'Table generated.';
    				$returnData = '<h2>Generated Table</h2>
    								<div id="shareValueForm_status_msg"></div>
										<form id="shareValueForm">
											<table class="table table-bordered">
											    <thead>
													<tr>
														<th>COMPANY NAME</th>
												      	<th>SHARE NAME</th>'
												        .$dateTH.
											      	'</tr>
											    </thead>
											    <tbody>'
											    .$tableTR.'
											    </tbody>
										  	</table>
										  	<input type="button" name="submitDate" class="btn btn-success" value="Submit" onclick="generateTable(\'shareValueForm\')" />
										</form>';
    			}
    			else{

    				$statusMsg = 'No share found in database.';
    			}
			}
			else{

   				$statusMsg = 'No company found in database.';
			}
		}
	}
	else{

		$statusMsg = 'Please fill all date fields.';
	}
	$returnArray['status'] = $status;
	$returnArray['statusMsg'] = $statusMsg;
	$returnArray['returnData'] = $returnData;
	echo json_encode($returnArray);
}

if($_POST['type'] == 'input_data'){

	$status = false;
	$statusMsg = $returnData = '';
	$returnArray = array();
	$formData = $_POST['postData'];
	$truncateSql = "TRUNCATE TABLE calculations";
	$conn->query($truncateSql);
	foreach($formData as $key=>$value){

		$shareValue = $value['value'] != '' ? $value['value'] : 0;
		if(is_numeric($shareValue)){

			$explodeOtherValue = explode('^*^', $value['name']);
			$companyId = $explodeOtherValue[0];
			$shareId = $explodeOtherValue[1];
			$date = $explodeOtherValue[2];
			$sql = "INSERT INTO calculations (company_id, share_id, share_date, value)
					VALUES ($companyId, $shareId, '$date', $shareValue)";
			if ($conn->query($sql) === TRUE) {

	    		$status = true;
	    		$statusMsg = 'Data saved successfully.';
			}
			else{
				$status = false;
				$statusMsg = 'Data not saved.';
				$truncateSql = "TRUNCATE TABLE calculations";
				$conn->query($truncateSql);
				break;
			}
		}
		else{

			$status = false;
			$statusMsg = 'Please enter only numeric values.';
			$truncateSql = "TRUNCATE TABLE calculations";
			$conn->query($truncateSql);
			break;
		}
	}
	$returnArray['status'] = $status;
	$returnArray['statusMsg'] = $statusMsg;
	$returnArray['returnData'] = $returnData;
	echo json_encode($returnArray);
}
?>