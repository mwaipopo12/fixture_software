
<?php 
foreach ($fixtures as &$fixture) {
    $foundDay = false;
    $currentDate = clone $startDate;

    while (!$foundDay && $currentDate <= $endDate) {
        $dayName = $currentDate->format('l');

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
            // Ensure we only schedule if the day has not exceeded its match limit
            if ($matchesPerDay[$dayName]['count'] < $matchDays[$dayName]) {
                $foundDay = true;

                // Schedule the match for this day
                $fixture['date'] = $currentDate->format('Y-m-d');
                $fixture['time'] = $matchesPerDay[$dayName]['lastMatchTime']->format('H:i:s');

                // Update match count and last match time for the day
                $matchesPerDay[$dayName]['count']++;
                $matchesPerDay[$dayName]['lastMatchTime']->add(new DateInterval('PT120M')); // Add 2 hours for next match

                // Reset the lastMatchTime if this was the last match of the day
                if ($matchesPerDay[$dayName]['count'] >= $matchDays[$dayName]) {
                    $matchesPerDay[$dayName]['lastMatchTime'] = new DateTime('16:00:00');
                    $matchesPerDay[$dayName]['count'] = 0; // Optionally reset count if you loop over multiple days
                }
            }
        }
        if (!$foundDay) {
            $currentDate->modify('+1 day');
        }
    }
}

?>