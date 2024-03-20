<?php
require_once 'config.php';

// Initialize variables
$title = $description = $poster = $trailerLink = $cast = $runtime = $genre = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $movieId = $_GET['id'];

    // Fetch movie details including the trailer link from the database based on movie_id
    $stmt = $dbh->prepare("SELECT * FROM tbl_movie WHERE movie_id = :id");
    $stmt->bindParam(':id', $movieId);
    $stmt->execute();
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($movie) {
        // Assign fetched movie details to variables
        $title = $movie['title'];
        $description = $movie['description'];
        $poster = $movie['poster'];
        $trailerLink = $movie['video_link'];
        $cast = $movie['cast'];
        $runtime = $movie['runtime'];
        $genre = $movie['genre'];
    }

    // Fetch available showtimes for the movie ID
    $showtimesStmt = $dbh->prepare("SELECT DISTINCT showtime_id, date, time FROM tbl_showtimes WHERE movie_id = :movieId");
    $showtimesStmt->bindParam(':movieId', $movieId);
    $showtimesStmt->execute();
    $showtimes = $showtimesStmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico">
  <!-- Plugins -->

</head>

<body>
    <nav class="navbar bg-black navbar-expand-md navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="img\assets\Filmax.png"
          height="30" alt="image">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNav11" aria-controls="navbarNav11"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav11">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item active">
            <a class="nav-link" href="home.php">Home <span
                class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="movies.php">Movies</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-3">
    <img src="img\assets\promote.gif" height="275px" width="1300px" alt="animated image">
  </div>
  <div class="container mt-5">
        <?php if(isset($title) && isset($description) && isset($poster)) : ?>
            <div class="row">
                <div class="col-md-3">
                    <!-- Display movie poster -->
                    <img src="<?php echo $poster; ?>" alt="Movie Poster" class="img-fluid" style="width: 240px; height: 350px;">
                </div>
                <div class="col-md-8">
                    <!-- Display movie title and description -->
                    <h2><?php echo $title; ?></h2>
                    <a href="<?php echo $trailerLink; ?>" class="btn btn-dark btn-sm" target="_blank">
                    <i class="fa fa-play"></i> Watch Trailer
                    </a>
                    <p><?php echo $description; ?></p>
                    <p><strong>Cast: </strong><?php echo $cast; ?></p>
                    <p><strong>Runtime: </strong><?php echo $runtime; ?> minutes</p>
                    <p><strong>Genre: </strong><?php echo $genre; ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="container mt-5">
    <h1 class="text-center mb-4"><strong><?php echo $title; ?> Showtimes</strong></h1>
    <hr>
    <div class="row justify-content-center">
        <?php 
        $currentDate = date('Y-m-d');
        foreach ($showtimes as $showtime) : 
            $showDate = date('Y-m-d', strtotime($showtime['date']));
            if ($showDate >= $currentDate) : ?>
                <div class="col-md-2">
                    <!-- Display each show date and time as a button -->
                    <a href="showtimedetails.php?id=<?= $showtime['showtime_id'] ?>" class="btn btn-dark btn-sm mb-3">
                        <?= date('F j, Y', strtotime($showtime['date'])) ?> <?= date('g:i A', strtotime($showtime['time'])) ?>
                    </a>
                </div>
            <?php endif;
        endforeach; ?>
    </div>
</div>

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