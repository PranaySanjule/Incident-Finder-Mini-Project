<?php

include("db_connect.php");

header('Content-Type:Application/json');
$image = isset($_FILES['image_name'])?$_FILES['image_name']:'';

// $log_date = isset($_POST['log_date'])?$_POST['log_date']:'';
if($image['error'] > 0): //|| empty($log_date)
    $res = array(
        'status' => 'error',
        'msg' => 'Input Parameters is missing!'
    );
    echo json_encode($res);
    
    exit;
endif;

// save image to disk from $_FILE['tmp'];
// define('SITE_ROOT','D:\\xampp\\htdocs\\dashboard\\incident-finder\\images\\');

if(isset($_FILES['image_name'])){


    // 1ST APPROACH
    // $file_name = $_FILES['image_name']['name'];
    // $file_tmp = $_FILES['image_name']['tmp_name'];
    // move_uploaded_file($file_tmp,SITE_ROOT.$file_name);

    // shell_exec('"C:\\Program Files (x86)\\Tesseract-OCR\\tesseract" "D:\\xampp\\htdocs\\dashboard\\incident-finder\\images\\'.$new_img_name.'" output');

    // echo "Extracted Data\n\n";

    // $myfile = fopen("out.txt", "r") or die("Unable to open file!");
    // echo fread($myfile,filesize("out.txt"));
    // fclose($myfile);

    // 2ND APPROACH
    $img_name = $_FILES['image_name']['name'];
              $img_size = $_FILES['image_name']['size'];
              $tmp_name = $_FILES['image_name']['tmp_name'];
              $error = $_FILES['image_name']['error'];

              if ($error === 0) {
                if ($img_size > 12500000) {
                  $em = "Sorry, your file is too large.";
                }else {
                  $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                  $img_ex_lc = strtolower($img_ex);

                  $allowed_exs = array("jpg", "jpeg", "png"); 

                  if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                    $img_upload_path = 'images/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                
                    // OCR (shell_exec)

                        shell_exec('"C:\\Program Files (x86)\\Tesseract-OCR\\tesseract" "D:\\xampp\\htdocs\\dashboard\\incident-finder\\images\\'.$new_img_name.'" output');
                
                        // echo "Extracted Data\n\n";

                        $myfile = fopen("output.txt", "r") or die("Unable to open file!");
                        // echo fread($myfile,filesize("output.txt"));
                        fclose($myfile);
                        

                        // delete temporary stored image
                        $path = $_SERVER['DOCUMENT_ROOT'].'/dashboard/incident-finder/images/'.$new_img_name;
                        unlink($path);
                    
                }else{
                      $em = "You can't upload files of this type";
                  }
                }

            }
}


// GET keywords from db
  // SQL QUERY

  $arr = [];
  while(empty($arr)){
      $query = "SELECT * FROM `keyword`";
      
      $result = mysqli_query($conn, $query);
      
      // FETCHING DATA FROM DATABASE
      $num = mysqli_num_rows($result);

      while($row = mysqli_fetch_assoc($result)){
          $arr[] = $row;
      }
    }

//   echo "Added New Keyword :-\n";

// Search keyword in ocr text (strpos, strstr, preg_match)
$file_name='output.txt';
$file_content = file_get_contents($file_name);

$keyword = '';

$check = false;
for($i=0;$i<sizeof($arr);$i++){
    $keyword = $arr[$i]['keywords'];
    if(strpos($file_content,$keyword)){
        // echo "$keyword : found\n";
        $check = true;
        break;
    }
}

if($check){
    // If found make entry in list table.
    // echo "\n";
    $computerName = gethostname();
    $image = $_FILES['image_name']['name'];
    $sql = "INSERT INTO `list`(`image_url`,`computer_name`,`match_keyword`) VALUES('$image','$computerName','$keyword')";
    mysqli_query($conn, $sql);
    unlink("output.txt");
    // echo "\n";
    $res = array(
    'status' => 'success',
    'msg' => 'OCR success'
    );
    echo json_encode($res);
    exit;

}else{
    unlink("output.txt");
    $res = array(
    'status' => 'failure',
    'msg' => 'keyword Not Match'
    );
    echo json_encode($res);
    exit;
}

?>