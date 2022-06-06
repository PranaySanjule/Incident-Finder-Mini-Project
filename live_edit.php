<?php
include("db_connect.php");
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'snoEdit') {	
	$update_field='';
	if(isset($input['keywords'])) {
		$update_field.= "keywords='".$input['keywords']."'";
	}
	if($update_field && $input['sno']) {
		$sql_query = "UPDATE `keyword` SET $update_field WHERE `keyword`.`sno`='" . $input['sno'] . "'";	
		mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));		
	}
		// $sno = $input["sno"];
        // $keywords = $input["keywords"];

		// if(isset($input['keywords'])){
		// 	$query = "UPDATE `keyword` SET `keywords` = '$keywords' WHERE `keyword`.`sno` = $sno";
		// }
  
        // $result = mysqli_query($conn, $query);
        // if($result){
        //   $update = true;
        // }
        // else{
        //     echo '<div class="alert alert-danger" role="alert">
        //   Data Not Updated!
        // </div>';
        // }

}