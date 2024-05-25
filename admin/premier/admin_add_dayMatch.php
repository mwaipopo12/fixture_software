<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];

  
  if (isset($_POST['reg_day_match'])) {

    $name = $_POST['name']; // This should be a string value
    $number_of_matches = $_POST['number_of_matches']; // This should be an integer
    $league_type = $_POST['league_type'];

    // SQL query to insert captured values
    $query = "INSERT INTO day_match (name, number_of_matches, league_type) VALUES (?, ?, ?)";
    $stmt = $mysql->prepare($query);

    // Check if the statement preparation was successful
    if ($stmt) {
        $stmt->bind_param('sis', $name, $number_of_matches, $league_type); // 'sis' indicates two strings and an integer
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success = "Day Match Data Inserted Successfully";
        } else {
            if ($mysql->errno == 1062) { // 1062 is the MySQL error code for duplicate entry
                $err = "Error: Duplicate entry. Day Match with the same name and league type already exists.";
            } else {
                $err = "Error: Data Insertion Failed";
            }
        }

        $stmt->close(); // Close the statement
    } else {
        $err = "Error: Statement Preparation Failed";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<?php include("inc/head.php");?>

<body class="">
    <!-- Sidebar -->
    <?php include("inc/sidebar.php"); ?>

    <div class="main-content">
        <!-- Navbar -->
        <?php include("inc/nav.php"); ?>
        <!-- End Navbar -->
        <!-- Header -->
        <div class="header pb-8 pt-5 pt-md-8" style="min-height: 270px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
            <span class="mask bg-gradient-default opacity-5"></span>
        </div>

        <div class="container-fluid mt--7">

            <div class="row">
                <div class="card col-md-12">
                    <?php
                    // Database connection 
                    include('inc/dbconn.php');
                    // Specify the table name where the league name is stored
                    $tableName = "competition";

                    // SQL query to fetch the league name from the specified table
                    $query = "SELECT name, league_type, season FROM $tableName WHERE league_type='Premier'  "; // Add your WHERE conditions if needed
                    $result = $mysql->query($query);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $leagueName = $row['name'];
                        $league_type = $row['league_type'];
                        $seasonName = $row['season'];
                    } else {
                        $leagueName = "Unknown League"; // Provide a default name if no data is found
                        $league_type = "Unknown League type";
                        $seasonName = "Unknown Season";
                    }
                    ?>

                    <!-- Display the league name in your HTML -->
                    <h2 class="card-header">
                        Day Selection for <?php echo $leagueName; ?> <?php echo $league_type; ?> <?php echo $seasonName; ?> Stage 2/5
                    </h2>

                    <div class="card-body">
                        <!-- Form -->
                        <form method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return showPopUp();">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">Select a Day</label>
                                    <select required name="name" class="form-control" id="name">
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="number_of_matches">Number of Matches</label>
                                    <input type="number" required name="number_of_matches" class="form-control" id="number_of_matches" aria-describedby="emailHelp">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="league_type">League Type</label>
                                    <select required name="league_type" class="form-control" id="league_type">
                                        <option value="Premier">Premier</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                        <?php if (isset($err)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                       
                        <?php } ?>
                    </div>
                            <button type="submit" name="reg_day_match" class="btn btn-primary">Add</button>
                        </form>
                        <!-- ./ Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript function to show a pop-up window
        function showPopUp() {
            <?php
            if (isset($success)) {
                echo 'alert("' . $success . '");';
            } elseif (isset($err)) {
                echo 'alert("' . $err . '");';
            }
            ?>
            return true; // Allow form submission
        }
    </script>

</body>

</html>
