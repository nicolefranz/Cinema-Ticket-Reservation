<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the POST request
    $movieTitle = $_POST['movietitle'] ?? '';
    $trailerLink = $_POST['trailerlink'] ?? '';
    $description = $_POST['description'] ?? '';
    $cast = $_POST['cast'] ?? '';
    $runtime = $_POST['runtime'] ?? '';
    $genre = $_POST['genre'] ?? '';

    $upload_dir = 'img/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $imgExt = strtolower(pathinfo($_FILES['movieposter']['name'], PATHINFO_EXTENSION));
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

    if (in_array($imgExt, $valid_extensions) && $_FILES['movieposter']['size'] < 5000000) {
      $targetFile = $upload_dir . rand(1000, 1000000) . "." . $imgExt;
      if (move_uploaded_file($_FILES['movieposter']['tmp_name'], $targetFile)) {
          $poster = $targetFile;

        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Oops!</strong> Sorry, there was an error uploading your file.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
        }
    } else {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Oops!</strong> Sorry, only JPG, JPEG, PNG & GIF files under 5MB are allowed.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>';
    }

    // Insert into tbl_movie
    $sql = "INSERT INTO tbl_movie (title, video_link, description, cast, runtime, genre, poster)
            VALUES (:title, :video_link, :description, :cast, :runtime, :genre, :poster)";
    
    $stmt = $dbh->prepare($sql);
    // Bind parameters to the prepared statement
    $stmt->bindValue(':title', $movieTitle);
    $stmt->bindValue(':video_link', $trailerLink);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':cast', $cast);
    $stmt->bindValue(':runtime', $runtime);
    $stmt->bindValue(':genre', $genre);
    $stmt->bindValue(':poster', $poster); // Binding the poster path

    if ($stmt->execute()) {
        // Get the last inserted ID
        $last_id = $dbh->lastInsertId();

        // Extract data for showtimes
        $showtimesData = json_decode($_POST['showtimesData'], true);

        // Insert into tbl_showtimes
        foreach ($showtimesData as $showtime) {
            $cinema = $showtime['cinema'] ?? '';
            $dateFormatted = date('F j, Y', strtotime($showtime['date'])); // Format date as 'Month day, Year'
            $timeFormatted = date('H:i:s', strtotime($showtime['time'])); // Format time as 'HH:MM:SS'
            $price = $showtime['price'] ?? '';

            $sql_showtimes = "INSERT INTO tbl_showtimes (movie_id, theater, date, time, price)
                              VALUES (:movie_id, :theater, :datepick, :timepick, :price)";

            // Prepare the SQL statement for showtimes
            $stmt_showtimes = $dbh->prepare($sql_showtimes);

            // Bind parameters to the prepared statement for showtimes
            $stmt_showtimes->bindValue(':movie_id', $last_id);
            $stmt_showtimes->bindValue(':theater', $cinema);
            $stmt_showtimes->bindValue(':datepick', $dateFormatted);
            $stmt_showtimes->bindValue(':timepick', $timeFormatted);
            $stmt_showtimes->bindValue(':price', $price);

            // Execute the prepared statement for showtimes
            if ($stmt_showtimes->execute()) {

              $sql_seats = "INSERT INTO tbl_seats (movie_id, seat_number, status) VALUES (:movie_id, :seat_number, :status)";

              // Prepare the statement only once outside the loop
              $stmt_seats = $dbh->prepare($sql_seats);

              for ($i = 1; $i <= 100; $i++) {
                  // Inside the loop, bind parameters and execute the statement
                  $stmt_seats->bindValue(':movie_id', $last_id);
                  $stmt_seats->bindValue(':seat_number', $i);
                  $stmt_seats->bindValue(':status', "Available");

                  if ($stmt_seats->execute()) {
                      $_SESSION['successMSG'] = "Successfully Added!";
                      header("refresh:3;adminhome.php");
                  } else {
                      echo "Error: " . $stmt_seats->errorInfo()[2];
                  }
              }
            } else {
                  echo "Error: " . $stmt_showtimes->errorInfo()[2];
            }
        }
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}


?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Filmax Entertainment</title>
  <link rel="icon" href="img\assets\filmax-entertainment.ico" type="image/x-icon"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">

  <!-- Custom CSS for floating labels -->
  <style>
    .form-floating {
      position: relative;
    }

    .form-floating input {
      padding: 1rem 1rem;
      width: 100%;
      border-radius: 5px;
      border: 1px solid #ced4da;
    }

    .form-floating label {
      position: absolute;
      top: 0;
      left: 0;
      pointer-events: none;
      padding: 1rem;
      transition: all 0.3s ease-out;
    }

    .form-floating input:focus+label,
    .form-floating input:not(:placeholder-shown)+label {
      font-size: 0.8rem;
      transform: translate(0, -100%);
      color: #495057;
      padding: 0.25rem 0.5rem;
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
        <h2 class="text-center mb-4"><strong>Add Movie</strong></h2>
        <hr>
        <?php
    if (isset($_SESSION['successMSG'])) {
      ?>
      <div class="alert alert-success">
        <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $_SESSION['successMSG']; ?></strong>
      </div>
      <?php
      unset($_SESSION['successMSG']); // Unset the session variable
    }
    ?>
    <form method="post" enctype="multipart/form-data" >
      <div class="row">
        <!-- Left Column for Movie Details -->
        <div class="col-md-6">
          <div class="mb-3 form-floating">
            <input type="text" class="form-control" id="movietitle" name="movietitle" placeholder=" ">
            <label for="movietitle">Movie Title</label>
          </div>
          <div class="mb-3 form-floating">
            <input type="text" class="form-control" id="trailerlink" name="trailerlink" placeholder=" ">
            <label for="url1">Trailer Link</label>
          </div>
          <div class="mb-3">
            <label for="movieposter" class="form-label">Movie Poster</label>
            <input type="file" class="form-control" id="movieposter" name="movieposter">
          </div>
          <div class="mb-3 form-floating">
            <textarea class="form-control" id="description" name="description" rows="3" placeholder=" "></textarea>
            <label for="description">Movie Description</label>
          </div>
          <div class="mb-3 form-floating">
            <input type="number" class="form-control" id="runtime" name="runtime" placeholder=" ">
            <label for="runtime">Runtime</label>
          </div>
          <div class="mb-3 form-floating">
            <input type="text" class="form-control" id="cast" name="cast" placeholder=" ">
            <label for="cast">Cast</label>
          </div>
          <div class="mb-3 form-floating">
            <input type="text" class="form-control" id="genre" name="genre" placeholder=" ">
            <label for="genre">Genre</label>
          </div>
        </div>
        <!-- Right Column for Showtime Details -->
        <div class="col-md-6">
          <div class="form-floating mb-3">
            <select class="form-select" id="cinema" name="cinema" aria-label="Cinema">
              <option value="Cinema 1">Cinema 1</option>
              <option value="Cinema 2">Cinema 2</option>
              <option value="Cinema 3">Cinema 3</option>
            </select>
            <label for="cinema">Cinema</label>
          </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="datePicker" class="form-label">Date</label>
                        <input type="date" class="form-control" id="datePicker" name="datePicker">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="timePicker" class="form-label">Time</label>
                        <input type="time" class="form-control" id="timePicker" name="timePicker">
                    </div>
                </div>
            </div>
            <!-- Price Input -->
            <div class="form-floating mb-3">
                <input class="form-control" id="price" name="price" type="number" placeholder="Price"/>
                <label for="price">Price</label>
            </div>
            <!-- Add Showtime Button -->
            <div class="mb-3">
              <button type="button" class="btn btn-primary btn-sm" id="addShowtime">Add Showtime</button>
            </div>
            <!-- Table to display added showtimes -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Cinema</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="showtimeTableBody">
                <!-- Rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
      </div>
      
      <div class="container mt-3">
        <div class="row justify-content-end">
          <div class="col-md-6">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button type="submit" class="btn btn-primary me-md-2 mb-2 mb-md-0" id="saveMovieDetails">Save</button>
              <a href="adminhome.php" class="btn btn-secondary">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </form>
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Find the logout button by its class
    const logoutButton = document.querySelector('.btn-outline-light');

    // Add a click event listener to the logout button
    logoutButton.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent the default behavior of the link

      // Perform logout actions (for demonstration, redirecting to a logout page)
      window.location.href = 'index.php'; // Replace 'logout.php' with your actual logout endpoint
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Find the add showtime button
    const addShowtimeBtn = document.getElementById('addShowtime');
    addShowtimeBtn.addEventListener('click', function(event) {
      event.preventDefault();

      // Get the values from the input fields
      const cinema = document.getElementById('cinema').value;
      const datePicker = document.getElementById('datePicker').value;
      const timePicker = document.getElementById('timePicker').value;
      const price = document.getElementById('price').value;

      // Check if the price is not empty and is a valid number before adding the row
      if (price.trim() !== '' && !isNaN(price)) {
        // Create a new row for the table
        const newRow = `
          <tr>
            <td>${cinema}</td>
            <td>${datePicker}</td>
            <td>${timePicker}</td>
            <td>${(price)}</td>
            <td><button class="btn btn-danger btn-sm deleteRow">Delete</button></td>
          </tr>
        `;

        // Append the new row to the table body
        const tableBody = document.getElementById('showtimeTableBody');
        tableBody.innerHTML += newRow;

        // Clear input fields after adding showtime
        document.getElementById('datePicker').value = '';
        document.getElementById('timePicker').value = '';
        document.getElementById('price').value = '';

        // Add event listener to delete row button
        const deleteButtons = document.querySelectorAll('.deleteRow');
        deleteButtons.forEach(function(button) {
          button.addEventListener('click', function(event) {
            event.preventDefault();
            // Delete the row
            const rowToDelete = button.parentNode.parentNode;
            rowToDelete.remove();
          });
        });
      } else {
        alert('Please enter a valid price.');
      }
    });

    // Handle form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      // Create an array to store showtimes data
      const showtimesData = [];

      // Iterate through each row in the table and extract data
      const tableRows = document.querySelectorAll('#showtimeTableBody tr');
      tableRows.forEach(function(row) {
        const columns = row.querySelectorAll('td');
        const showtime = {
          cinema: columns[0].innerText,
          date: columns[1].innerText,
          time: columns[2].innerText,
          price: columns[3].innerText
        };
        showtimesData.push(showtime);
      });

      // Append the showtimes data to the form as a JSON string
      const showtimesInput = document.createElement('input');
      showtimesInput.type = 'hidden';
      showtimesInput.name = 'showtimesData';
      showtimesInput.value = JSON.stringify(showtimesData);
      form.appendChild(showtimesInput);
      // Submit the form
      form.submit();
    });
  });
</script>
</body>

</html>