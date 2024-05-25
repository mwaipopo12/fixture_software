

<?php

// Initialize variables to hold the start and end dates
$startDate = '';
$endDate = '';

// SQL query to fetch start and end dates for Premier league
$query = "SELECT start_date, end_date FROM competition WHERE league_type = 'Premier' LIMIT 1";

if ($result = $mysql->query($query)) {
    if ($row = $result->fetch_assoc()) {
        $startDate = $row['start_date'];
        $endDate = $row['end_date'];
    }
    $result->free();
}


// Fetch Team Details
$teamQuery = "SELECT name,big_team,caf_qualifier, venue_name FROM team WHERE league_type = 'Premier'";
if ($teamResult = $mysql->query($teamQuery)) {
    if ($teamResult->num_rows == 0) {
        die("No teams found.");
    }

    $teams = [];
    while ($teamRow = $teamResult->fetch_assoc()) {
        $teams[] = $teamRow;
    }
} else {
    die("Error fetching team details: " . $mysql->error);
}

// SQL query to fetch venue details for teams in the Premier league
$query = "SELECT v.id, v.name, v.region, v.quality
          FROM venue AS v
          JOIN team AS t ON v.name = t.venue_name
          WHERE t.league_type = 'Premier'
          GROUP BY v.id"; // Group by to avoid duplicate venues if multiple teams share the same venue


$venues = []; // Array to store venue information

if ($result = $mysql->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $venues[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'region' => $row['region'],
            'quality' => $row['quality']
        ];
    }
    $result->free();
} else {
    // Handle error; for example, log it or display a message
    echo "Error fetching venue information: " . $mysql->error;
}


// Initialize an array to hold day and match information
$matchDays = [];

// SQL query to fetch day names and number of matches for Premier league type
$query = "SELECT name, number_of_matches FROM day_match WHERE league_type = 'Premier'";

if ($result = $mysql->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $matchDays[] = $row;
    }
    $result->free();
}

$venueUsageSchedule = []; // Format: ['YYYY-MM-DD' => ['venue_id1', 'venue_id2'], ...]

function scheduleMatch(&$fixture, &$venueUsageSchedule, &$matchDays, &$teams, &$venues) {
    $currentDate = new DateTime($fixture['date']);
    $endDate = new DateTime($GLOBALS['endDate']);

    while ($currentDate <= $endDate) {
        $matchDate = $currentDate->format('Y-m-d');
        $dayOfWeek = $currentDate->format('l');

        // Check for big match and weekend scheduling
        if (isBigMatch($fixture['home_team_id'], $fixture['away_team_id'], $teams) && !in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
            $currentDate->modify('+1 day');
            continue; // Skip to the next day if it's a big match and not on a weekend
        }

        // Ensure venue with lights for evening matches
        $matchTime = getNextMatchTime($dayOfWeek, $GLOBALS['matchDays']); // getNextMatchTime needs to be implemented
        if (substr($matchTime, 0, 2) >= 18 && !venueHasLights($fixture['venue_id'], $venues)) {
            $currentDate->modify('+1 day');
            continue; // Skip to the next day if the match is in the evening but the venue has no lights
        }

        if (isVenueAvailable($fixture['venue_id'], $matchDate, $venueUsageSchedule)) {
            // Venue is available, finalize scheduling
            $fixture['date'] = $matchDate;
            $fixture['time'] = $matchTime;
            $venueUsageSchedule[$matchDate][] = $fixture['venue_id'];
            break;
        }

        // If the venue is not available or conditions not met, check the next day
        $currentDate->modify('+1 day');
    }
}


$currentDate = new DateTime($startDate);
$scheduledMatches = []; // Keep track of matches scheduled on each date

foreach ($teams as $homeTeam) {
    foreach ($teams as $awayTeam) {
        if ($homeTeam['name'] === $awayTeam['name']) {
            continue; // Skip self-matches
        }

        // function getNextMatchDateTime(&$matchDays, DateTime $currentDate) {
        //     // Define default match time
        //     $defaultMatchTime = new DateTime ('16:00:00');
        
        //     // Loop through match days starting from the current date
        //     while (true) {
        //         $dayName = $currentDate->format('l'); // 'Monday', 'Tuesday', etc.
                
        //         if (isset($matchDays[$dayName])) {
        //             $dayInfo = &$matchDays[$dayName];
        //             if ($dayInfo['matches_scheduled'] < $dayInfo['number_of_matches']) {
        //                 // If not all match slots are filled for this day
        //                 $matchTime = (clone $defaultMatchTime)->add(new DateInterval('PT' . ($dayInfo['matches_scheduled'] * 120) . 'M'))->format('H:i:s');
        //                 $dayInfo['matches_scheduled']++;
        
        //                 return [
        //                     'date' => $currentDate->format('Y-m-d'),
        //                     'time' => $matchTime,
        //                 ];
        //             }
        //             // Reset for the next available day if the current day is fully booked
        //             $dayInfo['matches_scheduled'] = 0; // Reset if you loop over multiple weeks
        //         }
        
        //         // Move to the next day
        //         $currentDate->modify('+1 day');
        //     }
        // }
        

        // function isVenueAvailable($venueId, $matchDate, &$venueUsageSchedule) {
        //     // Check if the venue is already scheduled for the given date
        //     if (isset($venueUsageSchedule[$matchDate])) {
        //         return !in_array($venueId, $venueUsageSchedule[$matchDate]);
        //     }
        
        //     // If the date is not in the schedule, the venue is available
        //     return true;
        // }
        
        // function isBigMatch($homeTeamId, $awayTeamId, $teams) {
        //     // Find the 'big_team' status for both the home and away teams
        //     $homeTeamBig = $awayTeamBig = false;
        //     foreach ($teams as $team) {
        //         if ($team['id'] == $homeTeamId && $team['big_team'] === 'yes') {
        //             $homeTeamBig = true;
        //         }
        //         if ($team['id'] == $awayTeamId && $team['big_team'] === 'yes') {
        //             $awayTeamBig = true;
        //         }
        //     }
            
        //     // A match is considered big if both teams are big teams
        //     return $homeTeamBig && $awayTeamBig;
        // }
        
        function getNextAvailableMatchDayTime(&$matchDays, DateTime $currentDate, &$venueUsageSchedule, $venueId) {
            // Assume matches can start at 16:00 and can be scheduled every 2 hours until 22:00
            $defaultStartTime = new DateTime('16:00:00');
            $maxTime = new DateTime('22:00:00');
            $interval = new DateInterval('PT2H'); // 2 hours interval
            return ['date' => $matchDate->format('Y-m-d'), 'time' => $matchTime->format('H:i:s')];
            while (true) {
                $dayName = $currentDate->format('l'); // Get the day name
                if (isset($matchDays[$dayName])) {
                    $dayConfig = &$matchDays[$dayName];
                    if (!isset($venueUsageSchedule[$currentDate->format('Y-m-d')])) {
                        $venueUsageSchedule[$currentDate->format('Y-m-d')] = [];
                    }
                    // Check if the venue is already used on this day
                    if (in_array($venueId, $venueUsageSchedule[$currentDate->format('Y-m-d')])) {
                        $currentDate->modify('+1 day');
                        continue; // Find the next available day
                    }
        
                    // Calculate the match time based on how many matches have been scheduled for this day
                    $matchTime = (clone $defaultStartTime)->add(new DateInterval('PT' . (count($venueUsageSchedule[$currentDate->format('Y-m-d')]) * 120) . 'M'));
                    if ($matchTime > $maxTime) {
                        // If calculated match time exceeds the maximum allowed time, move to the next day
                        $currentDate->modify('+1 day');
                        continue;
                    }
        
                    // If the venue is not used and the time is within the allowed range, schedule the match
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
        
        function venueHasLights($venueId, $venues) {
            foreach ($venues as $venue) {
                if ($venue['id'] == $venueId && $venue['quality'] === 'light') {
                    return true; // The venue has lights
                }
            }
            return false; // Default to false if not found or doesn't have lights
        }
        
        // Schedule the match
        $fixtures[] = [
            'home_team' => $homeTeam['name'],
            'away_team' => $awayTeam['name'],
            'date' => $matchDate,
            'time' => $matchTime,
            'venue' => $homeTeam['venue_name'],
        ];

        // Update tracking structures
        $scheduledMatches[$matchDate][] = $homeTeam['venue_name'];
        $matchDays[$currentDate->format('l')]['matches_scheduled']++; // Update matches scheduled for the day
    }
}

$insertSql = "INSERT INTO fixture (home_team_name, away_team_name, venue_name, date, time) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysql->prepare($insertSql);

if (!$stmt) {
    die("Error preparing statement: " . $mysql->error);
}

foreach ($fixtures as $fixture) {
    $homeTeamName = $fixture['home_team']; // Assuming this is the team name; adjust if using team ID
    $awayTeamName = $fixture['away_team']; // Adjust as needed
    $venueName = $fixture['venue_name']; // The venue name; adjust if using venue ID
    $matchDate = $fixture['date']; // The scheduled match date
    $matchTime = $fixture['time']; // The scheduled match time

    // Bind the parameters
    $stmt->bind_param("sssss", $homeTeamName, $awayTeamName, $venueName, $matchDate, $matchTime);

    // Execute the insertion
    if (!$stmt->execute()) {
        echo "Error inserting fixture: " . $stmt->error;
        break; // Optionally, handle this error differently, e.g., logging or accumulating errors for later
    }
}

$stmt->close(); // Close the statement when done


?>

