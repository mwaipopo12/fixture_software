<?php
  session_start();
  include('inc/config.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];

  include('inc/dbconn.php');
  if (isset($_POST['reg_venue'])) {
    // Assuming you have already established a database connection named $mysqli

    $name = $_POST['name'];
    $region = $_POST['region'];
    $quality = $_POST['quality'];
    $league_type=$_POST['league_type'];

    // Check if a venue with the same name already exists in the database
    $checkQuery = "SELECT COUNT(*) FROM venue WHERE name = ?";
    $checkStmt = $mysql->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param('s', $name);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close(); // Close the check statement

        if ($count > 0) {
            $err = "Error: A venue with the same name already exists.";
        } else {
            // Venue with the same name does not exist, proceed with insertion
            $insertQuery = "INSERT INTO venue (name, region, quality,league_type) VALUES (?, ?, ?,?)";
            $insertStmt = $mysql->prepare($insertQuery);

            if ($insertStmt) {
                $insertStmt->bind_param('ssss', $name, $region, $quality,$league_type);
                $insertStmt->execute();

                if ($insertStmt->affected_rows > 0) {
                    $success = "Venue Data Inserted Successfully";
                } else {
                    $err = "Error: Data Insertion Failed";
                }

                $insertStmt->close(); // Close the statement
            } else {
                $err = "Error: Statement Preparatin Failed";
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
                        $query = "SELECT name,league_type, season FROM $tableName"; // Add your WHERE conditions if needed
                        $result = $mysql->query($query);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $leagueName = $row['name'];
                            $league_type = $row['league_type'];
                            $seasonName= $row['season'];
                        } else {
                            $leagueName = "Unknown League"; // Provide a default name if no data is found
                            $league_type= "Unknown Leaguye Type";
                            $seasonName = "Unkown Season";
                        }

                        
                        ?>

                        <!-- Display the league name in your HTML -->
                        <h2 class="card-header">
                            Add Venue for <?php echo $leagueName; ?> <?php echo $league_type;?>  <?php echo $seasonName;?> Stage 3/5
                        </h2>

                 <div class="card-body">
                <!--Form-->
                <form method="post" enctype="multipart/form-data" autocomplete="off">
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label for="name">Venue Name</label>
                          <input type="text" required name="name" class="form-control" id="name" aria-describedby="emailHelp">
                      </div> 
                      
                      <div class="form-group col-md-6">
                          <label for="region">Venue Region</label>
                          <input type="text" required name="region" class="form-control" id="region" aria-describedby="emailHelp">
                      </div> 
                  </div>

            <div class="row">
              <div class="form-group col-md-6">
                  <label for="quality">Venue Quality</label>
                  <select required name="quality" class="form-control" id="quality">
                      <option value="light">light</option>
                      <option value="no light">no light</option>
                  </select>
              </div>
              
                  
                  <div class="form-group col-md-6">
                    <label for="league_type">League Type</label>
                    <select required name="league_type" class="form-control" id="league_type">
                    
                        <option value="Premier">Premier</option>
                        <option value="Championship">Championship</option>
                        <option value="single">single</option>
                        <option value="First">First</option>

                    </select>
                 </div>
                            
            </div>
                  <div class="form-group">
                        <?php if(isset($err)) { ?>
                            <div class="alert alert-danger"><?php echo $err; ?></div>
                        <?php } ?>
                      </div>
              <button type="submit" name="reg_venue" class="btn btn-primary">Add</button>
             
           
          </form>

                <!-- ./ Form -->
            </div>    
                </div>
                </div>
 
        </div>
    </div>

</div>

</html>
