<?php
    $customerURL = 'http://arisukarno.xyz:8055/items/customer';
    $invitationURL = 'http://arisukarno.xyz:8055/items/invitation';

    if (isset($_GET['invm'])){
        $myEmail = base64_decode($_GET['invm']);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_email]=' . $myEmail);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $responseID = curl_exec($curl);
        $resultID = json_decode($responseID, true);
        $customerID = $resultID['data'][0]['customer_id'];

        curl_close($curl);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $invitationURL . '?fields=customer_inviter_id.customer_name&filter[customer_id]=' . $customerID);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $responseID = curl_exec($curl);
        $resultID = json_decode($responseID, true);
        $customerName = $resultID['data'][0]['customer_inviter_id']['customer_name'];

        curl_close($curl);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- intlTelInput CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css"
          integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS Independent -->
    <link rel="stylesheet" href="../public/css/invitation.css">

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
        let showPopUp = () => {
            Swal.fire({
                icon: 'success',
                title: 'Invitation Successfull',
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                text: "Wait for next information from someone that invite you",
            })
        }
    </script>

    <title>Invitation | Lumintu Event</title>
</head>

<body>
    <?php
    if (isset($_GET['allScs'])){

        // header('Location: ../view/invitation.php?allScs');
        header('Location: ../view/afterInvitation.php');
        echo '<script type="text/javascript">
                showPopUp();

                $("#email").val("");
                $("#customerName").val("");
            </script>';
    }
    ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12 container-invitation p-5">
            <div class="text-center header">
                <p class="h3 title-status">Event Invitation</p>
                <p class="text-white">From <?php echo $customerName; ?></p>
            </div>
            <div class="content">
                <div class="invitation-form">
                    <form name="formReg" method="post" action="../controller/biodataProcess.php">
                        <input hidden type="text" name="custID" value="<?php echo $customerID; ?>">
                        <div class="form-group email-form">
                            <label for="email" class="text-white">Email</label>
                            <input readonly type="email" name="email" class="form-control" id="email"
                                   placeholder="exampxle : ex@gmail.com" value="<?php echo $myEmail; ?>">
                            <small id="emailHelpBlock" class="form-text text-danger d-none">
                                Your email is not valid!
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="username" class="text-white">Name</label>
                            <input type="text" name="name" class="form-control name-input" id="username"
                                   oninput="allLetter(document.formReg.name)" placeholder="example : Bambang">
                            <small id="nameHelpBlock" class="form-text text-danger">
                                Your name will be place in your certificate
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="text-white">No. Hp</label>
                            <input id="phone" name="phoneNum" type="tel" class="form-control phone-input" oninput="allNumber(document.formReg.phoneNum)">
                            <small id="phoneHelpBlock" class="form-text text-danger d-none">
                                Letters Not Allowed!
                            </small>
                        </div>

                        <div class="text-center position-absolute footer-accept container px-5 pb-5">
                            <div class="form-check text-left">
                                <input type="checkbox" class="form-check-input" id="selectAgree">
                                <label class="form-check-label text-white" for="select1">I have accepted</label>
                            </div>
                            <div class="container text-center mt-2">
                                <button class="btn btn-accept rounded">Accept Invitation</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
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

<!-- intl-tel-input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"
        integrity="sha512-QMUqEPmhXq1f3DnAVdXvu40C8nbTgxvBGvNruP6RFacy3zWKbNTmx7rdQVVM2gkd2auCWhlPYtcW2tHwzso4SA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Independent Javascript -->
<script src="../public/js/invitation.js"></script>
</body>

</html>