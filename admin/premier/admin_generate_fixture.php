<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  // Include utility functions 
include('inc/utility_function.php');
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

// Fetch League Dates
$query = "SELECT start_date, end_date FROM competition WHERE league_type = 'Premier' LIMIT 1";
$result = $mysql->query($query);
if ($row = $result->fetch_assoc()) {
    $startDate = new DateTime($row['start_date']);
    $endDate = new DateTime($row['end_date']);
} else {
    die("League dates not found.");
}
$result->free();


// Fetch Team Details
$teamQuery = "SELECT id, name, big_team, caf_qualifier, venue_id FROM team WHERE league_type = 'Premier'";
$teams = [];
if ($teamResult = $mysql->query($teamQuery)) {
    while ($teamRow = $teamResult->fetch_assoc()) {
        $teams[$teamRow['id']] = $teamRow;
    }
}
if (empty($teams)) die("No teams found.");


// Fetch Venue Details
$venueQuery = "SELECT id, name, region, quality FROM venue";
$venues = [];
if ($venueResult = $mysql->query($venueQuery)) {
    while ($venueRow = $venueResult->fetch_assoc()) {
        $venues[$venueRow['id']] = $venueRow;
    }
}


// Prepare Match Days Data
$matchDayQuery = "SELECT name, number_of_matches FROM day_match WHERE league_type = 'Premier'";
$matchDays = [];
if ($matchDayResult = $mysql->query($matchDayQuery)) {
    while ($matchDayRow = $matchDayResult->fetch_assoc()) {
        $matchDays[$matchDayRow['name']] = $matchDayRow['number_of_matches'];
    }
}
// Fetch exceptional dates
$exceptionalDates = [];
$query = "SELECT name, start_date, end_date, break_type FROM exceptional_date";
if ($result = $mysql->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $exceptionalDates[] = $row;
    }
    $result->free();
}

//Helper functions

function venueHasLights($venueId, $venues) {
    return isset($venues[$venueId]) && $venues[$venueId]['quality'] === 'light';
}

function isBigMatch($homeTeamId, $awayTeamId, $teams) {
    return $teams[$homeTeamId]['big_team'] === 'yes' && $teams[$awayTeamId]['big_team'] === 'yes';
}


function findNextMatchDate(DateTime $currentDate, $dayName, &$matchDays, &$venueUsageSchedule, $venueId) {
    while (true) {
        if ($currentDate->format('l') === $dayName) {
            // Check if the venue is available and not exceeding the matches per day
            $dateKey = $currentDate->format('Y-m-d');
            if (!isset($venueUsageSchedule[$dateKey])) {
                $venueUsageSchedule[$dateKey] = [];
            }
            if (count($venueUsageSchedule[$dateKey]) < $matchDays[$dayName]) {
                // Venue is available for scheduling
                return $currentDate;
            }
        }
        $currentDate->modify('+1 day');
    }
}


function isDateExceptional(DateTime $date, $exceptionalDates) {
    foreach ($exceptionalDates as $exception) {
        $start = new DateTime($exception['start_date']);
        $end = new DateTime($exception['end_date']);
        if ($date >= $start && $date <= $end) {
            return $exception['break_type']; // Return the type of break
        }
    }
    return false; // Date is not exceptional
}


// End Helper functions

//Generate Double Round-Robin Fixtures
$fixtures = [];
foreach ($teams as $homeTeamId => $homeTeam) {
    foreach ($teams as $awayTeamId => $awayTeam) {
        if ($homeTeamId !== $awayTeamId) {
            // Round 1
            $fixtures[] = [
                'home_team_id' => $homeTeamId,
                'away_team_id' => $awayTeamId,
                'venue_id' => $homeTeam['venue_id'], // Assuming venue_name is an ID
                'round' => 1
            ];
            // Round 2
            $fixtures[] = [
                'home_team_id' => $awayTeamId,
                'away_team_id' => $homeTeamId,
                'venue_id' => $awayTeam['venue_id'], // Assuming venue_name is an ID
                'round' => 2
            ];
        }
    }
}

//End Generation Double Round-Robin Fixtures


// Initialize tracking for scheduled matches per day
foreach ($matchDays as $day => $numMatches) {
    $matchDays[$day] = [
        'number_of_matches' => $numMatches,
        'count' => 0,
        'lastMatchTime' => new DateTime('16:00:00')
    ];
}

function getNextAvailableMatchDayTime(&$matchDays, DateTime $currentDate, &$venueUsageSchedule, $venueId, $venues) {
    $defaultStartTime = new DateTime('16:00:00'); // Matches start at 16:00 by default
    $matchInterval = new DateInterval('PT120M'); // 120 minutes interval between matches

    while ($currentDate <= $GLOBALS['endDate']) {
        $dayName = $currentDate->format('l');
        $dateKey = $currentDate->format('Y-m-d');

        if (isset($matchDays[$dayName])) {
            if (!isset($venueUsageSchedule[$dateKey])) {
                $venueUsageSchedule[$dateKey] = [];
            }
            
            // Ensure the venue is not double-booked
            if (!in_array($venueId, $venueUsageSchedule[$dateKey])) {
                $matchTime = clone $defaultStartTime;
                // Adjust the match time based on the number of matches already scheduled for the day
                for ($i = 0; $i < count($venueUsageSchedule[$dateKey]); $i++) {
                    $matchTime->add($matchInterval);
                }

                // Check for lighting if the match time is in the evening
                if ($matchTime->format('H') >= 18 && !venueHasLights($venueId, $venues)) {
                    // If no lights, attempt to find another venue or skip to next available slot or day
                    $currentDate->modify('+1 day');
                    continue;
                }

                // Schedule the match if the venue has lights or the match is earlier in the day
                $venueUsageSchedule[$dateKey][] = $venueId; // Mark the venue as used for this date
                return [
                    'date' => $dateKey,
                    'time' => $matchTime->format('H:i:s'),
                ];
            }
        }

        $currentDate->modify('+1 day');
        $defaultStartTime = new DateTime('16:00:00'); // Reset the start time for a new day
    }

    return null; // If no suitable date is found
}



//scheduling logic
$venueUsageSchedule = []; // Track venue usage to prevent double-booking
$scheduledBigMatches = []; // Format: ['YYYY-MM' => true] to track big matches scheduled per month

foreach ($fixtures as &$fixture) {
    $currentDate = clone $startDate;
    $foundDay = false;

    while (!$foundDay && $currentDate <= $endDate) {
        $dayName = $currentDate->format('l');
        $monthKey = $currentDate->format('Y-m');
        $breakType = isDateExceptional($currentDate, $exceptionalDates, $fixture); // Make sure this function is correctly defined

        if ($breakType === 'all_teams') {
            // Skip all match scheduling within this break period for all teams
            $currentDate->modify('+1 day'); // Move to the next day
            continue; // Skip the rest of this iteration and check the next day
        } elseif ($breakType === 'caf_qualifier' && ($fixture['home_team_caf_qualifier'] === 'yes' || $fixture['away_team_caf_qualifier'] === 'yes')) {
            // Specific handling for CAF qualifiers during their break period
            $fixture['date'] = 'TBO';
            $fixture['time'] = 'TBO';
            $foundDay = true; // Ensure loop exits if date is set to TBO
            continue; // Move to the next day, or possibly break; based on your desired logic
        }

        if (isBigMatch($fixture['home_team_id'], $fixture['away_team_id'], $teams)) {
            if (($dayName === 'Saturday' || $dayName === 'Sunday') && !isset($scheduledBigMatches[$monthKey])) {
                // Schedule the big match if it's a weekend and no big match has been scheduled this month
                $fixture['date'] = $currentDate->format('Y-m-d');
                $fixture['time'] = '15:00:00'; // Example time for big matches
                $scheduledBigMatches[$monthKey] = true; // Mark this month as having a scheduled big match
                $foundDay = true;
            }
        } else if (isset($matchDays[$dayName])) {
            $scheduledInfo = getNextAvailableMatchDayTime($matchDays, $currentDate, $venueUsageSchedule, $fixture['venue_id'], $venues);
            if ($scheduledInfo) {
                $fixture['date'] = $scheduledInfo['date'];
                $fixture['time'] = $scheduledInfo['time'];
                $foundDay = true;
            }
        }

        if (!$foundDay) {
            $currentDate->modify('+1 day');
        }
    }
}




$fixtures = []; // Assume this is filled with your preliminary fixtures data

foreach ($fixtures as &$fixture) {
    $dayName = array_search(max($matchDays), $matchDays); // Find the day with maximum matches allowed
    $venueId = $fixture['venue_id']; // Assuming you have venue_id in your fixture structure

    $currentDate = clone $startDate; // Start from the league start date
    $nextAvailableDate = findNextMatchDate($currentDate, $dayName, $matchDays, $venueUsageSchedule, $venueId);

    // Set the date and time for the fixture
    $fixture['date'] = $nextAvailableDate->format('Y-m-d');
    $fixture['time'] = '16:00:00'; // Set a default time or adjust based on your logic

    // Update venueUsageSchedule for the day
    $venueUsageSchedule[$nextAvailableDate->format('Y-m-d')][] = $venueId;
}

// Prepare the statement for inserting fixtures into the database
$stmt = $mysql->prepare("INSERT INTO fixture (home_team_id, away_team_id, venue_id, date, time, round, league_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo "Error preparing statement: " . $mysql->error;
    exit; // Consider a more nuanced error handling strategy as needed
}

// Loop through each fixture for insertion
foreach ($fixtures as $fixture) {
    // Presuming $fixture array has all the necessary information, including dynamically set league type
    $leagueType = $fixture['league_type']; // Use the league type specified in each fixture
    
    // Debug: Output fixture being inserted
    error_log("Inserting fixture: " . json_encode($fixture));

    // Fetch the venue ID for the home team
    $venueId = getHomeTeamVenueId($mysql, $fixture['home_team_id']);
    
    $league_type='Premier';
    // Bind and execute the insertion statement for each fixture
    $stmt->bind_param("iiississ", 
        $fixture['home_team_id'], 
        $fixture['away_team_id'], 
        $venueId,
        $fixture['date'], 
        $fixture['time'],
        $fixture['round'],
        $fixture['league_type'], // Assuming dynamic league types per fixture
        $fixture['status'] // Assuming you have a status set for each fixture
    );

    if (!$stmt->execute()) {
        echo "Error inserting fixture: " . $stmt->error;
    } else {
        // Debug: Success message
        error_log("Fixture inserted successfully.");
    }
}

// Close the statement after all fixtures have been inserted
$stmt->close();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("inc/head.php"); ?>
</head>
<body>
    <!--Sidebar-->
    <?php include("inc/sidebar.php"); ?>
  
    <div class="main-content">
        <!-- Navbar -->
        <?php include("inc/nav.php"); ?>
        <!-- End Navbar -->
        <!-- Header -->
        <div class="header pb-8 pt-5 pt-md-8" style="min-height: 300px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
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
                    $query = "SELECT name,season FROM $tableName WHERE league_type='Premier'  "; 
                    $result = $mysql->query($query);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $leagueName = $row['name'];
                        $seasonName= $row['season'];
                    } else {
                        $leagueName = "Unknown League"; // Provide a default name if no data is found
                        $seasonName = "Unkown Season";
                    }

                    $query = "SELECT f.id, f.date, f.time, ht.name AS home_team_name, at.name AS away_team_name, v.name AS venue_name, f.round, f.league_type FROM fixture f INNER JOIN team ht ON f.home_team_id = ht.id INNER JOIN team at ON f.away_team_id = at.id INNER JOIN venue v ON f.venue_id = v.id WHERE f.league_type = 'Premier' ORDER BY f.date, f.time";

                    $res = $mysql->query($query);
                    ?>

                    <!-- Display the league name -->
                    <h2 class="card-header">Generated Fixture</h2>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Home Team</th>
                                    <th scope="col">Away Team</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Venue</th>
                                    <th scope="col">Round</th>
                                    <th scope="col">League Type</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $res->fetch_assoc()): ?>
                                    <tr>
                                        <th scope="row"><?php echo $row['id']; ?></th>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['home_team_id']; ?></td>
                                        <td><?php echo $row['away_team_id']; ?></td>
                                        <td><?php echo $row['time']; ?></td>
                                        <td><?php echo $row['venue_id']; ?></td>
                                        <td><?php echo $row['round']; ?></td>
                                        <td><?php echo $row['league_type']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td>
                                            <!-- Action buttons -->
                                            <a href="admin_update_team.php?id=<?php echo $row['id']; ?>" class="badge badge-primary">Update</a>
                                            <a href="admin_manage_team.php?delete_id=<?php echo $row['id']; ?>" class="badge badge-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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
