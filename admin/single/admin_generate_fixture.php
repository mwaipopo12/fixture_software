<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];

  if(isset($_GET['delete_id']))
  {
        $id=intval($_GET['delete_id']);
        $adn="DELETE FROM fixture WHERE id = ?";
        $stmt= $mysql->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();  
  
          if($stmt)
          {
            $success = "Match Details Deleted";
          }
            else
            {
                $err = "Try Again Later";
            }
    }


// Clear any existing fixtures
$sql = "TRUNCATE TABLE fixture WHERE league_type= 'Premier'  ";



// Step 2: Retrieve necessary information from the database

// Fetch competition start and end dates for the Premier league
$competitionQuery = "SELECT start_date, end_date FROM competition WHERE league_type = 'Premier'";
$competitionResult = $mysql->query($competitionQuery);
$competitionRow = $competitionResult->fetch_assoc();
$competitionStartDate = $competitionRow['start_date'];
$competitionEndDate = $competitionRow['end_date'];

// Fetch day match details for the Premier league
$dayMatchQuery = "SELECT name, number_of_matches FROM day_match WHERE league_type = 'Premier'";
$dayMatchResult = $mysql->query($dayMatchQuery);
$dayMatchRow = $dayMatchResult->fetch_assoc();
$dayMatchName = $dayMatchRow['name'];
$numberOfMatches = $dayMatchRow['number_of_matches'];

// Fetch team details for the Premier league with venue region
$teamQuery = "SELECT t.name, t.big_team, t.caf_qualifier, v.region AS venue_region 
              FROM team t
              JOIN venue v ON t.venue_name = v.id
              WHERE t.league_type = 'Premier'";
$teamResult = $mysql->query($teamQuery);
$teamsCount = $teamResult->num_rows;

// Step 3: Implement the double round-robin algorithm

$teams = array();
while ($teamRow = $teamResult->fetch_assoc()) {
    $teams[] = $teamRow;
}

// Initialize a counter to track consecutive matches for each team
$consecutiveMatches = array();

$fixtures = array();

for ($i = 0; $i < $teamsCount - 1; $i++) {
    for ($j = $i + 1; $j < $teamsCount; $j++) {
        // Home and away for each match
        for ($k = 0; $k < 2; $k++) {
            echo "i: $i, j: $j, k: $k\n"; // Debug statement

            // Determine home and away teams based on the iteration
            $homeTeamIndex = ($k == 0) ? $i : $j;
            $awayTeamIndex = ($k == 0) ? $j : $i;

            // Check if a 90-minute gap is needed
            if ($consecutiveMatches[$teams[$homeTeamIndex]['name']] > 0) {
                $competitionStartDate = date('Y-m-d H:i:s', strtotime("$competitionStartDate +90 minutes"));
            }

            // Format the date with the day name
            $formattedDate = date('l, Y-m-d', strtotime($competitionStartDate));
            $formattedTime = date('H:i:s', strtotime($competitionStartDate));

            $fixtures[] = array(
                'home_team' => $teams[$homeTeamIndex]['name'],
                'away_team' => $teams[$awayTeamIndex]['name'],
                'venue_region' => $teams[$homeTeamIndex]['region'],
                'date' => $formattedDate,
                'time' => $formattedTime,
                'league_type' => 'Premier',
            );

            // Update the consecutive matches counter for each team
            if (!isset($consecutiveMatches[$teams[$homeTeamIndex]['name']])) {
                $consecutiveMatches[$teams[$homeTeamIndex]['name']] = 1;
            } else {
                $consecutiveMatches[$teams[$homeTeamIndex]['name']]++;
            }

            if (!isset($consecutiveMatches[$teams[$awayTeamIndex]['name']])) {
                $consecutiveMatches[$teams[$awayTeamIndex]['name']] = 1;
            } else {
                $consecutiveMatches[$teams[$awayTeamIndex]['name']]++;
            }

            // Reset the consecutive matches counter if it exceeds 2
            if ($consecutiveMatches[$teams[$homeTeamIndex]['name']] > 2) {
                $consecutiveMatches[$teams[$homeTeamIndex]['name']] = 1;
            }

            if ($consecutiveMatches[$teams[$awayTeamIndex]['name']] > 2) {
                $consecutiveMatches[$teams[$awayTeamIndex]['name']] = 1;
            }

            // Update the date for the next match
            $competitionStartDate = date('Y-m-d H:i:s', strtotime("$competitionStartDate +90 minutes"));

            // Output relevant information for debugging
            echo "Home Team: {$teams[$homeTeamIndex]['name']}, Away Team: {$teams[$awayTeamIndex]['name']}, Date: $formattedDate, Time: $formattedTime\n";
        }
    }
}

// Step 4: Schedule matches based on the day match name

foreach ($fixtures as $match) {
    // Insert the match into the fixture table with the specific date and time
    $insertQuery = "INSERT INTO fixture (home_team_name, away_team_name,region, date, time, league_type)
                    VALUES (
                        '{$match['home_team']}',
                        '{$match['away_team']}',
                        '{$match['venue_region']}',
                        '{$match['date']}',
                        '{$match['time']}',
                        '{$match['league_type']}'
                    )";
    // Execute the query and check for errors
    if ($mysql->query($insertQuery) === FALSE) {
        echo "Error inserting fixture: " . $mysql->error;
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
    <div class="header  pb-8 pt-5 pt-md-8" style="min-height: 300px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
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
                        $query = "SELECT name,season FROM $tableName WHERE league_type='Premier'     "; // Add your WHERE conditions if needed
                        $result = $mysql->query($query);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $leagueName = $row['name'];
                            $seasonName= $row['season'];
                        } else {
                            $leagueName = "Unknown League"; // Provide a default name if no data is found
                            $seasonName = "Unkown Season";
                        }

                        
                        ?>

                        <!-- Display the league name in your HTML -->
                        <h2 class="card-header">
                           Generated Fixture for <?php echo $leagueName; ?> <?php echo $seasonName;?> 
                        </h2>
                <div class="card-body">
                    <div class="table-responsive">
                    
                        <table class="table align-items-center table-flush">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">DATE</th>
                                <th scope="col">HOME TEAM</th>
                                <th scope="col">AWAY TEAM</th>
                                <th scope="col">TIME</th>
                                <th scope="col">Venue </th>
                                <th scope="col">REGION</th>
                                <th scope="col">Action<th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                
                                    $ret="SELECT * FROM fixture WHERE league_type = 'Premier' "; 
                                    $stmt= $mysql->prepare($ret) ;
                                    $stmt->execute() ;
                                    $res=$stmt->get_result();
                                    $cnt=1;
                                    while($row=$res->fetch_object())
                                    {
                            ?>
                                <tr>
                                <th scope="row">
                                    <?php echo $cnt;?>
                                </th>
                                <td>
                                    <?php echo $row->date;?>
                                </td>
                                                        
                                <td>
                                    <?php echo $row->home_team_name;?>
                                </td>
                                
                                <td>
                                    <?php echo $row->away_team_name;?>
                                </td>
                                <td>
                                    <?php echo $row->time;?>
                                </td>
                                <td>
                                    <?php echo $row->venue_name;?>
                                </td>
                                <td>
                                    <?php echo $row->region;?>
                                </td>
                                <td>
                                    
                                        <a href  ="admin_update_team.php?id=<?php echo $row->id;?>&team_nnumber=<?php echo $row->team_region;?>" class="badge badge-primary">
                                            <i class="fa fa-edit"></i> <i class="fa fa-user"></i> 
                                                Update
                                        </a>
                                        <a href  ="admin_manage_team.php?delete_id=<?php echo $row->id;?>" class="badge badge-danger">
                                            <i class="fa fa-trash"></i> <i class="fa fa-user"></i>
                                                Delete
                                        </a>        
                                </td>
                                </tr>
                            <?php $cnt = 1+$cnt; }?>
                            </tbody>
                        </table>
                    </div>
                </div>    
            </div>
        </div>
          
    </div>
  </div>
 
  
</body>

</html>