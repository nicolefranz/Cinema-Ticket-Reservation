<?php
include('config.php');

$showtime_id = $_GET['showtime_id'] ?? '';
$name = $_GET['name'] ?? '';
$seat_number = $_GET['seat_number'] ?? '';
$movie = $_GET['movie'] ?? '';
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$status = $_GET['status'] ?? '';

// Fetch movie poster path from the database based on the movie title
$stmtMovie = $dbh->prepare("
    SELECT poster FROM tbl_movie WHERE title = :movie_title
");
$stmtMovie->bindParam(':movie_title', $movie);
$stmtMovie->execute();
$movieData = $stmtMovie->fetch(PDO::FETCH_ASSOC);
$posterPath = $movieData['poster'] ?? '';

// Fetch email from the tbl_seats table based on the seat number
$stmtSeat = $dbh->prepare("
    SELECT email FROM tbl_seats WHERE seat_number = :seat_number
");
$stmtSeat->bindParam(':seat_number', $seat_number);
$stmtSeat->execute();
$seatData = $stmtSeat->fetch(PDO::FETCH_ASSOC);
if ($seatData) {
    $email = $seatData['email'] ?? '';
}

// Fetch theater information based on showtime_id
$stmtTheater = $dbh->prepare("
    SELECT theater, price FROM tbl_showtimes WHERE showtime_id = :showtime_id
");
$stmtTheater->bindParam(':showtime_id', $showtime_id);
$stmtTheater->execute();
$theaterData = $stmtTheater->fetch(PDO::FETCH_ASSOC);
$theater = $theaterData['theater'] ?? '';
$price = $theaterData['price'] ?? '';


// Assuming $email holds the user's email associated with the booked seat
$stmtUser = $dbh->prepare("
    SELECT mode_payment, cardNumber, booked_date
    FROM tbl_seats 
    WHERE email = :email
");

$stmtUser->bindParam(':email', $email);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Fetching payment information
$modeOfPayment = $userData['mode_payment'] ?? '';
$ccNumber = $userData['cardNumber'] ?? '';
$bookedDate = $userData['booked_date'] ?? '';

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
          <li class="nav-item">
            <a class="nav-link" href="bookedseats.php">Booked Seats</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><strong>View Details</strong></h1>
        <hr>
    </div>
    <div class="container mt-3">
        <div class="text-end mb-3">
        <a href="print_ticket.php?showtime_id=<?php echo $showtime_id; ?>&name=<?php echo $name; ?>&seat_number=<?php echo $seat_number; ?>&movie=<?php echo $movie; ?>&date=<?php echo $date; ?>&time=<?php echo $time; ?>&status=<?php echo $status; ?>" class="btn btn-dark">
            <i class="fas fa-print me-2"></i> Print Ticket
        </a>
        </div>
    </div>
    <div class="container mt-4">
        <p><strong>Name: </strong> <?php echo $_GET['name'] ?? '' ?></p>
        <p><strong>Email: </strong><?php echo $email ?? '' ?></p>
        <p></p>
    </div>
    <div class="container mt-3">
        <h5 style="background-color: black; padding: 8px; color: white;">Booking Summary</h5>
        <div class="row">
        <div class="col-md-1">
            <?php if (!empty($posterPath)) : ?>
                <img src="<?php echo $posterPath; ?>" alt="Movie Poster" width="80">
            <?php else : ?>
                <p>Movie Poster Not Available</p>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
    <div class="row">
        <div class="col-md-3"><strong>Movie Title</strong></div>
        <div class="col-md-8"><?php echo $movie; ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Theater</strong></div>
        <div class="col-md-8"><?php echo $theater; ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>ShowDate</strong></div>
        <div class="col-md-8"><?php echo date("F j, Y", strtotime($date)); ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>ShowTime</strong></div>
        <div class="col-md-8"><?php echo date("g:i A", strtotime($time)); ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Ticket</strong></div>
        <div class="col-md-8"><?php echo $seat_number; ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Booked Date</strong></div>
        <div class="col-md-8"><?php echo date("F j, Y g:i A", strtotime($bookedDate)); ?></div>
    </div>
</div>

    </div>
    </div>
    <div class="container mt-4">
    <h5 style="background-color: black; padding: 8px; color: white;">Payment Information</h5>
    <div class="row">
        <div class="col-md-3"><strong>Mode of Payment</strong></div>
        <div class="col-md-9"><?php echo $modeOfPayment; ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>CC Number</strong></div>
        <div class="col-md-9"><?php echo $ccNumber; ?></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Price</strong></div>
        <div class="col-md-9"><?php echo $price; ?></div>
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