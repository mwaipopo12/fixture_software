<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];

  if (isset($_POST['reg_exceptional_date'])) {
    // Assuming you have already established a database connection named $mysql
    include('inc/dbconn.php');
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $break_type = $_POST['break_type'];

    // Check if a record with the same name already exists in the database
    $checkQuery = "SELECT COUNT(*) FROM exceptional_date WHERE name = ?";
    $checkStmt = $mysql->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param('s', $name);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close(); // Close the check statement

        if ($count > 0) {
            $err = "Error: An entry with the same name already exists.";
        } else {
            // No entry with the same name exists, proceed with insertion
            $insertQuery = "INSERT INTO exceptional_date (name, start_date, end_date, break_type) VALUES (?, ?, ?, ?)";
            $insertStmt = $mysql->prepare($insertQuery);

            if ($insertStmt) {
                $insertStmt->bind_param('ssss', $name, $start_date, $end_date, $break_type);
                $insertStmt->execute();

                if ($insertStmt->affected_rows > 0) {
                    $success = "Event Day Data Inserted Successfully";
                } else {
                    $err = "Error: Data Insertion Failed";
                }

                $insertStmt->close(); // Close the statement
            } else {
                $err = "Error: Statement Preparation Failed";
            }
        }
    } else {
        $err = "Error: Statement Preparation Failed (Check)" .$mysql->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include("inc/head.php");?>

<body class="">
 <!--Sidebar-->
 <?php include("inc/sidebar.php");?>
  
  <div class="main-content">
    <!-- Navbar -->
   <?php include("inc/nav.php");?>
    <!-- End Navbar -->
    <!-- Header -->
    <div class="header  pb-8 pt-5 pt-md-8" style="min-height: 270px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
    <span class="mask bg-gradient-default opacity-5"></span>
      
    </div>          
                <div class="container-fluid mt--7">
    
                <div class="row">
                 <div class="card col-md-12">
                      <?php
                        // database connection 
                        include('inc/dbconn.php');
                        // Specify the table name where the league name is stored
                        $tableName = "competition";

                        // SQL query to fetch the league name from the specified table
                        $query = "SELECT name,league_type,season FROM $tableName"; // Add your WHERE conditions if needed
                        $result = $mysql->query($query);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $leagueName = $row['name'];
                            $league_type = $row['league_type'];
                            $seasonName= $row['season'];
                        } else {
                            $leagueName = "Unknown League"; // Provide a default name if no data is found
                            $league_type ="Unknown League Type";
                            $seasonName = "Unkown Season";
                        }

                       
                        ?>

                        <!-- Display the league name in your HTML -->
                        <h2 class="card-header">
                            Add Exceptional Date for <?php echo $leagueName; ?> <?php echo $league_type;?> <?php echo $seasonName;?> Stage 5/5
                        </h2>
                                     
                 <div class="card-body">
                <!--Form-->
                <form method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Event Name</label>
                        <input type="text" required name="name" class="form-control" id="event_name" aria-describedby="emailHelp">
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
                  <label for="break_type">Break Type</label>
                  <select required name="break_type" class="form-control" id="break_type">
                      <option value="caf_qualifier">caf_qualifier</option>
                      <option value="all_teams">all_teams</option>
                  </select>
              </div>

            </div>
            <div class="form-group">
                        <?php if(isset($err)) { ?>
                            <div class="alert alert-danger"><?php echo $err; ?></div>
                        <?php } ?>
                    </div>

            <button type="submit" name="reg_exceptional_date" class="btn btn-primary">Add</button>
            
          
        </form>

                <!-- ./ Form -->
            </div>    
                </div>
                </div>
 
        </div>
    </div>

</div>

</html>
