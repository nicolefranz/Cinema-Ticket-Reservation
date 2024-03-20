<?php
// Include the database connection
require_once('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $cinema = $_POST['cinema'];
    $inputDate = $_POST['datePicker']; // Assuming 'datePicker' is the name of your input field

    // Convert date to month day year format
    $date = date('F j, Y', strtotime($inputDate));

    $time = $_POST['timePicker'];
    $price = $_POST['price'];

    // Prepare and execute the SQL query to insert showtime
    $stmt = $dbh->prepare("INSERT INTO tbl_showtimes (theater, date, time, price) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$cinema, $date, $time, $price]);

    if ($result) {
        // Get the last inserted ID (assuming your table has an auto-incremented ID)
        $lastInsertedId = $dbh->lastInsertId();

        // Redirect back to showtimes page after adding the showtime with the movie ID
        header("Location: showtimes.php?id=$lastInsertedId");
        exit();
    } else {
        // Handle the error, if needed
        echo "Failed to add showtime.";
    }
}
?>
