<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/OAuth.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/POP3.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';

    include('../config.php');

    $email = $_POST['email'];
    // $password = $_POST['password'];

    $customerURL = 'http://arisukarno.xyz:8055/items/customer';

    $query = "SELECT `customer_email`, `customer_code`, `customer_name` FROM `customer` WHERE `customer_email` = '$email'";

    // $query = "SELECT `customer_password` FROM `customer` WHERE `customer_email` = '$email'";
    $getEmail = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $passEmail = $getEmail->fetch_assoc();

    $customerName = $passEmail['customer_name'];
    $customerEmail = $passEmail['customer_email'];
    $customerCode = $passEmail['customer_code'];

    $loginLink = 'http://localhost/lumintu_logic/API/lumintuEventTicketing/controller/verificationProcess.php?m=' . $customerCode;


    if ($customerEmail == $email) {
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mintuticketing@gmail.com';
        $mail->Password = 'Mintu123';
        $mail->Port = 587;

        $mail->setFrom('mintuticketing@gmail.com', 'Lumintu Events');
        $mail->addAddress($email);
        $mail->Subject = "[Lumintu Events] Login Email";
        $mail->isHTML(true);

        $mail->Body = 'Hai ' . $customerName . ', silahkan klik link berikut untuk login kembali menggunakan email anda. Link ini juga digunakan untuk akses landing page<br/><br/>
                       <a href="' . $loginLink . '">Verifikasi Email</a>';

        // $mailLocation = '../view/email/emailVerification.html';
        // $message = file_get_contents($mailLocation);
        // $message = str_replace('%name%', $name, $message);
        // $message = str_replace('%link%', $loginLink, $message);

        // $mail->msgHTML($message);

        if ($mail->send()){
            header('Location: ../view/login.php?scs');
        }
        else{
//            echo $mail->ErrorInfo;
            header('Location: ../view/login.php?mailErr');
        }
    } else {
        header('Location: ../view/login.php?emailNotExist');
    }

    // if (md5($password) == $passData){
    //     $verQuery = "SELECT `status` FROM `customer` WHERE `customer_email` = '$email'";
    //     $getStatus = mysqli_query($conn, $verQuery) or die(mysqli_error($conn));
    //     if ($getStatus->fetch_array()[0] == 'Verified'){
    //         header('Location: ../view/details.php');
    //     }else{
    //         header('Location: ../view/login.php?st');
    //     }
    // }
    // else{
    //     header('Location: ../view/login.php?wp');
    // }
?>