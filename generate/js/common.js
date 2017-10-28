$( function() {

	$( '.datepicker' ).datepicker({ dateFormat: 'yy-mm-dd', maxDate: 0});
});

function generateTable(formId){

	var formType = '';
	if(formId == 'dateRangeForm'){

		formType = 'generate_table';
	}
	else{

		formType = 'input_data';
	}
	$.ajax({

		type: 'post',
		url: 'post.php',
		data: { type: formType, postData : $('#'+formId).serializeArray()},
		success: function(returnDatas){

    		var allReturnData = JSON.parse(returnDatas);
    		if(allReturnData.status){

    			$('#'+formId+'_status_msg').html('<p style="color: green;">'+allReturnData.statusMsg+'</p>');
    			if(formType == 'generate_table'){

    				$('#dynamic_table').html(allReturnData.returnData);
				}
    		}
    		else{

    			$('#'+formId+'_status_msg').html('<p style="color: red;">'+allReturnData.statusMsg+'</p>');
    			if(formType == 'generate_table'){

    				$('#dynamic_table').html('');
    			}
    		}
    		setTimeout(function(){

    			$('#'+formId+'_status_msg p' ).remove();
    		}, 3000);
		},      
		error: function() {
    
    		$('#'+formId+'_status_msg').html('<p style="color: red;">Error occurred!</p>');
			if(formType == 'generate_table'){

				$('#dynamic_table').html('');
			}
		}
	});
}