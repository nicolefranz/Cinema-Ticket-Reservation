<?php
require_once('config.php');

// Check if the showtime ID and movie ID are provided in the URL
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['movieId']) && !empty($_GET['movieId'])) {
    $showtimeId = $_GET['id'];
    $movieId = $_GET['movieId'];

    // Prepare and execute the SQL query to delete the showtime
    $deleteStmt = $dbh->prepare("DELETE FROM tbl_showtimes WHERE showtime_id = ?");
    $deleteStmt->execute([$showtimeId]);

    // Redirect back to the showtimes page after deletion
    header("Location: showtimes.php?id=$movieId");
    exit;
} else {
    // Handle the case where the showtime ID or movie ID is not provided
    echo "Invalid showtime ID or movie ID";
}
?>
