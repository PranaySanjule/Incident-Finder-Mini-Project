$(document).ready(function(){
	$('#data_table').Tabledit({
		url: 'live_edit.php',
		deleteButton: false,
		editButton: false,   		
		columns: {
		  identifier: [0, 'sno'],                    
		  editable: [[1, 'keywords']]
		},
		hideIdentifier: true,
		url: 'live_edit.php'		
	});
});