<?php
include('config.php');

// Fetch all relevant information using JOIN in the SQL query
$stmtReserved = $dbh->prepare("
    SELECT ts.showtime_id, ts.name, ts.status, ts.seat_number, tm.title AS movie, tsh.date, tsh.time
    FROM tbl_seats ts
    JOIN tbl_showtimes tsh ON ts.showtime_id = tsh.showtime_id
    JOIN tbl_movie tm ON tsh.movie_id = tm.movie_id
    WHERE ts.status = 'Paid'
");
$stmtReserved->execute();
$bookedSeats = $stmtReserved->fetchAll(PDO::FETCH_ASSOC);

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
    /* Style the table */
    #movieTable {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    #movieTable th,
    #movieTable td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    /* Style the table header */
    #movieTable thead {
      background-color: black;
      color: white;
    }

    /* Style the table header cells */
    #movieTable th {
      font-weight: bold;
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
        <h1 class="text-center mb-4"><strong>Bookings</strong></h1>
        <hr>
  </div>
  <div class="container mt-5">
    <!-- Table -->
    <table id="movieTable" class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Ticket</th>
          <th>Movie</th>
          <th>Showtime</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookedSeats as $seat) : ?>
            <tr>
                <td><?php echo $seat['name']; ?></td>
                <td><?php echo $seat['seat_number'] ?></td>
                <td><?php echo $seat['movie']; ?></td>
                <td>
                  <?php 
                  $timestamp = strtotime($seat['date'] . ' ' . $seat['time']); // Get timestamp from date and time
                  echo date('M j, Y - h:i A', $timestamp); // Format date and time (Month Day, Year - 12-hour format)
                  ?>
                </td>
                <td><?php echo $seat['status']; ?></td>
                <td>
                    <a href="view_booking.php?showtime_id=<?php echo $seat['showtime_id']; ?>&name=<?php echo urlencode($seat['name']); ?>&seat_number=<?php echo $seat['seat_number']; ?>&movie=<?php echo urlencode($seat['movie']); ?>&date=<?php echo urlencode($seat['date']); ?>&time=<?php echo urlencode($seat['time']); ?>&status=<?php echo urlencode($seat['status']); ?>" class="btn btn-dark">
                      <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
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
