<?php
require_once 'config.php';

$showtimeId = $_GET['id'] ?? null;
$quantity = $_GET['typeNumber'] ?? null;
$subtotal = $_GET['subtotal'] ?? null;


// Retrieve movie and showtime details
if ($showtimeId) {
    $stmtShowtime = $dbh->prepare("SELECT * FROM tbl_showtimes WHERE showtime_id = :showtimeId");
    $stmtShowtime->bindParam(':showtimeId', $showtimeId);
    $stmtShowtime->execute();
    $showtimeDetails = $stmtShowtime->fetch(PDO::FETCH_ASSOC);

    if ($showtimeDetails) {
        $movieId = $showtimeDetails['movie_id'];
        $date = $showtimeDetails['date'];
        $time = $showtimeDetails['time'];
        $cinema = $showtimeDetails['theater'];
        $price = $showtimeDetails['price'];
        // Fetch movie details based on movie_id from tbl_movies
        $stmtMovie = $dbh->prepare("SELECT * FROM tbl_movie WHERE movie_id = :movieId");
        $stmtMovie->bindParam(':movieId', $movieId);
        $stmtMovie->execute();
        $movieDetails = $stmtMovie->fetch(PDO::FETCH_ASSOC);

        if ($movieDetails) {
            $title = $movieDetails['title'];
            $poster = $movieDetails['poster'];
            $description = $movieDetails['description'];
            $cast = $movieDetails['cast'];

            $stmtSeats = $dbh->prepare("SELECT * FROM tbl_seats WHERE showtime_Id = :showtimeId AND movie_id = :movieId AND status = 'Paid'");
            $stmtSeats->bindParam(':showtimeId', $showtimeId);
            $stmtSeats->bindParam(':movieId', $movieId);
            $stmtSeats->execute();
            $bookedSeats = $stmtSeats->fetchAll(PDO::FETCH_ASSOC);
              
            $bookedSeatsArr = array_column($bookedSeats, 'seat_number');

            foreach ($bookedSeatsArr as $seats) {
                //echo $seats;

            }
        }
      }
    }
?>


<!DOCTYPE html>
<html>

<head>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Plugins -->

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
                <div class="col-md-2">
                    <!-- Display movie poster -->
                    <img src="<?php echo $poster; ?>" alt="Movie Poster" class="img-fluid" style="width: 160px; height: 220px;">
                </div>
                <div class="col-md-9">
                    <!-- Display movie title and description -->
                    <h1><strong><?php echo $title; ?></strong></h1>
                    <p><?php echo $cinema; ?> - <?php echo date('F j, Y', strtotime($date)); ?> <?php echo date('g:i A', strtotime($time)); ?></p>
                    <span style="font-size: 13px; text-align: justify;">
                      <p><?php echo $description; ?></p>
                      <p><strong>CAST: </strong><?php echo $cast; ?></p>
                    </span>
                </div>
            </div>
        <?php endif; ?>
        <hr>
    </div>
    <div class="container mt-5">
      <h1 style="text-align: center;"><strong>SELECT SEATS</strong></h1>
      <div id="maxSelectionAlert"></div>
    </div>
    <!-- Legend for seat statuses -->
    <section class="mt-5 justify-content-center">
    <div class="container justify-content-center">
        <div class="row justify-content-center"> <!-- Center horizontally -->
            <div class="col-md-8 text-center"> <!-- Center content -->
                <div class="row justify-content-center">
                    <div class="col-md-2"> <!-- Individual legend item -->
                        <div class="d-flex align-items-center mb-2">
                            <div class="legend-box me-2" style="background-color: red; width: 20px; height: 20px;"></div>
                            <span class="legend-text">Selected</span>
                        </div>
                    </div>
                    <div class="col-md-2"> <!-- Individual legend item -->
                        <div class="d-flex align-items-center mb-2">
                            <div class="legend-box me-2" style="background-color: gray; width: 20px; height: 20px;"></div>
                            <span class="legend-text">Booked</span>
                        </div>
                    </div>
                    <div class="col-md-2"> <!-- Individual legend item -->
                        <div class="d-flex align-items-center mb-2">
                            <div class="legend-box me-2" style="background-color: #F3F8FF; width: 20px; height: 20px; border: 1px solid black;"></div>
                            <span class="legend-text">Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-8 text-center">
      <p style="font-size: 15px;"><strong>NOTE: </strong>To change your selection, click on a seat that you've already selected</p>
    </div>
  </div>
</div>
  <div class="container con mt-5 p-4">
    <div class="d-flex justify-content-center align-items-center" style="height: 30px; background-color: black; border: 1px solid #ccc;">
        <span style="color: white; font-size: 15px;"><strong>CINEMA SCREEN</strong></span>
    </div>
      <br>
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
      echo '<button type="button" class="btn btn-dark btn-sm square-btn" disabled>' . $buttonText . '</button>';
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
      <a href="showtimedetails.php?id=<?= $showtimeId?>" class="btn btn-dark me-2">Back</a>
      <button class="btn btn-danger me-2" id="addSeat">Next</button>
    </div>
  </div>

  <script>
    // get button ids
    // get button ids
    var selectedButtons = [];

  function showValue(buttonId) {
  var button = document.getElementById(buttonId);
  var index = selectedButtons.indexOf(buttonId);

  var addButton = document.getElementById('addSeat');
  

  if (index !== -1) {
    // If the seats button is already selected, remove it from the selection
    button.classList.remove('btn-danger');
    button.classList.remove('text-white');
    selectedButtons.splice(index, 1);
  } else if (selectedButtons.length < <?= $quantity ?>) {
    // If the seats selected seats are less than the specified quantity, add the button to the selection
    button.classList.add('btn-danger');
    button.classList.add('text-white');
    selectedButtons.push(buttonId);
  }

  if (selectedButtons.length >= <?= $quantity ?>) {
        // Display the success alert when reaching the maximum selection
        var alertDiv = document.getElementById('maxSelectionAlert');
        alertDiv.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <div>
                    Maximum selection reached successfully!
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
    } else {
        // Clear the alert message if the selection is within the limit
        var alertDiv = document.getElementById('maxSelectionAlert');
        alertDiv.innerHTML = '';
    }

  console.log("Selected Buttons: " + JSON.stringify(selectedButtons));
}

    // handle the add button to be passed on php
    function handleAddSeats() {
      // Send an AJAX request to the server
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            // Handle the server response, if needed
            console.log("Server Response: " + xhr.responseText);
          } else {
            // Handle the error, if any
            console.error("Error: " + xhr.statusText);
          }
        }
      };

      // Construct the URL with the selectedButtons parameter
      var url = "bookseat.php?id=<?= $showtimeId ?>";
      url += "&selectedButtons=" + encodeURIComponent(JSON.stringify(selectedButtons));

      // Open a POST request
      xhr.open("POST", url, true);
      // Set the Content-Type header for a JSON payload
      xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      // Send the request with the selectedButtons data
      xhr.send();

      // For testing, display the selected button values
      console.log("Selected Buttons: " + JSON.stringify(selectedButtons));
    }
    document.getElementById('addSeat').addEventListener('click', handleAddSeats);

    function handleAddSeats() {
      if (selectedButtons.length === <?= $quantity ?>) {
      var url = "checkout.php?id=<?= $showtimeId ?>&typeNumber=<?= $quantity ?>&subtotal=<?= $subtotal ?>&selectedButtons=" + encodeURIComponent(JSON.stringify(selectedButtons));
      window.location.href = url;
    } else {
      alert("Please select exactly <?= $quantity ?> seat(s).");
    }
    document.getElementById('addSeat').addEventListener('click', handleAddSeats);
    addButton.disabled = false;
  }
  </script>

<br>
  <br>
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