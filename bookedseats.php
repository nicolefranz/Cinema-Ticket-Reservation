<?php
include('config.php');

// Fetch all relevant information using JOIN in the SQL query
$stmtReserved = $dbh->prepare("
    SELECT DISTINCT s.showtime_id, s.movie_id, s.date, s.time, s.theater, m.title, s.price
    FROM tbl_showtimes s
    JOIN tbl_movie m ON s.movie_id = m.movie_id
    JOIN tbl_seats se ON s.showtime_id = se.showtime_id
");
$stmtReserved->execute();
$bookedSeats = $stmtReserved->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission if any
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['showtime_id'], $_POST['movie_id'], $_POST['theater_id'])) {
        $showtimeId = $_POST['showtime_id'];
        $movieId = $_POST['movie_id'];
        $theaterId = $_POST['theater_id'];

        // Use $showtimeId, $movieId, and $theaterId as needed
        echo $showtimeId;
        echo $movieId;
        echo $theaterId;
    } else {
        // Handle the case when parameters are not set
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <!-- Plugins -->
  <style>
    #movieTable thead th {
      background-color: black;
      color: white;
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
      </div>
    </div>
  </nav>
  <div class="container mt-5">
    <h1 class="text-center mb-4"><strong>Booked Seats</strong></h1>
    <hr>
  </div>

  <div class="container mt-5">
    <form action="bookedseats2.php" method="GET">
    <table id="movieTable" class="table">
      <thead>
        <tr>
          <th>Movie ID</th>
          <th>Title</th>
          <th>Date and Time</th>
          <th>Theater</th>
          <th>Capacity</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookedSeats as $seat) : ?>
          <tr>
            <td><?php echo $movie_id = $seat['movie_id']; ?></td>
            <td><?php echo $title = $seat['title']?></td>
            <td>
              <?php 
                $timestamp = strtotime($seat['date'] . ' ' . $seat['time']); // Get timestamp from date and time
                  echo date('M j, Y - h:i A', $timestamp); // Format date and time (Month Day, Year - 12-hour format)
              ?>
            </td>
            <td><?php echo $theater = $seat['theater']; ?></td>
            <td>
              <?php
              $stmtCapacity = $dbh->prepare("SELECT COUNT(*) FROM tbl_seats WHERE status = 'Paid' AND movie_id = :movie_id AND showtime_id = :showtime_id");
              $stmtCapacity->bindValue(':movie_id', $seat['movie_id']);
              $stmtCapacity->bindValue(':showtime_id', $seat['showtime_id']);
              $stmtCapacity->execute();
              $capacity = $stmtCapacity->fetchColumn();
              echo $capacity . "/100";
              ?>
            </td>
            <td><?php echo $seat['price']; ?></td>
            <td>
    <button class="btn btn-dark view-button" 
    data-showtime-id="<?php echo $seat['showtime_id']; ?>" 
data-movie-id="<?php echo $seat['movie_id']; ?>">
        <i class="fas fa-eye"></i> View
    </button>
    <form action="bookedseats2.php" method="post" id="viewForm" style="display: none;">
        <input type="hidden" name="showtime_id" id="showtimeIdInput">
        <input type="hidden" name="movie_id" id="movieIdInput">
        <input type="hidden" name="theater_id" id="theaterIdInput">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const viewButtons = document.querySelectorAll('.view-button');

            viewButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const showtimeId = button.getAttribute('data-showtime-id');
                    const movieId = button.getAttribute('data-movie-id');

                    // Set values in the hidden form
                    document.getElementById('showtimeIdInput').value = showtimeId;
                    document.getElementById('movieIdInput').value = movieId;

                    // Submit the form
                    document.getElementById('viewForm').submit();
                });
            });
        });
    </script>
</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </form>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#movieTable').DataTable();
    });
  </script>

  </body>

</html>
