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

    <style>
      a {
        text-decoration: none;
        color: black;
      }
    </style>
  </head>

  <body>
    <nav class="navbar bg-black navbar-expand-md navbar-dark">
      <div class="container">
        <a class="navbar-brand" href="#">
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


    <section class="">
      <div id="carouselExampleIndicators" class="carousel slide"
        data-bs-ride="carousel">
        <ol class="carousel-indicators">
          <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
            class="active" aria-current="true"></li>
          <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
            class=""></li>
          <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
            class=""></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100"
              alt="First slide [800x400]"
              src="img\assets\header1.png"
              data-holder-rendered="true">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100"
              alt="Second slide [800x400]"
              src="img\assets\header2.png"
              data-holder-rendered="true">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100"
              alt="Third slide [800x400]"
              src="img\assets\header3.png"
              data-holder-rendered="true">
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators"
          role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators"
          role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </section>
    <section class="pt-5 pb-5 bg-light">
      <div class="container">
        <div class="row mb-md-4">
          <div class="col text-center">
            <h1>How it works</h1>
          </div>
        </div>
        <div class="row mb-md-4">
        </div>
        <div class="row d-flex mb-5">
          <div class="col-10 mx-auto col-md-4">
            <div class="my-3 card card-body shadow p-4 ">
              <div
                class="row align-items-center d-flex text-md-center text-lg-start">
                <div class="col-12 col-sm-3 col-md-3 text-center px-0">
                  <div class="icon-wrap text-primary my-3">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADiUlEQVR4nO2ZaYiOURTHf+NFY4uQvbGLpOzKUpZQJso2RPliHaN8I2uSKDGRKFmKLGXUfDCMXUrJkiRTDFki+74by+jWeeq4Pc/rnXnu+87zav51P8xz7px7zvPec/7/ex+oxv+JDGAaMAOoQRpiOlAuYyYRQm1gF3AS6BJn3jmVQB4RQrYK7ETAnLbAL5nzE2hJhNBWgvKS6OUzZ6myFxNBHFABHvSxX1F2U8iRQ0/gt9oi7S17gdgeAXWJKI6rtzzZsmUCY4EWPv/XH9gHFAKtkhVcAynWZnHmdAMuA0eA+glwwmjgrErajDUkCRdkgR9AETAVqFNJX4bEjlmBe77HkCQ88VnwvZCTH8YJLzwDSoCN6tdrbvkpA/YA3UkiBgPnVaF644bP3LU+yZrxGOgoc7YCD4FNQBYpRDtgOXBLus0iyz4sIHhvmK0YKQkRjw8MYXUCJgDf1PORRBhXVaCD1PPN6vlpIoSmcbggz5IbZcrWlypETZED1yWYncqWq4K8C8SUba+yGWauksDnA/eswnyl5tSTvz3bFGXrobqYUaad5XkjYLYoWSM1zJxPQCmwHRjuKoF8n67yURbXWKXs1yxbkbIVCj98+EfnKgcuuuCJfcrhC2AF0DigLj6puaOUbUgCwZYHDONzYpgEjBBbD8xNQFHqrnPGsu33Ce6GFL3hmRoiUwYCW4Avat53YAQpgN11+lm1tEA4Y4foHyPugtBV5Ijn6ynQMAU5/NV1Dof01UoK3PO3gRTA7jrxDvyJYLxVDzYHJQW665jt4pLpV5EC6K7zzcGJa4ry9zqBg1OF0U3kdIHcFeVYb810sTCICcN7/hbiEIut6xVv/LYOQYZ5wyBX+TNnilougs+pACEtCblWppz0gi4QKoUS69BixN5K4IFPArcdrLda+TsU1pk+4362tkhMrlOK1dWii1NZb7Xm/bDOspSzl3GuzjsAkxyxaBPrpYVCTMSd5zBfZHUy0Vqt98aFQy2hy0Ui7xYeiKdzXPDBRVcH/KMBXeeOSG9X1ycZwCXl3xS0E5itNMfqSHqYIj4lLdfvRiNRDFU+y0T5OscAYBvwNiCZ5/LNoDKJ9FEdbR1JRqbcox4PYOmbUpAVxSDpaCn9WNhGWLjUSuJqiIviKoF5a/OAryoJw9pphzkqgXdCTmmFDLl28ZJYRhoiWyVg5HhawnwMmeVK11eDCOAPe/V6l8R0NKsAAAAASUVORK5CYII=">
                  </div>
                </div>
                <div class="col-12 col-md-9 mt-3 mt-lg-0">
                  <a href="movies.php">
                    <h4 class="">
                      Pick a movie
                    </h4>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-10 mx-auto col-md-4">
            <div class="my-3 card card-body shadow p-4">
              <div class="row align-items-center text-md-center text-lg-start">
                <div class="col-12 col-sm-3 col-md-3 text-center px-0">
                  <div class="icon-wrap text-primary my-3">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABZElEQVR4nO2WMU4DMRBFf8UdWMFB4AShgDJHIbQ0JPSQwyCBREKuAdKiUEJNZWTJKzmb9Xi89iYg/pNcRB5/+63tyAAhhBBSBtPR1gCmAA6EcbbvFsBHIENqNn+Wmb+FNOENwsx6CLTbNCNfxan35UKsC4ho8k+QScw8V0Kbnw1FwB3ZgEerDe8IeEc24B1pwzuCP3ZHGioAEwDfikXbmks3JobZtUjDlUJkMuD8nTy0FrD0+laBvkohUgUyXpRzJyOd5dS+Ehm98QP2JWKhSO6OaBlkR0zkKPwGEaOZM1S4SBAp0Rr838sUkRj7FCkKRbDjHTGKVnv17wMcq9rLrxPF1SI2eOTVnyVMZnrkj5T5/4svZ30k1GgeibF2KOQfu5rPHJHmdToWai4KiJwL+eOOl3Ey1y5kLtTcFxC5E/LnrsauRYU00asw7q2ASE7+FlLxI8IsCog8C/lP/NcihBCCgfkB97U78a2UHoAAAAAASUVORK5CYII=">
                  </div>
                </div>
                <div class="col-12 col-md-9 mt-3 mt-lg-0">
                  <h4 class="">
                    Choose a seat
                  </h4>
                </div>
              </div>
            </div>
          </div>
          <div class="col-10 mx-auto col-md-4">
            <div class="my-3 card card-body shadow p-4">
              <div class="row align-items-center text-md-center text-lg-start">
                <div class="col-12 col-sm-3 col-md-3 text-center px-0">
                  <div class="icon-wrap text-primary my-3">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAACMElEQVR4nO2ZQUsVURTHf+qjyV2IQaI9H/oF/B7ughbZop3QSmqhi3JpSH4a0W2QgRYVbSRBJERCKQMhjEw3LwbOyGMY39w5c+a+W84fzuqd4Z7fPefM3HMfVKtR4DmwCXwDLoBD4A2wANwlcEXACvAHaHexM+Cl+AenIeB9DkDatoDbBKRIAdEJE1UVWD8wA6wBR1Iqe8AqMA30pfyXlRCJLVcBMQF8ctjFEfEfceiJtkPPjFlDHDsu/lUWf1YSIrF5y3LKy0TaXgGvjUA2rEBmjALS2qEVyFqPQc6tQI56DPLDCuS8xyAbViDfewyyaAWyrgzgrXwHyvbHmBXIA2UQ90L7sg8AHwsGsCnHlUgyo81ohLEmZX5w/bKPdzw7LEeXohDxqbkSxceUDw4BtDKeHQSWHN6AZ+JX+TzSDzwEfpWs/avsBXCjYEwN4JFmVM3LSlnbAe4UANiT55x1U9H0WtvukplGCiAxZz32BJHYbEZZ3wd2r/B3lvY1qrU4+wlAVgbUICeeQX7LupGjv7N8QqSDa9cgGaozQl1al6p7JEt1j1D3yKXqHvmve+TUM8RPWfeWNYj2DxutvZN1p6xBnnoGmcsZpNQg8QXCZ48TYjyRdioPqJBaHmC2U9dJ5qNuoninnkgNW70ATmUCncvIhPnlQ6hqaK6DroWawJcC5XQgt5f/NEQ7RJgWsN8l2H2H37Pui70qLxPJjrv6BQ2RKEiYpjKooGCaJYPxAvMX64hGouPVFFAAAAAASUVORK5CYII=">
                  </div>
                </div>
                <div class="col-12 col-md-9 mt-3 mt-lg-0">
                  <h4 class="">
                    Enjoy watching!
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
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