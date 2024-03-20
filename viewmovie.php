<?php
require_once 'config.php';

// Check if movie_id is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $movieId = $_GET['id'];

    // Fetch movie details based on movie_id
    $stmt = $dbh->prepare("SELECT * FROM tbl_movie WHERE movie_id = ?");
    $stmt->execute([$movieId]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the movie exists
    if ($movie) {
        // Check if the 'trailer_link' key exists in the $movie array
        if (isset($movie['video_link'])) {
            // Assign the 'trailer_link' value to the $trailerLink variable
            $trailerLink = $movie['video_link'];
        } else {
            // Handle the case where 'trailer_link' is not available (optional)
            // You can assign a default value or perform other actions here
            $trailerLink = 'Trailer link not available';
        }

        // Assign other movie details to respective variables
        $title = $movie['title'];
        $poster = $movie['poster'];
        $description = $movie['description'];
        $runtime = $movie['runtime'];
        $cast = $movie['cast'];
        $genre = $movie['genre'];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Filmax Entertainment</title>
  <link rel="icon" href="img\assets\filmax-entertainment.ico" type="image/x-icon"/>
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet"
    href=" https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css ">
  <link rel="stylesheet"
    href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">

  <!-- Plugins -->
</head>

<body>
<nav class="navbar bg-black navbar-expand-md navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="img\assets\Filmax.png" height="30" alt="image">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav11"
        aria-controls="navbarNav11" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav11">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item active">
            <a class="nav-link" href="adminhome.php">Dashboard <span class="sr-only"></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="booking.php">Bookings</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Movie - <?php echo $title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
        <h2 class="text-center mb-4"><strong>Movie Details: <?php echo $title; ?></strong></h2>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <!-- Display movie poster -->
                <div class="mb-3">
                    <img src="<?php echo $poster; ?>" alt="Movie Poster" style="width: 300px; height: 450px;">
                </div>
            </div>
            <div class="col-md-8">
                <!-- Display movie details in read-only text fields -->
                <form>
                    <div class="mb-3">
                        <label for="movietitle" class="form-label">Movie Title</label>
                        <input type="text" class="form-control" id="movietitle" value="<?php echo $title; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="trailerlink" class="form-label">Trailer Link</label>
                        <input type="text" class="form-control" id="trailerlink" value="<?php echo $trailerLink; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Movie Description</label>
                        <textarea class="form-control" id="description" rows="3" readonly><?php echo $description; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="runtime" class="form-label">Runtime</label>
                        <input type="number" class="form-control" id="runtime" value="<?php echo $runtime; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cast" class="form-label">Cast</label>
                        <input type="text" class="form-control" id="cast" value="<?php echo $cast; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" id="genre" value="<?php echo $genre; ?>" readonly>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container mt-5">
    <h2 class="text-center mb-4"><strong>Showtimes</strong></h2>
    <table class="table">
        <thead>
            <tr>
                <th>Cinema</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch showtimes based on the movie_id
            $showtimesStmt = $dbh->prepare("SELECT * FROM tbl_showtimes WHERE movie_id = ?");
            $showtimesStmt->execute([$movieId]);
            $showtimes = $showtimesStmt->fetchAll(PDO::FETCH_ASSOC);

            // Display each showtime in the table rows
            foreach ($showtimes as $showtime) {
                echo "<tr>";
                echo "<td>{$showtime['theater']}</td>";
                echo "<td>{$showtime['date']}</td>";
                echo "<td>{$showtime['time']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <br>
  <br>
  <br>
  <br>
  <br>
  <section class="">
    <footer class="pt-4 pb-4 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <ul class="nav justify-content-center justify-content-md-start">
                        <li class="nav-item">
                            <a class="nav-link active ps-md-0" href="#">
                            <img alt="image"
                            src=" img\assets\logo.png"
                            height="40">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php" style="color: white;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="movies.php" style="color: white;">Movies</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="terms&condition.php" style="color: white;">Terms & Conditions</a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 mt-4 mt-md-0 text-center text-md-end">
                    © 2023 Filmax Entertainment. All Rights Reserved
                </div>
            </div>
        </div>
    </footer>
</section>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>

  </body>

</html>