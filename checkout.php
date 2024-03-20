<?php
// Set error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/vendor/autoload.php';

$showtimeId = $_GET['id'] ?? null;
$quantity = $_GET['typeNumber'] ?? null;
$subtotal = $_GET['subtotal'] ?? 0;

// Retrieve the selected buttons array from the query parameters
$selectedButtons = json_decode($_GET['selectedButtons'] ?? '[ ]');

// Output the selected buttons
foreach ($selectedButtons as $button) {
  //echo "Selected Button: $button<br>";
}

if ($showtimeId) {
  // Fetch details of the specific showtime using the showtime_id
  $stmtShowtime = $dbh->prepare("SELECT * FROM tbl_showtimes WHERE showtime_id = :showtimeId");
  $stmtShowtime->bindParam(':showtimeId', $showtimeId);
  $stmtShowtime->execute();
  $showtimeDetails = $stmtShowtime->fetch(PDO::FETCH_ASSOC);

  if ($showtimeDetails) {
      // Retrieve additional details for the showtime, e.g., cinema, date, time, etc.
      $movieId = $showtimeDetails['movie_id'];
      $date = $showtimeDetails['date'];
      $time = $showtimeDetails['time'];
      $cinema = $showtimeDetails['theater'];
      $price = $showtimeDetails['price'];

      // Fetch movie details based on movie_id from tbl_movie
      $stmtMovie = $dbh->prepare("SELECT * FROM tbl_movie WHERE movie_id = :movieId");
      $stmtMovie->bindParam(':movieId', $movieId);
      $stmtMovie->execute();
      $movieDetails = $stmtMovie->fetch(PDO::FETCH_ASSOC);

      if ($movieDetails) {
          $title = $movieDetails['title'];
          $poster = $movieDetails['poster'];
          $description = $movieDetails['description'];
      }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $fullName = $_POST['fullName'] ?? '';
    $emailAddress = $_POST['emailAddress'] ?? '';
    $paymentMethod = $_POST['paymentMethod'] ?? '';
    $cardNumber = $_POST['cardNumber'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $expirationMonth = $_POST['expirationMonth'] ?? '';
    $expirationYear = $_POST['expirationYear'] ?? '';

    // Validate the form data (you can add more validation as needed)
    if (empty($fullName) || empty($emailAddress) || empty($paymentMethod) || empty($cardNumber) || empty($cvv) || empty($expirationMonth) || empty($expirationYear)) {
        echo "Please fill in all required fields.";
    } else {
        // Insert data for each selected seat button
        foreach ($selectedButtons as $selectedButton) {
          $sql = "UPDATE tbl_seats 
                  SET status='Paid', 
                      name=:fullName, 
                      email=:emailAddress, 
                      mode_payment=:paymentMethod, 
                      cardNumber=:cardNumber, 
                      subtotal=:subtotal, 
                      totalpayment=:subtotal 
                  WHERE showtime_id = :showtime_id 
                  AND seat_number = :selectedButton";
      
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':fullName', $fullName);
          $stmt->bindValue(':emailAddress', $emailAddress);
          $stmt->bindValue(':paymentMethod', $paymentMethod);
          $stmt->bindValue(':cardNumber', $cardNumber);
          $stmt->bindValue(':subtotal', $subtotal);
          $stmt->bindValue(':showtime_id', $showtimeId);
          $stmt->bindValue(':selectedButton', $selectedButton);
      
          if (!$stmt->execute()) {
              // Print error information if the query fails
              var_dump($stmt->errorInfo());
              // Echo the SQL query for debugging purposes
              echo "SQL Query: $sql<br>";
              echo "Error updating data for seat $selectedButton.<br>";
          } else {
              echo "Data for seat $selectedButton updated successfully.<br>";
          }
      }

        // Email sending
        $mail = new PHPMailer();
        // Configure PHPMailer settings: SMTP, recipient, content, attachments, etc.
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'filmax.entertainment01@gmail.com'; // Your SMTP username
        $mail->Password = 'ezfbhnpmppmdgojr'; // Your SMTP password
        $mail->SMTPSecure = 'tls'; // Set the encryption method if required
        $mail->Port = 587; // Adjust the SMTP port as needed

        $mail->setFrom('filmax.entertainment01@gmail.com', 'Filmax Entertainment');
        $mail->addAddress($emailAddress);
        
        $currentDateTime = date('F j, Y g:i A');

        $mail->isHTML(true);
        $mail->Subject = "Filmax Entertainment: Booking Confirmation for " . $title;
        $mail->Body = "
        <html>
        <head>
            <title>Filmax Entertainment: Booking Confirmation</title>
            <style>
                div {
                  height: 100%;
                  width: 75%;
                  padding: 10px;
                  margin: auto;        
                  }
            </style>
        </head>
        <body>
            <p>Good Day $fullName,</p>
            <br>
            <p style=\"text-align: center;\"><strong>Thank you for choosing Filmax Entertainment! Your reservation details are listed below.</strong></p>
            <!-- Insert transaction details -->
            <br>
            <h4 style=\"background-color: black; color: white; padding: 10px; text-align: center;\"><strong>Reservation Details</strong></h4>
            <p style=\"text-align: center;\"><strong>Transaction Date/Time:</strong> $currentDateTime</p>
            <p style=\"text-align: center;\"><strong>Movie Title :</strong> $title</p>
            <p style=\"text-align: center;\"><strong>Cinema :</strong> $cinema</p>
            <p style=\"text-align: center;\"><strong>Show Date :</strong> " . date('F j, Y', strtotime($date)) . "</p>
            <p style=\"text-align: center;\"><strong>Show Time :</strong> " . date('g:i A', strtotime($time)) . "</p>
            <p style=\"text-align: center;\"><strong>Tickets Reserved :</strong> $quantity ticket(s) - $seat </p>
            <!-- Insert transaction summary -->
            <br>
            <h4 style=\"background-color: black; color: white; padding: 10px; text-align: center;\"><strong>Transaction Summary</strong></h4>
            <p style=\"text-align: center;\"><strong>Payment Type :</strong> $paymentMethod</p>
            <p style=\"text-align: center;\"><strong>Transaction Date/Time :</strong> $currentDateTime</p>
            <p style=\"text-align: center;\"><strong>Transaction Amount :</strong> $subtotal</p>
            <p style=\"text-align: center;\"><strong>Product Description :</strong> Cinema Transaction</p>
            <!-- Insert additional information -->
            <br>
            <p><strong>Additional Information:</strong></p>
            <ul>
                <li>This email serves as your official confirmation. Present this email on your mobile device at the cinema ticket counter/kiosk.</li>
                <li>Reserved tickets must be collected at the cinema's designated ticket counter or kiosk. A valid form of identification and the reservation confirmation (printed or digital) must be presented for ticket collection.</li>
                <li>We recommend arriving at least 30 minutes before the movie commences for concession purchases and seating.</li>
                <li>Filmax Entertainment reserves the right to release reserved seats if patrons arrive later than the specified time before the movie starts. No refunds or exchanges will be provided for late arrivals.</li>
            </ul>
            <br>
            <p><strong>Sincerely,</strong></p>
            <p>The Filmax Entertainment Team</p>
            <br>
            <p><em><strong>This is an auto-generated email. PLEASE DO NOT REPLY TO THIS MESSAGE.</strong></em></p>
        </body>
        </html>";

        try {
            if ($mail->send()) {
                echo '<script>alert("Email sent successfully to ' . $emailAddress . '");</script>';
                // Redirect to successbooked.php and pass data through URL parameters
                $redirectUrl = 'successbooked.php' .
                    '?fullName=' . urlencode($fullName) .
                    '&emailAddress=' . urlencode($emailAddress) .
                    '&paymentMethod=' . urlencode($paymentMethod) .
                    '&cardNumber=' . urlencode($cardNumber) .
                    '&cvv=' . urlencode($cvv) .
                    '&expirationMonth=' . urlencode($expirationMonth) .
                    '&expirationYear=' . urlencode($expirationYear);

                header('Location: ' . $redirectUrl);
                exit();
            } else {
                echo '<script>alert("Email sending failed. Error: ' . $mail->ErrorInfo . '");</script>';
                error_log("Email sending failed. Error: " . $mail->ErrorInfo);
                // Handle email sending failure (redirect to an error page or other actions)
                // header("Location: errorpage.php");
                exit();
            }
        } catch (Exception $e) {
            echo '<script>alert("Email sending failed. Exception: ' . $e->getMessage() . '");</script>';
            error_log("Email sending failed. Exception: " . $e->getMessage());
            // Handle email sending exception (redirect to an error page or other actions)
            // header("Location: errorpage.php");
            exit();
        }
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
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="icon" href="favicon.ico">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                  class="sr-only"></span></a>
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
    <div class="container mt-4">
      <div class="row">
        <!-- Left column for Booking Summary -->
        <div class="col-md-4">
          <h2><strong>Booking Summary</strong></h2>
          <div class="col-md-7">
                <!-- Display movie poster -->
                <img src="<?php echo $poster; ?>" alt="Movie Poster" class="img-fluid" style="width: 250px; height: 375px;">
                <br>
          </div>
          <h1><strong><i class="fas fa-film"></i></strong> <?php echo $title; ?></h1>
          <p><?php echo $cinema; ?> - <?php echo date('F j, Y', strtotime($date)); ?> <?php echo date('g:i A', strtotime($time)); ?></p>
          <span style="font-size: 12px; text-align: justify;">
              <i><?php echo $description; ?></i>
          </span>
          <hr>
          <table class="table">
            <tr>
              <td>
                <strong>Price</strong>
              </td>
              <td>
                <?php echo $price; ?>.00
              </td>
            </tr>
            <tr>
              <td>
                <strong>Qty</strong>
              </td>
              <td>
                <?php echo $quantity; ?>
              </td>
            </tr>
            <tr class="table-danger">
              <td>
                <strong>Subtotal</strong>
              </td>
              <td>
                <span id="subtotalValue"><strong><?= number_format($subtotal, 2) ?></strong></span>
              </td>
            </tr>
          </table>




        </div>
        <!-- Right column for Personal Information -->
        <div class="container mt-5 col-md-8">
        <div id="maxSelectionAlert"></div>
        <h6><i class="fa fa-user"></i> Personal Information</h6>
          <form action="" method="POST">
            <div class="form-floating mb-3">
              <input class="form-control" id="fullName" name="fullName" type="text" placeholder="Full Name" data-sb-validations="required" />
              <label for="fullName">Full Name</label>
              <div class="invalid-feedback" data-sb-feedback="fullName:required">Full Name is required.</div>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" id="emailAddress" name="emailAddress" type="email" placeholder="Email Address" data-sb-validations="required,email" />
              <label for="emailAddress">Email Address</label>
              <div class="invalid-feedback" data-sb-feedback="emailAddress:required">Email Address is required.</div>
              <div class="invalid-feedback" data-sb-feedback="emailAddress:email">Email Address Email is not valid.</div>
            </div>
            <div>
              <h6><i class="fa fa-credit-card"></i> Payment</h6>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-md-9">
                <div class="form-floating mb-3">
                  <select class="form-select" id="paymentMethod" name="paymentMethod" aria-label="Payment Method">
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                  </select>
                  <label for="paymentMethod">Payment Method</label>
                </div>
              </div>
            <div class="col-md-3 d-flex align-items-center">
              <span style="font-size: 30px;">
                <i class="fa fa-cc-visa" style="color: navy;"></i>
                <i class="fa fa-cc-mastercard" style="color: red;"></i>
              </span>
            </div>
          </div>

            <div class="form-floating mb-3">
              <input class="form-control" id="cardNumber" name="cardNumber" type="number" placeholder="Credit Card Number" data-sb-validations="required" />
              <label for="cardNumber">CC Number</label>
              <div class="invalid-feedback" data-sb-feedback="cardNumber:required">Credit Card Number is required.</div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input class="form-control" id="cvv" name="cvv" type="number" placeholder="CVV" data-sb-validations="required" />
                  <label for="cvv">CVV</label>
                  <div class="invalid-feedback" data-sb-feedback="cvv:required">CVV is required.</div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <select class="form-select" id="expirationMonth" name="expirationMonth" aria-label="Expiration Month">
                      <option value="January">January</option>
                      <option value="February">February</option>
                      <option value="March">March</option>
                      <option value="April">April</option>
                      <option value="May">May</option>
                      <option value="June">June</option>
                      <option value="July">July</option>
                      <option value="August">August</option>
                      <option value="September">September</option>
                      <option value="October">October</option>
                      <option value="November">November</option>
                      <option value="December">December</option>
                  </select>
                  <label for="expirationMonth">Expiration Month</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input class="form-control" id="expirationYear" name="expirationYear" type="number" placeholder="Expiration Year" data-sb-validations="required" />
                  <label for="expirationYear">Expiration Year</label>
                  <div class="invalid-feedback" data-sb-feedback="expirationYear:required">Expiration Year is required.</div>
                </div>
              </div>
            </div>
            <span style="font-size: 13px;">
              <i> <strong>NOTE: </strong>Always check your order summary before proceeding to payment. </i></br>
              
            </span>
            <div class="mb-3">
            <div>
              <br>
            <p style="font-size: 12px;">
              <strong>By clicking the confirm reservation, </strong><br>
              I agree and understood the <a href="terms&condition.php">Terms and Conditions.</a><br>
              I have verified that every detail is accurate and confirmed to be correct.
            </p>
          </div>
          </div>
            <div class="col-md-12 text-end">
                <a href="movies.php" class="btn btn-danger me-2">Cancel Reservation</a>
                <button type="submit" class="btn btn-dark me-2" id="confirmButton" name="confirmButton" >Confirm Reservation</button>
            </div>
          </form>
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

      <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    </body>

  </html>