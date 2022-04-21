<?php 
// Connect to the Database 
$servername = "localhost";
$username = "root";
$password = "";
$database = "incident_finder";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Die if connection was not successful
if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Incident Finder</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
  </head>
  <body>
    <div class="container my-4">
      <h1>Incident Finder</h1>
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            class="nav-link active"
            id="pills-home-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-home"
            type="button"
            role="tab"
            aria-controls="pills-home"
            aria-selected="true"
          >
            List
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-profile-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-profile"
            type="button"
            role="tab"
            aria-controls="pills-profile"
            aria-selected="false"
          >
            Keywords
          </button>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div
          class="tab-pane fade show active"
          id="pills-home"
          role="tabpanel"
          aria-labelledby="pills-home-tab"
        >
        
        <table class="table">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Image Name</th>
                <th scope="col">Computer Name</th>
                <th scope="col">Date Log</th>
              </tr>
            </thead>
            <?php 
              $sql = "SELECT * FROM `list`";
              $data = mysqli_query($conn, $sql);

              $total = mysqli_num_rows($data);
                if($total!= 0){
                     
                  while($result=mysqli_fetch_assoc($data)){
                    
                    echo "
                      <tr>
                      <th>".$result['ID']."</th>
                      <th>".$result['Image Name']."</th>
                      <th>".$result['Computer Name']."</th>
                      <th>".$result['Log Date']."</th>
                    ";
                  }
                  
                }else{
                  echo "No record found";
                }
            ?>
          </table>

        </div>
        <div
          class="tab-pane fade"
          id="pills-profile"
          role="tabpanel"
          aria-labelledby="pills-profile-tab"
        >
          <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Add String" rows="3"></textarea>
        </div>
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>
  </body>
</html>