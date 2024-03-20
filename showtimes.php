<?php
require_once('config.php');

// Check if movie_id is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $movieId = $_GET['id'];
}

// Process form submission for adding a new showtime
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cinema'], $_POST['datePicker'], $_POST['timePicker'], $_POST['price'])) {
    $cinema = $_POST['cinema'];
    $inputDate = $_POST['datePicker']; // Assuming 'datePicker' is the name of your input field

    // Convert date to 'Month Day, Year' format
    $date = date('F j, Y', strtotime($inputDate));

    $time = $_POST['timePicker'];
    $price = $_POST['price'];

    // Insert the new showtime into the database
    $insertStmt = $dbh->prepare("INSERT INTO tbl_showtimes (movie_id, theater, date, time, price) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->execute([$movieId, $cinema, $date, $time, $price]);

    // Redirect to the same page to avoid form resubmission on page refresh
    header("Location: showtimes.php?id=$movieId");
    exit;
}

// Fetch showtimes based on the movie_id
$showtimesStmt = $dbh->prepare("SELECT * FROM tbl_showtimes WHERE movie_id = ?");
$showtimesStmt->execute([$movieId]);
$showtimes = $showtimesStmt->fetchAll(PDO::FETCH_ASSOC);
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
      <!-- Navbar Brand -->
      <a class="navbar-brand" href="#">
        <img src="img\assets\Filmax.png" height="30" alt="image">
      </a>
      <!-- Navbar Toggler Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav11"
        aria-controls="navbarNav11" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navbarNav11">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item active">
            <a class="nav-link" href="adminhome.php">Dashboard <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="booking.php">Bookings</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  </div>
    <div class="container mt-5">
    <h2 class="text-center mb-4"><strong>Showtimes</strong></h2>
    <div class="d-flex justify-content-end mb-3">
        <button onclick="goBack()" class="btn btn-primary">Movie Details</button>
    </div>
    <div class="mb-3">
        <form id="addShowtimeForm" method="post" action="showtimes.php?id=<?php echo $movieId; ?>">
        <input type="hidden" name="movieId" value="<?php echo $movieId; ?>">
            <div class="form-floating mb-3">
                <select class="form-select" id="cinema" name="cinema" aria-label="Cinema">
                    <option value="Cinema 1">Cinema 1</option>
                    <option value="Cinema 2">Cinema 2</option>
                    <option value="Cinema 3">Cinema 3</option>
                </select>
                <label for="cinema">Cinema</label>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="datePicker" class="form-label">Date</label>
                    <input type="date" class="form-control" id="datePicker" name="datePicker">
                </div>
                <div class="col">
                    <label for="timePicker" class="form-label">Time</label>
                    <input type="time" class="form-control" id="timePicker" name="timePicker">
                </div>
            </div>

            <!-- Price Input -->
            <div class="form-floating mb-3">
                <input class="form-control" id="price" name="price" type="number" placeholder="Price"/>
                <label for="price">Price</label>
            </div>
            <!-- Submit Button -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-sm">Add Showtime</button>
            </div>
        </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Cinema</th>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
                // Display each fetched showtime in the table rows
                foreach ($showtimes as $showtime) {
                    echo "<tr>";
                    echo "<td>{$showtime['theater']}</td>";
                    echo "<td>{$showtime['date']}</td>";
                    echo "<td>{$showtime['time']}</td>";
                    // Update the deletion link to use the 'id' column from tbl_showtimes
                    echo "<td>
                            <a href='delete_showtime.php?id={$showtime['showtime_id']}&movieId={$showtime['movie_id']}' class='btn btn-danger'>Delete</a>
                        </td>";
                    echo "</tr>";
                }
                ?>
        </tbody>
    </table>
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

<script>
    function goBack() {
        // Get the movieId from PHP
        <?php echo "var movieId = $movieId;" ?>

        // Redirect to editmovie.php with the same movieId
        window.location.href = `editmovie.php?id=${movieId}`;
    }
</script>

  </body>

</html>