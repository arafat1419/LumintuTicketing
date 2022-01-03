<?php
    session_start();
    $_SESSION['cred'] = $_GET['m'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.jqueryui.min.css">

    <!-- CSS Independent -->
    <link rel="stylesheet" href="../public/css/statuspesanan.css" />
    <link rel="stylesheet" href="../public/css/loader.css" />

    <title>Order Status | Lumintu Event</title>
</head>

<body>
<div class="container">
    <form action="../controller/orderProcess.php" method="post" id="formPesanan">
        <div class="text-center title-page my-3">
            <p class="h2">Status Pesanan</p>
        </div>
        <div class="banner-status rounded container-fluid text-white text-center mb-3">
            <div class="container rounded details-pageStatus d-flex align-items-center justify-content-center">
                <div class="jumbotron my-auto p-5">
                    <p class="h3 nama-event">Dream World Wide in Jogja</p>
                    <p class="h5 tanggal mb-4">By Lumintu Logic</p>
                </div>
            </div>
        </div>

        <div class="container mb-3 d-none voucher text-right">
            <div class="d-flex justify-content-end">
                <input type="text" name="voucher" class="form-control kode-input w-25" placeholder="Voucher Code" />
            </div>
        </div>

        <table class="table display table-responsive-lg table-status">
            <thead>
                <tr>
                    <th class="email-td">Email</th>
                    <th class="name-td">Nama</th>
                    <th class="no-sort ticket-td">Ticket</th>
                    <th class="no-sort price-td">Price</th>
                    <th class="no-sort status-td">Status</th>
                    <th class="no-sort button-td">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr class="text-center">
                    <th colspan="5" class="text-right">Total Invoice :</th>
                    <td>
                    <input type="email" class="input-status w-100" id="total-harga" name="total-harga" value="0" readonly />
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="text-center py-4">
            <button class="rounded py-2 px-5 btn-checkout" disabled type="submit">
                Checkout
            </button>
        </div>
    </form>
</div>

<div class="outer d-none" id="loader">
    <div class="middle">
        <div class="inner text-center">
            <blockquote class="blockquote text-center">
                <p class="mb-0">
                    Sabar dan ikhlas bisa menjadikan kamu seorang yang mulia dan
                    terhormat di dunia sekalipun kamu bukan apa-apa.
                </p>
                <footer class="blockquote-footer">
                    Someone famous in <cite title="Source Title">PINTAR</cite>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<!-- Independent JS -->
<script src="../public/js/statuspesanan.js"></script>
</body>

</html>