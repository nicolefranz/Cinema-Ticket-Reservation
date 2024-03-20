<?php
ob_start();
include('config.php');
require('pdf/fpdf.php');

$showtime_id = $_GET['showtime_id'] ?? '';
$name = $_GET['name'] ?? '';
$seat_number = $_GET['seat_number'] ?? '';
$movie = $_GET['movie'] ?? '';
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$status = $_GET['status'] ?? '';

// Fetch movie poster path from the database based on the movie title
$stmtMovie = $dbh->prepare("SELECT poster FROM tbl_movie WHERE title = :movie_title");
$stmtMovie->bindParam(':movie_title', $movie);
$stmtMovie->execute();
$movieData = $stmtMovie->fetch(PDO::FETCH_ASSOC);
$posterPath = $movieData['poster'] ?? '';

// Fetch email from the tbl_seats table based on the seat number
$stmtSeat = $dbh->prepare("SELECT email FROM tbl_seats WHERE seat_number = :seat_number");
$stmtSeat->bindParam(':seat_number', $seat_number);
$stmtSeat->execute();
$seatData = $stmtSeat->fetch(PDO::FETCH_ASSOC);
$email = $seatData['email'] ?? '';

// Fetch theater information based on showtime_id
$stmtTheater = $dbh->prepare("SELECT theater, price FROM tbl_showtimes WHERE showtime_id = :showtime_id");
$stmtTheater->bindParam(':showtime_id', $showtime_id);
$stmtTheater->execute();
$theaterData = $stmtTheater->fetch(PDO::FETCH_ASSOC);
$theater = $theaterData['theater'] ?? '';
$price = $theaterData['price'] ?? '';

// Assuming $email holds the user's email associated with the booked seat
$stmtUser = $dbh->prepare("SELECT mode_payment, cardNumber, booked_date FROM tbl_seats WHERE email = :email");
$stmtUser->bindParam(':email', $email);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Fetching payment information
$modeOfPayment = $userData['mode_payment'] ?? '';
$ccNumber = $userData['cardNumber'] ?? '';
$bookedDate = $userData['booked_date'] ?? '';

$pdf = new FPDF('L', 'mm', array(150,70));
$pdf->AddPage();

// Border
$pdf->Rect(5,15,100,47.5 ,'D');
$pdf->Rect(105,7.5,40,55,'D');
$pdf->Rect(5,35,20,27.5 ,'D');
$pdf->Rect(25,35,25,19 ,'D');
$pdf->Rect(50,35,30,19 ,'D');
$pdf->Rect(80,35,25,19 ,'D');
$pdf->Rect(80,35,25,19 ,'D');
$pdf->Rect(25,54,120,8.5,'F');
$pdf->Rect(5,15,100,5,'D');
$pdf->Line(105,15,105,62.5);

// Image logo
$pdf->Image('img/assets/logo_9.png', 2.5,37,25);
$pdf->Image('img/assets/Filmax.png', 105,7.5,40);

// Company Name
$pdf->SetFont('Times','B',17);
$pdf->SetXY(5,7.5);
$pdf->MultiCell(100,7.5,"FILMAX ENTERTAINMENT",1,'C');

// Company Contact Info
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(5,14);
$pdf->MultiCell(100,7.5,"filmax.entertainment01@gmail.com | 366-43-66",0,'C');

// Title
$pdf->SetFont('Times','B',25);
$pdf->SetXY(5,25);
$pdf->MultiCell(100,7.5,$movie,0,'C');

// Seat
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(30,35);
$pdf->MultiCell(15,7.5,"SEAT",0,'C');
$pdf->SetXY(30,40);
$pdf->MultiCell(15,7.5,$seat_number,0,'C');

// Time
$pdf->SetXY(58,35);
$pdf->MultiCell(15,7.5,"TIME",0,'C');
$pdf->SetXY(58,40);
$pdf->MultiCell(15,7.5,$time,0,'C');

// Date
$pdf->SetXY(85,35);
$pdf->MultiCell(15,7.5,"DATE",0,'C');
$pdf->SetFont('Arial','B',7);
$pdf->SetXY(85,40);
$pdf->MultiCell(15,7.5,$bookedDate,0,'C');

//second page
// Title
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(115,18);
$pdf->Cell(10,10,$movie,0);

// Seat
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(116,25);
$pdf->Cell(10,10,"SEAT :",0);
$pdf->SetXY(128.5,25);
$pdf->Cell(10,10,$seat_number,0);

// Time
$pdf->SetXY(128,32);
$pdf->Cell(10,10,$time,0);

// Date
$pdf->SetXY(107,32);
$pdf->Cell(10,10,$bookedDate,0);

// Cinema
$pdf->SetFont('Times','B',11);
$pdf->SetXY(116,39);
$pdf->Cell(10,10,$theater,0);

// Output PDF
$pdf->Output();
ob_end_flush();
?>
