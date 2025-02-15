<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];

  if (isset($_POST['reg_team'])) {
    // Assuming you have already established a database connection named $mysqli

    $name = $_POST['name'];
    $region = $_POST['region'];
    $big_team = $_POST['big_team'];
    $caf_qualifier = $_POST['caf_qualifier'];
    $league_type=$_POST['league_type'];
    $venue_name=$_POST['venue_name'];

    // Check if a team with the same name already exists in the database
    $checkQuery = "SELECT COUNT(*) FROM team WHERE name = ? AND league_type = ? ";
    $checkStmt = $mysql->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param('ss', $name, $league_type);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $err = "Error: A team with the same name and league_type already exists.";
        } else {
            // Team with the same name does not exist, proceed with insertion
            $insertQuery = "INSERT INTO team (name, region, big_team, caf_qualifier, league_type, venue_name) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $mysql->prepare($insertQuery);

                if ($insertStmt) {
                 $insertStmt->bind_param('ssssss', $name, $region, $big_team, $caf_qualifier, $league_type, $venue_name);
                 $insertStmt->execute();

                    if ($insertStmt->affected_rows > 0) {
                         $success = "Team Inserted Successfully";
                } else {
                 $err = "Error: Data Insertion Failed";
                }

                 $insertStmt->close();
            } else {
              $err = "Error: Statement Preparation Failed" . $mysql->error;
            }

        }

       // Close the check statement
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
    <div class="header  pb-8 pt-5 pt-md-8" style="min-height: 250px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
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
                        $query = "SELECT name, season FROM $tableName WHERE league_type='Second' "; // Add your WHERE conditions if needed
                        $result = $mysql->query($query);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $leagueName = $row['name'];
                            $seasonName= $row['season'];
                        } else {
                            $leagueName = "Unknown Competition"; // Provide a default name if no data is found
                            $seasonName = "Unkown Season";
                        }

                        // Close the result set
                        
                        ?>

                        <!-- Display the league name in your HTML -->
                        <h2 class="card-header">
                            Add Teams for <?php echo $leagueName; ?>  <?php echo $seasonName;?> Stage 4/5
                        </h2>


                 <div class="card-body">
                <!--Form-->
                <form method="post" enctype="multipart/form-data" autocomplete="off">
                  <div class="row">
                      <div class="form-group col-md-6">
                          <label for="name">Team Name</label>
                          <input type="text" required name="name" class="form-control" id="name" aria-describedby="emailHelp">
                      </div> 
                      
                      <div class="form-group col-md-6">
                          <label for="region">Team Region</label>
                          <input type="text" required name="region" class="form-control" id="region" aria-describedby="emailHelp">
                      </div> 
                  </div>

                  <div class="row">
                          <div class="form-group col-md-6">
                          <label for="big_team">Big Team</label>
                          <select required name="big_team" class="form-control" id="big_team">
                              <option value="no">no</option>
                          </select>
                      </div>

                    <div class="form-group col-md-6">
                      <label for="caf_qualifier">Caf Qualifier</label>
                      <select required name="caf_qualifier" class="form-control" id="caf_qualifier">
                          <option value="no">no</option>
                      </select>
                  </div>

              </div> 
    
              <div class="row">
              <div class="form-group col-md-6">
                    <label for="league_type" >League Type</label>
                    <select required name="league_type"  class="form-control" id="league_type">
                        
                        <option value="Second">Second</option>
                        

                    </select>
                 </div>
                  <div class="form-group col-md-6">
                      <label for="venuen_name">Team Venue</label>
                      <select required name="venue_name" class="form-control" id="venue_name">
                          <option value="">Select a Venue</option> <!-- Default empty option -->
                          <?php
                          //  database connection
                          include('inc/dbconn.php');
                          $query = "SELECT name FROM venue WHERE league_type='Second' ";
                          $result = $mysql->query($query);

                          if ($result) {
                              while ($row = $result->fetch_assoc()) {
                                  $venueName = $row['name'];
                                  echo "<option value=\"$venueName\">$venueName</option>";
                              }
                              $result->free(); // Free the result set
                          }
                          ?>
                      </select>
                  </div>
                  
            </div>

                    <div class="form-group">
                        <?php if (isset($err)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                       
                        <?php } ?>
                    </div>

              <button type="submit" name="reg_team" class="btn btn-primary">Add</button>
             
              
          </form>

                <!-- ./ Form -->
            </div>    
           </div>
          </div>
         </div>
       </div>
    </div>
  </html>
