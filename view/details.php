<!doctype html>
<?php
    session_start();
    $cred = $_SESSION['cred'];

    $customerURL = 'https://api-ticket.arisukarno.xyz/items/customer';

    $accessToken = '?access_token=Q$Q68KDADkOvPtHPXhJxtfFafr0rKSuUL40fV5uy6JYDo';

    $curl = curl_init();

    //get customer ID
    curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_code]=' . $_SESSION['cred']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseID = curl_exec($curl);
    $resultID = json_decode($responseID, true);
    $customerEmail = $resultID['data'][0]['customer_email'];

    curl_close($curl);
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Independent CSS-->
    <link rel="stylesheet" href="../public/css/details.css">
    <link rel="stylesheet" href="../public/css/slider.css">
    <link rel="stylesheet" href="../public/css/loader.css">
    

    <title>Details Event | Lumintu Event</title>
</head>

<body>
    <div class="container-fluid banner-event mb-4">
    <div class="container container-nav">
    <nav class="navbar navbar-expand-lg navbar-light text-white">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link text-white mr-4" href="main.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white mr-4" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white mr-4" href="#">Webinars</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white mr-4" href="#">Workshops</a>
                </li>
            </ul>
            <div class="my-2 my-lg-0">X</div>
        </div>
    </nav>
</div>

        <div class="container deskripsi d-flex align-items-center justify-content-start">
            <div class="jumbotron rounded my-auto p-lg-5 p-sm-3 p-3">
                <h1 class="h3 nama-event"></h1>
                <h4 class="h5 tanggal eventClient mb-4"></h4>
                <p class="text-white eventAddress"></p>
                <button class="btn btn-buy" data-toggle="modal" data-target="#exampleModalScrollable" type="button">Buy
                    Ticket</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 description">
                <p class="h5 title-event mb-3">Description</p>
                <p class="text-justify eventDesc"></p>
            </div>

            <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="header-popup modal-title container">
                                <p class="h3 text-center">Dream World Wide in Jogja</p>
                                <p class="organizer text-center">By Lumintu Logic</p>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="body-form" name="bodyForm" method="post"
                                action="../controller/insertInvitationProcess.php">
                                <div class="body-popup mx-3">
                                    <div class="peserta rounded mb-3" id="peserta1">
                                        <div class="row special mb-2 align-items-center">
                                            <div class="col-12 text-right ">
                                                <div class="toggle-buyMe">
                                                    <label class="switch">
                                                        <input type="checkbox" class="switchMe">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <small class="mr-1 my-auto">Buy For Me</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-auto input-section">
                                            <div class="col-lg-4 col-md-4 col-sm-12 my-auto">
                                                <p>Peserta 1</p>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <div class="form-group my-auto">
                                                    <input type="email" name="peserta1" class="form-control"
                                                        id="inputPeserta1" aria-describedby="emailHelp"
                                                        placeholder="example : ex@gmail.com"
                                                        oninput="validate(this.name)">
                                                    <small id="emailHelpBlock" class="form-text text-danger">
                                                        Your email is not valid!
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right mt-2 mx-4 btn-plus">
                                    <button type="button" class="btn btn-default btn-circle btn-lg rounded-circle"
                                        onclick="addInputFieldInvitation()">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-default btn-circle btn-lg rounded-circle"
                                        onclick="delInputFieldInvitation()">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <div class="modal-footer border-0">
                                    <div class="container text-center">
                                        <button class="btn btn-buy w-50 btn-invite" type="submit">Invite</button>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="header-carousel">
                    <div class="owl-carousel owl-theme">
                    </div>
                </div>

                <div class="body-session">
                    <div class="table-responsive">
                        <table class="table">
                            <div class="spinner-border text-primary spinner-event d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="outer d-none" id="loader">
        <div class="middle">
            <div class="inner text-center">
                <blockquote class="blockquote text-center">
                    <p class="mb-0">Sabar dan ikhlas bisa menjadikan kamu seorang yang mulia dan terhormat di dunia
                        sekalipun kamu bukan apa-apa.</p>
                    <footer class="blockquote-footer">Someone famous in <cite title="Source Title">PINTAR</cite>
                    </footer>
                </blockquote>
                <div class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>

    <!-- Font Awesome CDN -->
    <script src="https://use.fontawesome.com/7a7a4d3981.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- OwlCarousel2 CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- MomentJS CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Independent Js -->
    <script src="../public/js/details.js"></script>

    <script type="text/javascript">

        var cred = " <?php echo($customerEmail); ?>";
        console.log(cred);

        $(document).on('change', '.switchMe', function () {
            let oldData = statusOfInput[0]
            if (this.checked) {
                $("input#inputPeserta1").val(cred);
                $("#peserta1 #emailHelpBlock").addClass("d-none")
                statusOfInput[0] = { ...oldData, status: true }
                validate("peserta1")
            } else {
                $("#peserta1 #emailHelpBlock").removeClass("d-none")
                statusOfInput[0] = { ...oldData, status: false }
                validate("peserta1")
                $("input#inputPeserta1").val('');
            }
        });
    </script>

</body>

</html>