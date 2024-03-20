<?php
require_once 'config.php';

// Check if a delete request has been made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'])) {
    $movieId = $_POST['movie_id'];

    try {
        //Delete  associated seats from tbl_seats
        $deleteMovieQuery = "DELETE FROM tbl_seats WHERE movie_id = :movieId";
        $deleteMovieStmt = $dbh->prepare($deleteMovieQuery);
        $deleteMovieStmt->bindParam(':movieId', $movieId);
        $deleteMovieStmt->execute();
        
        // Delete associated showtimes from tbl_showtimes
        $deleteShowtimesQuery = "DELETE FROM tbl_showtimes WHERE movie_id = :movieId";
        $deleteShowtimesStmt = $dbh->prepare($deleteShowtimesQuery);
        $deleteShowtimesStmt->bindParam(':movieId', $movieId);
        $deleteShowtimesStmt->execute();

        // Delete movie from tbl_movie
        $deleteMovieQuery = "DELETE FROM tbl_movie WHERE movie_id = :movieId";
        $deleteMovieStmt = $dbh->prepare($deleteMovieQuery);
        $deleteMovieStmt->bindParam(':movieId', $movieId);
        $deleteMovieStmt->execute();

        // Redirect after successful deletion
        header('Location: adminhome.php'); // Redirect to admin home or any other page
        exit();
    } catch (PDOException $e) {
        // Handle exceptions if necessary
        echo "Error deleting movie: " . $e->getMessage();
    }
}

// Fetch movies from the database
$stmt = $dbh->prepare("SELECT * FROM tbl_movie");
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>