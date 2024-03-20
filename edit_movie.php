<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'])) {
    // Retrieve posted form data and validate/sanitize if needed
    $movieId = $_POST['movie_id'];
    $title = $_POST['title'];
    $trailerLink = $_POST['video_link'];
    $description = $_POST['description'];
    $runtime = $_POST['runtime'];
    $cast = $_POST['cast'];
    $genre = $_POST['genre'];

    // Validate the input data (perform necessary validation checks)

    // Update the movie details in the database
    $updateStmt = $dbh->prepare("UPDATE tbl_movie 
                                SET title = ?, video_link = ?, description = ?, 
                                    runtime = ?, cast = ?, genre = ? 
                                WHERE movie_id = ?");
    $success = $updateStmt->execute([$title, $trailerLink, $description, $runtime, $cast, $genre, $movieId]);

    if ($success) {
        // Redirect back to the movie edit page upon successful update
        header("Location: editmovie.php?id=$movieId");
        exit();
    } else {
        // Handle update failure
        echo "Error: Failed to update. Please try again.";
    }
} else {
    // Handle if the POST data is not received properly
    echo "Error: Failed to update. Please ensure all fields are filled.";
}
?>
