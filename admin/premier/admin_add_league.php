<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];

  if (isset($_POST['competition'])) {
    // Assuming you have already established a database connection named $mysqli
    include('inc/dbconn.php');
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $season = $_POST['season'];
    $league_type=$_POST['league_type'];

    // Check if a row already exists in the competition table
    $checkQuery = "SELECT COUNT(*) FROM competition";
    $checkStmt = $mysql->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->execute();
        $checkStmt->bind_result($rowCount);
        $checkStmt->fetch();

        $checkStmt->close(); // Close the check statement

        if ($rowCount >= 4) {
            $err = "Error: Only 4 row is allowed in the competition table.";
        } else {
            // No row exists, proceed with insertion
            $insertQuery = "INSERT INTO competition (name, start_date, end_date,season ,league_type) VALUES (?, ?, ?, ?,?)";
            $insertStmt = $mysql->prepare($insertQuery);

            if ($insertStmt) {
                $insertStmt->bind_param('sssss', $name, $start_date, $end_date, $season,$league_type);
                $insertStmt->execute();

                if ($insertStmt->affected_rows > 0) {
                    // Data inserted successfully, redirect to the next page
                    header("Location: admin_add_dayMatch.php");
                    exit(); // Terminate script to prevent further execution
                } else {
                    $err = "Errr: Data Insertion Failed";
                }

                $insertStmt->close(); // Close the statement
            } else {
                $err = "Error: Statement Preparation Failed";
            }
        }
    } else {
        $err = "Error: Statement Preparation Failed (Check)";
    }
}


?>


<!DOCTYPE html>
<html lang="en">

  <?php include("inc/head.php");?>

<body class="">
 <!-- sidebar  -->
 <?php include("inc/sidebar.php");?>
  <div class="main-content">
     <!-- Header  -->
    <div class="header  pb-8 pt-5 pt-md-8" style="min-height: 270px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
          <span class="mask bg-gradient-default opacity-5"></span>
    </div>      
            <div class="container-fluid mt--7">
    
                <div class="row">
                 <div class="card col-md-12">
                 <h2 class="card-header">Add Competition Information  Stage 1/5</h2>
                 <div class="card-body">
                <!--Form-->
                <form method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Competition Name</label>
                        <input type="text" required name="name" class="form-control" id="name" aria-describedby="emailHelp">
                    </div> 
                    
                    <div class="form-group col-md-6">
                        <label for="start_date">Start Date</label>
                        <input type="date" required name="start_date" class="form-control" id="start_date" aria-describedby="emailHelp">
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="end_date">End Date</label>
                        <input type="date" required name="end_date" class="form-control" id="end_date" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="season">Season</label>
                        <input type="text" required name="season" class="form-control" id="season" aria-describedby="emailHelp">
                    </div>
                </div> 
                <div class="row">
                  
                  <div class="form-group col-md-6">
                    <label for="league_type">League Type</label>
                    <select required name="league_type" class="form-control" id="league_type">
                    
                    <option value="Premier">Premier</option>
                    <option value="Second">Second</option>
                    <option value="Single_leg">Single Leg</option>
                    <option value="First">First</option>
                    
                       
                    </select>
                 </div>       
          </div>

                <div class="form-group">
                        <?php if(isset($err)) { ?>
                            <div class="alert alert-danger"><?php echo $err; ?></div>
                        <?php } ?>
                </div>
            
                <button type="submit" name="competition" class="btn btn-primary">Add</button>
                
               
              </form>

                <!-- ./ Form -->
                </div>    
              </div>
              </div>
 
        </div>
    </div>

  </div>

  <script>
document.addEventListener("DOMContentLoaded", function() {
    // Attach the event listener to your form
    document.querySelector("form").addEventListener("submit", function(e) {
        // Prevent the default form submission to validate the input
        e.preventDefault();

        // Trim the input fields and check if they are empty
        var name = document.getElementById("name").value.trim();
        var start_date = document.getElementById("start_date").value.trim();
        var end_date = document.getElementById("end_date").value.trim();
        var season = document.getElementById("season").value.trim();
        var league_type = document.getElementById("league_type").value.trim();

        // Validate each field
        if (!name || !start_date || !end_date || !season || !league_type) {
            alert("Please fill in all fields correctly. Spaces are not allowed as the only input.");
        } else {
            // If validation passes, submit the form
            this.submit();
        }
    });
});
</script>

</body>
</html>
