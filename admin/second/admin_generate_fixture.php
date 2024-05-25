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


// Fetch Competition Dates
$competitionQuery = "SELECT start_date, end_date FROM competition WHERE league_type = 'Premier'";
$competitionResult = $mysqli->query($competitionQuery);
if ($competitionRow = $competitionResult->fetch_assoc()) {
    $competitionStartDate = $competitionRow['start_date'];
    $competitionEndDate = $competitionRow['end_date'];
} else {
    die("No competition dates found.");
}

// Fetch Team Details
$teamQuery = "SELECT name, venue_name FROM team WHERE league_type = 'Premier'";
$teamResult = $mysqli->query($teamQuery);
if (!$teamResult || $teamResult->num_rows == 0) {
    die("No teams found.");
}

$teams = [];
while ($teamRow = $teamResult->fetch_assoc()) {
    $teams[] = $teamRow;
}

// Fetch Available Match Days and Their Capacities
$dayMatchQuery = "SELECT name, number_of_matches FROM day_match WHERE league_type = 'Premier'";
$dayMatchResult = $mysqli->query($dayMatchQuery);
$matchDays = [];
while ($dayMatchRow = $dayMatchResult->fetch_assoc()) {
    $matchDays[$dayMatchRow['name']] = [
        'total_capacity' => (int)$dayMatchRow['number_of_matches'],
        'matches_scheduled' => 0,
        'last_match_time' => '16:00:00' // Default time for the first match
    ];
}

// Determine the next available match day and time
function getNextAvailableMatchDayTime(&$matchDays, $currentDate) {
    $nextDate = $currentDate;
    do {
        $dayName = date('l', strtotime($nextDate));
        if (isset($matchDays[$dayName]) && $matchDays[$dayName]['matches_scheduled'] < $matchDays[$dayName]['total_capacity']) {
            $matchTime = $matchDays[$dayName]['last_match_time'];
            // Update the time for the next match on this day
            if ($matchDays[$dayName]['matches_scheduled'] > 0) {
                $nextMatchTime = date('H:i:s', strtotime($matchTime . ' + 120 minutes'));
                $matchDays[$dayName]['last_match_time'] = $nextMatchTime;
            }
            return array($nextDate, $matchDays[$dayName]['last_match_time']);
        }
        // Reset time for the next day
        $matchDays[$dayName]['last_match_time'] = '16:00:00';
        $nextDate = date('Y-m-d', strtotime("$nextDate +1 day"));
    } while (true);
}

// Prepare for match scheduling
$fixtures = [];
$matchDateTime = $competitionStartDate;

// Schedule each match
foreach ($teams as $homeTeam) {
    foreach ($teams as $awayTeam) {
        if ($homeTeam['name'] === $awayTeam['name']) {
            continue; // Teams don't play against themselves
        }

        // Find the next available match day and time
        list($matchDate, $matchTime) = getNextAvailableMatchDayTime($matchDays, $matchDateTime);

        // Format the date to include the day name, e.g., 'Monday, 2024-02-21'
        $formattedMatchDate = date('l, Y-m-d', strtotime($matchDate));

        // Add fixture with venue name
        $fixtures[] = [
            'home_team' => $homeTeam['name'],
            'away_team' => $awayTeam['name'],
            'date' => $formattedMatchDate,
            'time' => $matchTime,
            'venue_name' => $homeTeam['venue_name'], // Include the home team's venue
            'league_type' =>$league_type['Premier']
        ];

        // Update matchDays with the scheduled match
        $dayName = date('l', strtotime($matchDate));
        $matchDays[$dayName]['matches_scheduled']++;

        // Prepare for the next match scheduling
        $matchDateTime = $matchDate;
    }
}


// Insert fixtures into the database
$insertQuery = $mysqli->prepare("INSERT INTO fixture (home_team_name, away_team_name,venue_name, date, time,league_type) VALUES (?, ?, ?, ?, ?)");
foreach ($fixtures as $fixture) {
    $insertQuery->bind_param("sssss", $fixture['home_team'], $fixture['away_team'],$fixture['venue_name'],$fixture['date'], $fixture['time'], $fixture['league_type']);
    if (!$insertQuery->execute()) {
        echo "Error inserting fixture: " . $mysqli->error;
    }
}


$insertQuery->close();


echo "Fixtures generated and inserted successfully.";

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