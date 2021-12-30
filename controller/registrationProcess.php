<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/OAuth.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/POP3.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';

    include('../config.php');

    $customerURL = 'http://arisukarno.xyz:8055/items/customer';

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNum = $_POST['phoneNum'];
    $cusCode = hash('sha512', $email.$phoneNum);
    $loginLink = 'http://localhost/LumintuTicketing/controller/verificationProcess.php?m=' . $cusCode;

    $query = "INSERT INTO `customer`(`customer_email`, `customer_name`, `customer_phone`, `customer_code`)
                SELECT '$email', '$name', '$phoneNum', '$cusCode'
                FROM (SELECT 1)a WHERE NOT EXISTS (SELECT `customer_id` FROM `customer` WHERE `customer_email` = '$email')";
    $runQuery = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $affRow = $conn->affected_rows;

    if ($affRow == 0){
        header('Location: ../view/registration/registration.php?mailExist');
    }
    else{
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
        $mail->Subject = "[Lumintu Events] Verifikasi Email";
        $mail->isHTML(true);
//            $mail->Body = 'Hai ' . $name . ', silahkan klik link berikut untuk verifikasi email anda. Link ini juga digunakan untuk akses landing page<br/><br/>
//                        <a href="' . $loginLink . '?m=' . $cusCode .'">Verifikasi Email</a>';

        $mailLocation = '../view/email/emailVerification.html';
        $message = file_get_contents($mailLocation);
        $message = str_replace('%name%', $name, $message);
        $message = str_replace('%link%', $loginLink, $message);

        $mail->msgHTML($message);

        if ($mail->send()){
            header('Location: ../view/registration/registration.php?scs');
        }
        else{
//            echo $mail->ErrorInfo;
            header('Location: ../view/registration/registration.php?mailErr');
        }
    }

?>
