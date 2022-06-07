<?php

$insert = false;
$update = false;
$delete = false;

$connect = new PDO("mysql:host=localhost;dbname=incident_finder", "root", "");

$servername = "localhost";
$username = "root";
$password = "";
$database = "incident_finder";

$conn = mysqli_connect($servername, $username, $password, $database);

$error = '';
$output = '';

// delete keywords from DB
if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `keyword` WHERE `sno` = $sno";
  $result = mysqli_query($conn, $sql);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

      // Update keywords
      if(isset($_POST['snoEdit'])){

        $sno = $_POST["snoEdit"];
        $keywords = $_POST["keywordsEdit"];

        $query = "UPDATE `keyword` SET `keywords` = '$keywords' WHERE `keyword`.`sno` = $sno";
  
        $result = mysqli_query($conn, $query);
        if($result){
          $update = true;
        }
        else{
            echo '<div class="alert alert-danger" role="alert">
          Data Not Updated!
        </div>';
        }


      }elseif(empty($_POST["keywords"])){
        echo $error = '
        <div class="alert alert-danger" role="alert">
          Keyword List is Required
        </div>
        ';
      }else{

        // Insert keyword into DB
        $array = explode("\r\n", $_POST["keywords"]);

        $keyword_array = array_unique($array);

        $query = "
            INSERT INTO `keyword` (`keywords`) VALUES ('".implode("'),('", $keyword_array)."')
        ";

        $statement = $connect->prepare($query);
        $statement->execute();
        $insert = true;
      }
  }

  // Fetch all the keywords from DB
  $query = "
  SELECT * FROM keyword
  ORDER BY sno DESC
  ";

  $statement = $connect->prepare($query);
  $statement->execute();

  if($statement->rowCount() > 0)
  {
      $result = $statement->fetchAll();
      $sno = 0;
      foreach($result as $row){
        $sno = $sno + 1;
        $output .="
          <tr>
            <td scope='row'>". $sno . "</td>
            <td>".$row["keywords"]."</td>
            <td> <button class='edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button>  </td>
          </tr>
        ";
      }

  }else{
    $output .= '
      <tr>
        <td>No Data Found
      </tr>
    ';
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Incident Finder</title>
    <link rel="stylesheet" href="style.css">
    
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
    crossorigin="anonymous" 
    />
    
    <link href="//cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css" rel="stylesheet">

 
  </head>
  <body>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 style="color: black;" class="modal-title" id="editModalLabel">Edit Keywords String</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div style="color:black;" class="modal-body">
        <form action="index.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="snoEdit" id="snoEdit">
                <div class="form-group">
                  <textarea class="col-md-12" id="keywordsEdit" name="keywordsEdit" placeholder="Add String" required rows="5"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
        </form>
     </div>
    </div>
  </div>
</div>

  <div class="background">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Incident Finder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Project
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Incident Finder</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li>
          </ul>
          <form class="d-flex">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-warning" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>

  <?php
  if($insert){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($delete){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>

    <div class="container">
      <p class="heading py-3" ><u>Incident Finder</u></p>
      <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            class="nav-link active"
            id="pills-keyword-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-keyword"
            type="button"
            role="tab"
            aria-controls="pills-keyword"
            aria-selected="false"
          >
            Keywords
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-list-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-list"
            type="button"
            role="tab"
            aria-controls="pills-list"
            aria-selected="true"
          >
            List
          </button>
        </li>
        <!-- tab view to add image manually -->
        <!-- <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-upload-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-upload"
            type="button"
            role="tab"
            aria-controls="pills-upload"
            aria-selected="false"
          >
            Upload
          </button>
        </li> -->

      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div
          class="tab-pane fade show active"
          id="pills-keyword"
          role="tabpanel"
          aria-labelledby="pills-keyword-tab"
        >
          <form action="index.php" method="POST" enctype="multipart/form-data">
            <center class="py-4">
              <div class="keyArea">
                <textarea type="text" class="col-md-5" id="keywords" name="keywords" placeholder="Add String" required rows="5"></textarea>
              </div>
              <div class="textKey">
                <button type="submit" name="submit" class="btn btn-warning">Add Keywords</button>
              </div>
            </center>
          </form>
          <table class="table  table-striped" id="data_table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">S.NO</th>
                  <th scope="col">KEYWORDS</th>
                  <th scope="col">ACTIONS</th>
                </tr>
              </thead>
                <!-- show all the fetch keywords from DB -->
                <?php echo $output; ?>
          </table>
        </div>
        <div
          class="tab-pane fade"
          id="pills-list"
          role="tabpanel"
          aria-labelledby="pills-list-tab"
        >
        <table class="table">
          
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Image URL</th>
                <th scope="col">Computer Name</th>
                <th scope="col">Match Keyword</th>
                <th scope="col">Date Log</th>
              </tr>
              <!-- Show all confidential images -->
            <?php 

              include "db_connect.php";

              $sql = "SELECT * FROM `list`";
              $data = mysqli_query($conn, $sql);

              $total = mysqli_num_rows($data);
                if($total!= 0){
                     $ID = 0;
                  while($result=mysqli_fetch_assoc($data)){
                    $ID = $ID + 1;
                    echo "
                      <tr>
                      <th>".$ID."</th>
                      <th>".$result['image_url']."</th>
                      <th>".$result['computer_name']."</th>
                      <th>".$result['match_keyword']."</th>
                      <th>".$result['Log Date']."</th>
                      </tr>
                    ";
                  }
                  
                }else{
                  echo "No record found";
                }
            ?>  
        </table>

        </div>
        
        <!-- manually image upload and extract text functionality -->
        <!-- <div
          class="tab-pane fade"
          id="pills-upload"
          role="tabpanel"
          aria-labelledby="pills-upload-tab"
        >
        <center>
          <form action="upload.php" method="POST" enctype="multipart/form-data">
          <input type="file" name="my_image" />
          <input class="btn btn-warning" name="submit" type="submit"/>
          </form>
        </center>
        </div> -->

      </div>
    </div>

    <br>
    <br>
    <br>
    <br>

      <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 border-top" style="user-select: auto;">
        <p class="col-md-4 mb-0 text-muted" >© 2021 Incident Finder, Inc</p>

        <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none" style="user-select: auto;">
          <svg class="bi me-2" width="40" height="32" style="user-select: auto;"><use xlink:href="#bootstrap" style="user-select: auto;"></use></svg>
        </a>

        <ul class="nav col-md-4 justify-content-end" style="user-select: auto;">
          <li class="nav-item" style="user-select: auto;"><a href="#" class="nav-link px-2 text-muted" style="user-select: auto;">Home</a></li>
          <li class="nav-item" style="user-select: auto;"><a href="#" class="nav-link px-2 text-muted" style="user-select: auto;">Features</a></li>
          <li class="nav-item" style="user-select: auto;"><a href="#" class="nav-link px-2 text-muted" style="user-select: auto;">Pricing</a></li>
          <li class="nav-item" style="user-select: auto;"><a href="#" class="nav-link px-2 text-muted" style="user-select: auto;">FAQs</a></li>
          <li class="nav-item" style="user-select: auto;"><a href="#" class="nav-link px-2 text-muted" style="user-select: auto;">About</a></li>
        </ul>
      </footer>

      <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
      <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
      ></script>


      <script src="//cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
    
      <script>
        $(document).ready( function () {
            $('.table').DataTable();
        } );
      </script>
    


  <script>
    // script to delete data
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ",);
        tr = e.target.parentNode.parentNode;
        keywords = tr.getElementsByTagName("td")[1].innerText;
        console.log(keywords);
        keywordsEdit.value = keywords;  
        snoEdit.value = e.target.id;
        console.log(e.target.id);
        $('#editModal').modal('toggle');
      })
    })

    // script to add data
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ",);
        tr = e.target.parentNode.parentNode;
        keywords = tr.getElementsByTagName("td")[1].innerText;
        console.log("edit ");
        sno = e.target.id.substr(1);

        
        if (confirm("Are you sure you want to delete this note!")) {
          console.log("yes");
          window.location = `index.php?delete=${sno}`;
        }
        else {
          console.log("no");
        }
      })
    }) 
  </script>

  <script type="text/javascript" src="dist/jquery.tabledit.js"></script>
  <script type="text/javascript" src="custom_table_edit.js"></script>


  </body>
</html>