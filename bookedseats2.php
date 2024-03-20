<?php
include('config.php');
if (isset($_GET['showtime_id']) && isset($_GET['movie_id'])) {
    $showtimeId = $_GET['showtime_id'];
    $movieId = $_GET['movie_id'];
    //var_dump($_GET);
    //echo $showtimeId;
    //echo $movieId;
    // Use $showtimeId, $movieId, and $theaterId as needed

    $stmtSeats = $dbh->prepare("SELECT * FROM tbl_seats WHERE showtime_id = :showtimeId AND movie_id = :movie_id AND status = 'Paid'");
    $stmtSeats->bindParam(':showtimeId', $showtimeId);
    $stmtSeats->bindParam(':movie_id', $movieId);
    $stmtSeats->execute();
    $bookedSeats = $stmtSeats->fetchAll(PDO::FETCH_ASSOC);
              
            $bookedSeatsArr = array_column($bookedSeats, 'seat_number');

            foreach ($bookedSeatsArr as $seats) {
                // echo $seats;

            }
} else {
    // Handle the case when parameters are not set
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
<style>
    .square-btn {
      width: 40px; /* Set the width of the button */
      height: 40px; /* Set the height of the button */
    }

    .con{
      width : 900px;
      background: #F3F8FF;
      border-radius: 15px;
    }

    .text-white {
      color: white !important;
    }
  </style>
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
<div class="container mt-4">
  <div class="row justify-content-center">
    
  </div>
</div>
  <div class="container con mt-5 p-4">
    <div class="d-flex justify-content-center align-items-center" style="height: 30px; background-color: black; border: 1px solid #ccc;">
        <span style="color: white; font-size: 15px;"><strong>CINEMA SCREEN</strong></span>
    </div>
          <br>
          <?php
    $totalButtons = 100;
    $buttonsPerRow = 20;
    $buttonsPerSpace = 5; // Number of buttons between spaces
    $spaceWidth = 5; // Width of the space in pixels

    $rows = ['A', 'B', 'C', 'D', 'E'];

    for ($i = 1; $i <= $totalButtons; $i++) {
        // Calculate the row and column index
        $rowIndex = ceil($i / $buttonsPerRow) - 1; // 0-based index
        $colIndex = ($i - 1) % $buttonsPerRow;

        // Start a new row for every $buttonsPerRow buttons
        if ($colIndex == 0) {
            echo '<div class="row mb-3">';
        }

        // Generate a square button with text like "a1", "b2", etc.
        $buttonText = $rows[$rowIndex] . ($colIndex + 1);
        if (in_array($buttonText, $bookedSeatsArr)) {
            echo '<button type="button" class="btn btn-outline btn-dark btn-sm square-btn" disabled>' . $buttonText . '</button>';
        } else {
            echo '<button type="button" class="btn btn-outline-dark btn-sm square-btn" id="' . $buttonText . '" name="' . $buttonText . '" onclick="showValue(\'' . $buttonText . '\')">' . $buttonText . '</button>';
        }
        // Add space after every $buttonsPerSpace buttons
        if ($colIndex % $buttonsPerSpace == $buttonsPerSpace - 1 && $colIndex != $buttonsPerRow - 1) {
            echo '<div class="col" style="width: ' . $spaceWidth . 'px;"></div>';
        }

        // End the row for every $buttonsPerRow buttons
        if ($colIndex == $buttonsPerRow - 1 || $i == $totalButtons) {
            echo '</div>';
        }
    }
    ?>

        <div class="col-md-12 text-end">
          <a href="bookedseats.php" class="btn btn-dark me-2">Back</a>
        </div>
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
<script>
    var selectedButtons = [];

function showValue(buttonId) {
var button = document.getElementById(buttonId);
var index = selectedButtons.indexOf(buttonId);

if (index !== -1) {
  // If the button is already selected, remove it from the selection
  button.classList.remove('btn-danger');
  button.classList.remove('text-white');
  selectedButtons.splice(index, 1);
} else if (selectedButtons.length < <?= $quantity ?>) {
  // If the selected seats are less than the specified quantity, add the button to the selection
  button.classList.add('btn-danger');
  button.classList.add('text-white');
  selectedButtons.push(buttonId);
}
}
</script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>
  </body>

</html>