<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  // Include utility functions if they're in a separate file
include('inc/utility_functions.php');
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
$result = $mysqli->query($query);
if ($row = $result->fetch_assoc()) {
    $startDate = new DateTime($row['start_date']);
    $endDate = new DateTime($row['end_date']);
} else {
    die("League dates not found.");
}
$result->free();


// Fetch Team Details
$teamQuery = "SELECT id, name, big_team, caf_qualifier, venue_name FROM team WHERE league_type = 'Premier'";
$teams = [];
if ($teamResult = $mysqli->query($teamQuery)) {
    while ($teamRow = $teamResult->fetch_assoc()) {
        $teams[$teamRow['id']] = $teamRow;
    }
}
if (empty($teams)) die("No teams found.");


// Fetch Venue Details
$venueQuery = "SELECT id, name, region, quality FROM venue";
$venues = [];
if ($venueResult = $mysqli->query($venueQuery)) {
    while ($venueRow = $venueResult->fetch_assoc()) {
        $venues[$venueRow['id']] = $venueRow;
    }
}


// Prepare Match Days Data
$matchDayQuery = "SELECT name, number_of_matches FROM day_match WHERE league_type = 'Premier'";
$matchDays = [];
if ($matchDayResult = $mysqli->query($matchDayQuery)) {
    while ($matchDayRow = $matchDayResult->fetch_assoc()) {
        $matchDays[$matchDayRow['name']] = $matchDayRow['number_of_matches'];
    }
}
// Fetch exceptional dates
$exceptionalDates = [];
$query = "SELECT name, start_date, end_date, break_type FROM exceptional_date";
if ($result = $mysqli->query($query)) {
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

function isDateExceptional($date, $exceptionalDates, $fixture) {
    foreach ($exceptionalDates as $exception) {
        $start = new DateTime($exception['start_date']);
        $end = new DateTime($exception['end_date']);
        // Optionally, extend this logic to consider the break_type and the teams in the fixture
        if ($date >= $start && $date <= $end) {
            return true; // Date is within an exceptional range
        }
    }
    return false; // Date is not exceptional
}

function getNextAvailableMatchDayTime(&$matchDays, DateTime $currentDate, &$venueUsageSchedule, $venueId) {
    // Assume matches can start at 16:00 and can be scheduled every 2 hours until 22:00
    $defaultStartTime = new DateTime('16:00:00');
    $maxTime = new DateTime('22:00:00');
    
    while (true) {
        $dayName = $currentDate->format('l'); // Get the day name
        if (isset($matchDays[$dayName])) {
            if (!isset($venueUsageSchedule[$currentDate->format('Y-m-d')])) {
                $venueUsageSchedule[$currentDate->format('Y-m-d')] = [];
            }
            // Check if the venue is already used on this day
            if (in_array($venueId, $venueUsageSchedule[$currentDate->format('Y-m-d')])) {
                $currentDate->modify('+1 day');
                continue; // Find the next available day
            }

            // Calculate the match time based on how many matches have been scheduled for this day
            $matchTime = (clone $defaultStartTime);
            for ($i = 0; $i < count($venueUsageSchedule[$currentDate->format('Y-m-d')]); $i++) {
                $matchTime->add(new DateInterval('PT2H')); // Add 2 hours for each scheduled match
                if ($matchTime > $maxTime) {
                    // If calculated match time exceeds the maximum allowed time, reset and move to the next day
                    $currentDate->modify('+1 day');
                    continue 2; // Continue outer while loop
                }
            }

            //If the venue is not used and the time is within the allowed range, schedule the match
            $venueUsageSchedule[$currentDate->format('Y-m-d')][] = $venueId; // Mark the venue as used for this day
            return [
                'date' => $currentDate->format('Y-m-d'),
                'time' => $matchTime->format('H:i:s'),
            ];
        } else {
            // If the day is not available for matches, move to the next day
            $currentDate->modify('+1 day');
        }
    }
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
                'venue_id' => $homeTeam['venue_name'], // Assuming venue_name is an ID
                'round' => 1
            ];
            // Round 2
            $fixtures[] = [
                'home_team_id' => $awayTeamId,
                'away_team_id' => $homeTeamId,
                'venue_id' => $awayTeam['venue_name'], // Assuming venue_name is an ID
                'round' => 2
            ];
        }
    }
}

//End Generation Double Round-Robin Fixtures


//scheduling logic 

foreach ($fixtures as &$fixture) {
    $foundDay = false;
    $currentDate = clone $startDate; // Assuming $startDate is defined as the start of the league

    while (!$foundDay && $currentDate <= $endDate) { // Assuming $endDate is defined as the end of the league
        $dayName = $currentDate->format('l');
        
        // Check if the fixture falls on an exceptional date
        if (isDateExceptional($currentDate, $exceptionalDates, $fixture)) {
            // Check if either team is a CAF qualifier
            if ($fixture['home_team_caf_qualifier'] === 'yes' || $fixture['away_team_caf_qualifier'] === 'yes') {
                // Mark fixture as TBO for CAF qualifiers
                $fixture['date'] = 'TBO'; // Use a special marker or NULL depending on your database schema
                $fixture['time'] = 'TBO'; // Use a special marker or NULL
                $foundDay = true; // Exit the loop as the fixture has been marked
                break; // No need to continue checking other days
            }
        } else if (isset($matchDays[$dayName])) {
            // Normal scheduling logic for non-exceptional days or non-CAF qualifier matches
            if ($matchesPerDay[$dayName]['count'] < $matchDays[$dayName]['number_of_matches']) {
                $foundDay = true;
                // Schedule the match normally for this day
                $fixture['date'] = $currentDate->format('Y-m-d');
                $fixture['time'] = $matchesPerDay[$dayName]['lastMatchTime']->format('H:i:s');
                
                // Increment the match count and adjust the time for the next match
                $matchesPerDay[$dayName]['count']++;
                $matchesPerDay[$dayName]['lastMatchTime']->modify('+120 minutes');
            }
        }
        
        if (!$foundDay) {
            $currentDate->modify('+1 day'); // Move to the next day
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
$stmt = $mysql->prepare("INSERT INTO fixture (home_team_id, away_team_id, venue_id, match_date, match_time, round, league_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo "Error preparing statement: " . $mysql->error;
    exit; // Consider a more nuanced error handling strategy as needed
}

// Loop through each fixture for insertion
foreach ($fixtures as $fixture) {
    // Fetch the venue ID for the home team
    $venueId = getHomeTeamVenueId($mysql, $fixture['home_team_id']);
    
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
    }
}

// Close the statement after all fixtures have been inserted
$stmt->close();


?>
