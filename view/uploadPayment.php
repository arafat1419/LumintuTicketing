<?php
    session_start();
    $_SESSION['cred'] = $_GET['m'];

    echo $_SESSION['cred'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- CSS Independent -->
    <link rel="stylesheet" href="../public/css/uploadPayment.css">

    <title>Upload | Lumintu Event</title>
</head>

<body>
    <div class="container-fluid container-parent">
        <div class="row">
            <div class="col-12 rounded mx-auto isi">
                <div id="notCompleted">
                    <div class="text-center header mt-3 mb-3">
                        <p class="h3 title-status">Upload Payment</p>
                        <p class="text-white">Upload your payment receipt below</p>
                    </div>
                    <div class="container drag-area rounded text-center d-flex align-items-center mb-3">
                        <div class="container-petunjuk">
                            <i class="fa fa-cloud-upload icon"></i>
                            <p class="h5">Drag & Drop to Upload File</p>
                        </div>
                    </div>
                    <div class="container text-right">
                        <button type="button" class="btn btn-hapus mb-3" onclick="deleteImage()" disabled>Hapus
                            Gambar</button>
                    </div>
                    <div class="content">
                        <p class="text-white">File :</p>
                        <div class="text-center">
                            <form class="input-group mb-3" method="post" action="../controller/uploadPaymentProcess.php"
                                enctype="multipart/form-data">
                                <div class="custom-file w-100 mb-3">

                                    <input type="file" name="file" class="custom-file-input" id="inputGroupFile"
                                        aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile">Choose file</label>
                                </div>
                                <div class="container mt-3">
                                    <button type="submit" value="submit" name="submit"
                                        class="btn rounded py-1 px-5 btn-checkout " disabled>Upload</button>
                                </div>
                            </form>
                        </div>

                    </div>
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

    <!-- Independent Javascript -->
    <script src="../public/js/uploadPayment.js"></script>
</body>

</html>