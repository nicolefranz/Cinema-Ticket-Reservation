<?php
require_once 'config.php';

// Fetch movies from the database
$stmt = $dbh->prepare("SELECT * FROM tbl_movie");
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Filmax Entertainment</title>
  <link rel="icon" href="img\assets\filmax-entertainment.ico" type="image/x-icon"/>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Plugins -->
  
  <style>
  /* CSS to set a fixed size for the card images and center them */
  .card-img-top {
    height: 400px; /* Set a fixed height for the images */
    object-fit: cover; /* Maintain aspect ratio and cover entire space */
  }

  /* Center the card content */
  .card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
  }
</style>

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
          <li class="nav-item">
            <a class="nav-link" href="bookedseats.php">Booked Seats</a>
          </li>
        </ul>
        <a class="btn btn-outline-light ms-md-3" href="#">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <!-- Add Movie button -->
    <div class="text-end mb-3">
      <a href="addmovie.php" class="btn btn-dark"><i class="fas fa-plus"></i> Add Movie</a>
    </div>

<!-- Display movies in a 5x5 grid -->
<div class="row row-cols-1 row-cols-md-5 g-4">
<?php if (empty($movies)) : ?>
      <div class="alert alert-warning" role="alert">
      <h4 class="alert-heading">No movies available!</h4>
      </div>
  <?php else : ?>
  <?php foreach ($movies as $movie) : ?>
    <div class="col">
      <div class="card h-100">
        <img src="<?php echo $movie['poster']; ?>" class="card-img-top" alt="Movie Poster" style="width: 240px; height: 350px;">
        <div class="card-body">
          <h5 class="card-title"><?php echo $movie['title']; ?></h5>
          <div class="btn-group d-flex justify-content-center" role="group" aria-label="Movie Actions">
            <a href="viewmovie.php?id=<?php echo $movie['movie_id']; ?>" class="btn btn-info">
              <i class="bi bi-eye-fill me-1"></i> View
            </a>
            <a href="editmovie.php?id=<?php echo $movie['movie_id']; ?>" class="btn btn-warning ms-2">
              <i class="bi bi-pencil-fill me-1"></i> Edit
            </a>
            <form method="post" action="deletemovie.php">
              <input type="hidden" name="movie_id" value="<?php echo $movie['movie_id']; ?>">
              <button type="submit" class="btn btn-danger ms-2" onclick="return confirm('Are you sure you want to delete this movie?')">
                <i class="bi bi-trash-fill me-1"></i> Delete
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php endif; ?>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#movieTable').DataTable();
    });

    document.addEventListener('DOMContentLoaded', function () {
      const logoutBtn = document.querySelector('.btn-outline-light');

      logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();

        const confirmLogout = confirm('Are you sure you want to logout?');
        if (confirmLogout) {
          window.location.href = 'index.php';
        }
      });
    });
  </script>
  
</body>

</html>
