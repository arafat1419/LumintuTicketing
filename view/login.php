<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- CSS Independent -->
    <link rel="stylesheet" href="../public/css/main.css">

    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>

    <!-- SweetAlert2 CDN -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let showLoginSuccess = () => {
            Swal.fire({
                icon: 'success',
                title: 'Login Success!',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                text: "Check your email to continue",
            })
        }

        let showEmailNotExist = () => {
            Swal.fire({
                icon: 'error',
                title: 'Email Not Exist!',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                text: "Make sure your email has been register",
            })
        }
    </script>

    <title>Hello, world!</title>
  </head>
  <body>
    <?php
            if (isset($_GET['scs'])){
                echo '<script type="text/javascript">
                        showLoginSuccess();
                    </script>';
            }elseif(isset($_GET['emailNotExist'])){
                echo '<script type="text/javascript">showEmailNotExist();</script>';
        }
    ?>

    <div class="container-fluid">
        <div class="row d-flex">
            <div class="col-lg-4 col-md-6 col-xs-12 login-side p-5"> <!-- Start Login Side -->
                <div class="login-side-header mb-5">
                    <p class="h1 text-center mb-3">Welcome</p>
                    <p class="font-italic text-center">Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>

                    <?php
                    if (isset($_GET['success'])){
                        echo '<p style="color: green">Your account has been verified! Go ahead and login to access our page.</p>';
                    }

                    if (isset($_GET['wp'])){
                        echo '<p style="color: red">Email or password is wrong.</p>';
                    }

                    if (isset($_GET['st'])){
                        echo '<p style="color: red">Please verify your email first.</p>';
                    }
                    ?>
                </div>

                <div class="login-side-form">
                    <form action="../controller/loginProcess.php" method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <small id="emailHelpBlock" class="form-text text-danger d-none">
                            Your email is not valid!
                        </small>
                        <button type="submit" class="btn btn-lg btn-submit w-100 mt-2">Submit</button>
                      </form>
                </div>

                <div class="login-side-bottom position-absolute mb-4">
                    <p class="text-center">Don’t have an account? <a href="../view/registration/registration.php">Sign-Up</a></p>
                </div>

            </div> <!-- End Login Side -->
            <div class="col-lg-8 col-md-6"></div>
        </div>
    </div>

        
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="https://use.fontawesome.com/7a7a4d3981.js"></script>



    <script src="../public/js/login.js"></script>
  </body>
</html>