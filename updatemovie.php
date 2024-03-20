<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $movieId = $_GET['id'];

    // Retrieve submitted form data
    $title = $_POST['movietitle'];
    $trailerLink = $_POST['trailerlink'];
    $description = $_POST['description'];
    $runtime = $_POST['runtime'];
    $cast = $_POST['cast'];
    $genre = $_POST['genre'];

    // File handling for movie poster
    if ($_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $posterTmpName = $_FILES['poster']['tmp_name'];
        $posterName = $_FILES['poster']['name'];
        $posterPath = 'img/' . $posterName; // Define the path where the poster will be saved

        // Move the uploaded poster to the desired location
        if (move_uploaded_file($posterTmpName, $posterPath)) {
            // Update movie details including the poster path in the database
            $stmt = $dbh->prepare("UPDATE tbl_movie SET title = :title, video_link = :trailerLink, description = :description, runtime = :runtime, cast = :cast, genre = :genre, poster = :posterPath WHERE movie_id = :movieId");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':trailerLink', $trailerLink);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':runtime', $runtime);
            $stmt->bindParam(':cast', $cast);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':posterPath', $posterPath);
            $stmt->bindParam(':movieId', $movieId);

            if ($stmt->execute()) {
                // On successful update, redirect back to the movie view page or display a success message
                header("Location: view_movie.php?id={$movieId}");
                exit();
            } else {
                // Handle update failure
                echo "Failed to update movie details.";
            }
        } else {
            // Handle file upload error
            echo "Failed to upload the movie poster.";
        }
    } else {
        // Handle other form submission errors if any
        echo "Error in form submission.";
    }
} else {
    // Handle invalid or missing movie ID
    echo "Invalid request or movie ID is missing.";
}
?>
