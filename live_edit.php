<?php
// header();
include_once("db_connect.php");
// include("index.php");
  $doc = new DOMDocument();
  $doc->loadHTMLFile("index.php");
  $sno = $doc->getElementById("snoEdit");
  print_r($sno->nodeValue);

$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {	

	// print_r($input);
      if(isset($input['keywords'])){

        $sno = $input['sno'];
        $keywords = $input['keywords'];
		  // print_r(gettype($keywords));

        $query = "UPDATE `keyword` SET `keywords` = '$keywords' WHERE `keyword`.`sno` = $sno";
		  // print_r($query);
        $result = mysqli_query($conn, $query);
        if($result){
          echo "Updated";
        }
        else{
            echo '<div class="alert alert-danger" role="alert">
          Data Not Updated!
        </div>';
        }
      }

}