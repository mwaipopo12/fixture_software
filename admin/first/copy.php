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
        $adn="DELETE FROM fixture_premier WHERE id = ?";
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
$sql = "TRUNCATE TABLE fixture_premier";
$mysql->query($sql);
  
// Fetch the start_date and end_date of the league from the 'league_premier' table
$leagueStartDate = "";
$leagueEndDate = "";

$sql = "SELECT start_date, end_date FROM league_premier";
$result = $mysql->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $leagueStartDate = $row['start_date'];
    $leagueEndDate = $row['end_date'];
} else {
    echo "No league found in the 'league_premier' table.";
}

$sql = "SELECT name, region FROM venue_premier";
$result = $mysql->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $teamVenueNames = $row['name'];
    $teamRegions = $row['region'];
} else {
    echo "No league found in the 'league_premier' table.";
}

// Fetch team names, venue names, and regions
$teams = [];
$teamVenueNames = [];
$teamRegions = [];

$sql = "SELECT t.name, t.venue_premier_name, v.region FROM team_premier t
        JOIN venue_premier v ON t.venue_premier_name = v.name";
$result = $mysql->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row['name'];
        $teamVenueNames[] = $row['venue_premier_name'];
        $teamRegions[] = $row['region'];
    }
} else {
    echo "No teams found in the 'team_premier' and 'venue_premier' tables.";
}

// Fetch day names and the number of matches from the 'day_match' table
$days = [];
$numberMatches = [];
$sql = "SELECT name, number_match FROM day_match";
$result = $mysql->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $days[] = $row['name'];
        $numberMatches[] = $row['number_match'];
    }
} else {
    echo "No day matches found in the 'day_match' table.";
}

// Calculate the number of weeks between the start and end date
$startDate = strtotime($leagueStartDate);
$endDate = strtotime($leagueEndDate);
$weeks = ceil(($endDate - $startDate) / (7 * 24 * 60 * 60));

// Shuffle the teams array to randomize the order of matches
shuffle($teams);

// Generate the round-robin fixture with one match per week
$fixture = generateRoundRobin($teams, $weeks, $leagueStartDate, $leagueEndDate, $teamVenueNames, $teamRegions, $days, $numberMatches);
// Initialize the time to 16:00:00 for the first match
$time = strtotime($leagueStartDate . " 16:00:00");

foreach ($fixture as $match) {
    $homeTeam = $match['home_team'];
    $awayTeam = $match['away_team'];
    $venue = $match['venue_premier_name']; // Venue based on the match
    $region = $match['region']; // Region based on the match

    // Format the date
    $date = $match['match_date'];
    // Format the time
    $formattedTime = date("H:i:s", $time);

    // Insert match data into the 'fixture_premier' table
    $sql = "INSERT INTO fixture_premier (home_team, away_team, venue_premier_name, venue_premier_region, date, time) VALUES ('$homeTeam', '$awayTeam', '$venue', '$region', '$date', '$formattedTime')";

    if ($mysql->query($sql) !== true) {
        echo "Error: " . $sql . "<br>" . $mysql->error;
    }

    // Increment the time by 120 minutes (2 hours) for the next match on a different day
    $time += 7200; // 7200 seconds = 2 hours
}


function generateRoundRobin($teams, $weeks, $startDate, $endDate, $teamVenueNames, $teamRegions, $days, $numberMatches) {
    $fixture = [];
    $numTeams = count($teams);

    for ($week = 0; $week < $weeks; $week++) {
        $matchDate = date("Y-m-d", strtotime($startDate . " + " . ($week * 7) . " days"));

        for ($dayIndex = 0; $dayIndex < count($days); $dayIndex++) {
            $matchesPerDay = $numberMatches[$dayIndex];

            for ($i = 0; $i < $matchesPerDay; $i++) {
                $teamsForDay = $teams;
                shuffle($teamsForDay); // Shuffle teams for variety

                for ($j = 0; $j < $numTeams; $j++) {
                    if ($j < $matchesPerDay) {
                        $match = [
                            'home_team' => $teamsForDay[$j],
                            'away_team' => $teamsForDay[$numTeams - $j - 1],
                            'match_date' => $matchDate,
                            'venue_premier_name' => $teamVenueNames[array_search($teamsForDay[$j], $teams)],
                            'region' => $teamRegions[array_search($teamsForDay[$j], $teams)],
                        ];
                        $fixture[] = $match;
                    }
                }

                $matchDate = date("Y-m-d", strtotime($matchDate . " + 7 days")); // Move to the next week
            }
        }
    }

    return $fixture;
}

?>


<!DOCTYPE html>
<html lang="en">

<?php include("inc/head.php");?>

<body class="">
 <!--Sidebar-->
 

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
                        $tableName = "league_premier";

                        // SQL query to fetch the league name from the specified table
                        $query = "SELECT name,season FROM $tableName"; // Add your WHERE conditions if needed
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
                                
                                    $ret="SELECT * FROM fixture_premier "; 
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
                                    <?php echo $row->home_team;?>
                                </td>
                                
                                <td>
                                    <?php echo $row->away_team;?>
                                </td>
                                <td>
                                    <?php echo $row->time;?>
                                </td>
                                <td>
                                    <?php echo $row->venue_premier_name;?>
                                </td>
                                <td>
                                    <?php echo $row->venue_premier_region;?>
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