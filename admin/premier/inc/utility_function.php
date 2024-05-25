<?php
function getHomeTeamVenueId($mysql, $homeTeamId) {
    $query = "SELECT venue_id FROM team WHERE id = ?";
    if ($stmt = $mysql->prepare($query)) {
        $stmt->bind_param("i", $homeTeamId);
        $stmt->execute();
        $stmt->bind_result($venueId);
        $stmt->fetch();
        $stmt->close();
        
        return $venueId;
    } else {
        die("Error preparing statement: " . $mysql->error);
    }
}

?>