<?php
session_start();
include_once 'config.php';

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $dbh->prepare("SELECT id, email, password FROM tbl_user WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      // Check if the email matches the admin's email
      if ($user['email'] === 'filmax.entertainment01@gmail.com') {
          // Admin's email, redirect to adminhome.php
          $_SESSION['user_id'] = $user['id'];
          header("Location: adminhome.php");
          exit();
      } else {
          // Regular user, redirect to movies.php
          $_SESSION['user_id'] = $user['id'];
          header("Location: movies.php");
          exit();
      }
  } else {
      $errorMsg = "Incorrect email or password. Please try again.";
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
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">

  <!-- Plugins -->
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
            <a class="nav-link" href="index.php">Home <span class="sr-only"></span></a>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h1 class="text-center mb-4">Theater Admin Login</h1>
        <form action="adminhome.php" method="POST">
          <div class="mb-3 form-floating">
            <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder=" ">
            <label for="exampleInputEmail1">Email address</label>
          </div>
          <div class="mb-3 form-floating">
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder=" ">
            <label for="exampleInputPassword1">Password</label>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
          <?php if ($errorMsg): ?>
            <div class="text-danger"><?php echo $errorMsg; ?></div>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <br>
  <br>
  <br>
  <br>
  <br>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>

</body>

</html>
