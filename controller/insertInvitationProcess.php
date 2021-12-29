<?php

    session_start();
    $cred = $_SESSION['cred'];
    $buyTicketLink = 'http://localhost/lumintu_logic/API/lumintuEventTicketing/view/statuspesanan.php';
    $bioLink = 'http://localhost/lumintu_logic/API/lumintuEventTicketing/view/invitation.php';
    $customerURL = 'http://192.168.18.67:8001/items/customer';
    $invitationURL = 'http://192.168.18.67:8001/items/invitation';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/OAuth.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/POP3.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';

    $numberOfPost = count($_POST);

    $counter = 0;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_code]=' . $cred);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseID = curl_exec($curl);
    $resultID = json_decode($responseID, true);
    $inviterEmail = $resultID['data'][0]['customer_email'];
    $inviterID = $resultID['data'][0]['customer_id'];

    curl_close($curl);

    if ($numberOfPost == 1) {
        if ($inviterEmail == $_POST['peserta1']) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $invitationURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                        "customer_id": "' . $inviterID . '",
                        "customer_inviter_id": " '. $inviterID .' ",
                        "invitation_status": "1"
                    }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $getResponse = curl_exec($curl);
            $onCreateResponseInvitation = json_decode($getResponse, true);

            curl_close($curl);

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

            $mail->addAddress($inviterEmail);
            $mail->Subject = "[Lumintu Events] Link Pemesanan Tiket";
            $mail->isHTML(true);
            // $mail->Body = 'Hai ' . $resultID['data'][0]['customer_name'] . ', silahkan klik link berikut untuk melakukan pemesanan tiket<br/><br/>
            //                 <a href="' . $buyTicketLink . '?m=' . $resultID['data'][0]['customer_code'] . '">Pesan Tiket</a>';

            $mailLocation = '../view/email/emailConfirmation.html';
            $message = file_get_contents($mailLocation);
            $message = str_replace('%name%', $resultID['data'][0]['customer_name'], $message);
            $message = str_replace('%link%', $buyTicketLink, $message);

            $mail->msgHTML($message);

            if ($mail->send()) {
                header('Location: ../view/details.php?scs');
            } else {
                header('Location: ../view/details.php?mailErrSolo');
            }
        }
    }else{

        for ($x = 2; $x <= $numberOfPost; $x++){
            if ($_POST['peserta'.$x] != ''){
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_email]=' . $_POST['peserta'.$x]);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $responseID = curl_exec($curl);
                $resultID = json_decode($responseID, true);

                if (isset($resultID['data'][0]['customer_email'])){
                    $counter++;
                }

                curl_close($curl);
            }
        }

        if ($counter == 0){
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $invitationURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                        "customer_id": "' . $inviterID . '",
                        "customer_inviter_id": " '. $inviterID .' ",
                        "invitation_status": "1"
                    }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $getResponse = curl_exec($curl);
            $onCreateResponseInvitation = json_decode($getResponse, true);

            curl_close($curl);

            for ($x = 2; $x <= $numberOfPost; $x++){
                $pesertaEmail = $_POST['peserta'.$x];
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $customerURL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                    "customer_email": "' . $pesertaEmail . '"
                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $getResponse = curl_exec($curl);
                $onCreateResponseCustomer = json_decode($getResponse, true);

                curl_close($curl);

                if (!isset($onCreateResponseCustomer['errors'][0]['extensions']['code'])){
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_email]=' . $_POST['peserta'.$x]);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $responseID = curl_exec($curl);
                    $resultID = json_decode($responseID, true);

                    curl_close($curl);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $invitationURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                        "customer_id": "' . $resultID['data'][0]['customer_id'] . '",
                        "customer_inviter_id": " '. $inviterID .' ",
                        "invitation_status": "0"
                    }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $getResponse = curl_exec($curl);
                    $onCreateResponseInvitation = json_decode($getResponse, true);

                    curl_close($curl);

                    if (!isset($onCreateResponseInvitation['errors'][0]['extensions']['code'])){

                    }else{
                        header('Location: ../view/details.php?errOnInv');
                    }
                }
                else{
                    header('Location: ../view/details.php?errCus');
                }
            }

            for ($x = 1; $x <= $numberOfPost; $x++){
                $pesertaEmail = $_POST['peserta'.$x];
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

                if ($x == 1){
                    $mail->addAddress($inviterEmail);
                    $mail->Subject = "[Lumintu Events] Link Pemesanan Tiket";
                    $mail->isHTML(true);
//                    $mail->Body = 'Hai ' . $inviterEmail . ', silahkan klik link berikut untuk melakukan pemesanan tiket<br/><br/>
//                                        <a href="' . $buyTicketLink . '?m=' . $cred .'">Pesan Tiket</a>';
                    $mailLocation = '../view/email/emailToOrder.html';
                    $message = file_get_contents($mailLocation);
                    $message = str_replace('%inviterMail%', $inviterEmail, $message);
                    $message = str_replace('%link%', $buyTicketLink . '?m=' . $cred, $message);
                    $mail->msgHTML($message);
                }
                else{
                    $mail->addAddress($pesertaEmail);
                    $mail->Subject = "[Lumintu Events] Link Pengisian Biodata Pemesanan Tiket";
                    $mail->isHTML(true);
//                    $mail->Body = 'Silahkan klik link berikut untuk melakukan pengisian biodata untuk pemesanan tiket.<br/><br/>
//                                <a href="' . $bioLink . '?invm=' . base64_encode($pesertaEmail) .'">Pesan Tiket</a>';

                    $mailLocation = '../view/email/emailInvitation.html';
                    $message = file_get_contents($mailLocation);
                    $message = str_replace('%receiverMail%', $pesertaEmail, $message);
                    $message = str_replace('%inviterMail%', $inviterEmail, $message);
                    $message = str_replace('%link%', $bioLink . '?invm=' . base64_encode($pesertaEmail), $message);
                    $mail->msgHTML($message);
                }

                if ($mail->send()){
                    header('Location: ../view/details.php?allScs');
                }else{
                    header('Location: ../view/details.php?mailFailed');
                }
                $mail->clearAddresses();
            }
        }else{
            header('Location: ../view/details.php?dupEm');
        }
    }

?>