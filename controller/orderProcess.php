<?php
    session_start();

    date_default_timezone_set("Asia/Bangkok");

    use PHPMailer\PHPMailer\PHPMailer;
    use Dompdf\Dompdf;

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/OAuth.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/POP3.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';
    require "../vendor/autoload.php";

    require_once '../vendor/dompdf/dompdf/src/Autoloader.php';

    $invoiceLink = 'http://localhost/LumintuTicketing/view/invoice.php';

    $urlIP = 'api-ticket.arisukarno.xyz';

    $imagedata = file_get_contents("https://raw.githubusercontent.com/ifetayo14/lumintuEventTicketing/master/public/img/kraton.png");
    // alternatively specify an URL, if PHP settings allow
    $base64 = base64_encode($imagedata);

    $url = 'https://' . $urlIP . '/items/invitation?fields=invitation_id,customer_id.customer_id,customer_id.customer_email,customer_id.customer_name,customer_inviter_id.customer_email,invitation_status&filter[customer_inviter_id][customer_code]=' . $_SESSION['cred'];
    $invoiceURL = 'https://' . $urlIP . '/items/invoice';
    $customerURL = 'https://' . $urlIP . '/items/customer';
    $orderURL = 'https://' . $urlIP . '/items/order';
    $voucherURL = 'https://' . $urlIP . '/items/voucher';
    $invitationURL = "https://' . $urlIP . '/items/invitation";



    $document = new DOMPDF('P', 'A4', 'en', false, 'UTF-8');

    $price = $_POST['total-harga'];
    $currentDate = new DateTime();
    $addDate = $currentDate->add(new DateInterval("P1D"));

    $curl = curl_init();

    //get customer ID
    curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_code]=' . $_SESSION['cred']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseID = curl_exec($curl);
    $resultID = json_decode($responseID, true);
    $dataLengthID = $resultID["data"];
    $customerID = $resultID['data'][0]['customer_id'];
    $inviterEmail = $resultID['data'][0]['customer_email'];

    curl_close($curl);

    $curl = curl_init();

    //get customer-invitation-data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    $result = json_decode($response, true);
    $invitationDataLength = $result["data"];

    curl_close($curl);
    $curl = curl_init();

    if (sizeof($invitationDataLength) == 1){
        // echo "Jalan line 58" . "\n";
        if ($_POST['voucher'] != ''){
            $voucher = $_POST['voucher'];
            echo $voucher;
            //      get voucher
            curl_setopt($curl, CURLOPT_URL, $voucherURL . '?&filter[voucher_code]=' . $voucher);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $responseVoucher = curl_exec($curl);
            $resultVoucher = json_decode($responseVoucher, true);
            $voucherID = $resultVoucher['data'][0]['voucher_id'];
            $discPrice = $resultVoucher['data'][0]['voucher_discount'];
            $voucherStok = $resultVoucher['data'][0]['voucher_stock'];
            $voucherReal = (int)$voucherStok - 1;

            echo "\n" . $voucherStok . "\n" ;

            echo $discPrice . "\n";
            echo $price . "\n";

            $totalPrice = (int)$price - (int)$discPrice;

            echo $totalPrice . "\n";


            curl_close($curl);
            $curl = curl_init();

            //post to invoice
            curl_setopt_array($curl, array(
                CURLOPT_URL => $invoiceURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "customer_id": "' . $customerID . '",
                "invoice_total": "' . $totalPrice . '",
                "invoice_status": "pending",
                "invoice_date": "' . $currentDate->format('c') . '",
                "invoice_end": "' . $addDate->format('c') . '"
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $responseA = curl_exec($curl);
            $resultA = json_decode($responseA, true);  
            echo "\n" . var_export($resultA) . "\n";

            curl_close($curl);

            //---

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-ticket.arisukarno.xyz/items/invitation?filter[customer_inviter_id]=" . $customerID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $responseGet = curl_exec($curl);
        $resultGet = json_decode($responseGet, true);
        $lengthGet = $resultGet["data"];
        curl_close($curl);

        for ($x = 0; $x < sizeof($lengthGet); $x++) {
            $invitationID = $lengthGet[$x]['invitation_id'];
            echo $invitationID;
            // --
            $curl = curl_init();

            echo $invitationURL . '/' . $customerID . "--2";
            //post to invitation
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-ticket.arisukarno.xyz/items/invitation/' . $invitationID,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_POSTFIELDS =>'{
                "invitation_status": 2
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $responseA = curl_exec($curl);
            $resultA = json_decode($responseA, true);  
            echo "\n" . $responseA . "\n";

            curl_close($curl);
            // --
        }

        // ---

            $curl = curl_init();

            //post to voucher
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => $voucherURL . "/" . $voucherID,
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'PATCH',
            //     CURLOPT_POSTFIELDS =>'{
            //     "voucher_stock": ' . $voucherReal . '
            // }',
            //     CURLOPT_HTTPHEADER => array(
            //         'Content-Type: application/json'
            //     ),
            // ));
        }else{
            $voucherID = '0';
            $curl = curl_init();

            //post to invoice
            curl_setopt_array($curl, array(
                CURLOPT_URL => $invoiceURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "customer_id": "' . $customerID . '",
                "invoice_total": "' . $price . '",
                "invoice_status": "pending",
                "invoice_date": "' . $currentDate->format('c') . '",
                "invoice_end": "' . $addDate->format('c') . '"
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
            $responseA = curl_exec($curl);
            $resultA = json_decode($responseA, true);  
            echo "\n" . var_export($resultA) . "\n";

            curl_close($curl);
            // --

            //---

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-ticket.arisukarno.xyz/items/invitation?filter[customer_inviter_id]=" . $customerID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $responseGet = curl_exec($curl);
        $resultGet = json_decode($responseGet, true);
        $lengthGet = $resultGet["data"];
        curl_close($curl);

            for ($x = 0; $x < sizeof($lengthGet); $x++) {
                $invitationID = $lengthGet[$x]['invitation_id'];
                echo $invitationID;
                // --
                $curl = curl_init();

                echo $invitationURL . '/' . $customerID . "--2";
                //post to invitation
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-ticket.arisukarno.xyz/items/invitation/' . $invitationID,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'PATCH',
                    CURLOPT_POSTFIELDS =>'{
                    "invitation_status": 2
                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $responseA = curl_exec($curl);
                $resultA = json_decode($responseA, true);  
                echo "\n" . $responseA . "\n";

                curl_close($curl);
                // --
            }

        // ---
        }

        // $server_response = curl_exec($curl);
        // $postResponse = json_decode($server_response, true);
        // echo "\n" . var_export($server_response) . "\n";

        // curl_close($curl);

        if (isset($postResponse['errors'][0]['extensions']['code'])){
            echo $postResponse['errors'][0]['extensions']['code'];
//            header('Location: ../view/statuspesanan.php?errInv');
        }else{
            $curl = curl_init();
            //      get invoice
            curl_setopt($curl, CURLOPT_URL, $invoiceURL . '?&filter[customer_id]=' . $customerID);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $responseInvID = curl_exec($curl);
            $resultInvID = json_decode($responseInvID, true);
            $invID = $resultInvID['data'][0]['invoice_id'];

            curl_close($curl);
            $curl = curl_init();

            if ($_POST['voucher'] != ''){
                if ($_POST['tiket-peserta-0'] > 3) {
                    $curl2 = curl_init();
                    if ($_POST['tiket-peserta-0'] == 4){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "1",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "2",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }elseif ($_POST['tiket-peserta-0'] == 5){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "1",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "3",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }elseif ($_POST['tiket-peserta-0'] == 6){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "2",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "3",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }elseif ($_POST['tiket-peserta-0'] == 7){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "4",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }
                    $customer_server_response2 = curl_exec($curl2);
                    $customerPostResponse = json_decode($customer_server_response2, true);

                    curl_close($curl2);
                }else{
                    $tiket = $_POST['tiket-peserta-0'];
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $orderURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "' . $tiket . '",
                        "order_quantity": "1",
                        "voucher_id": "' . $voucherID . '"
                    }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));
                }
            }else{
                if ($_POST['tiket-peserta-0'] > 3) {
                    $curl2 = curl_init();
                    if ($_POST['tiket-peserta-0'] == 4){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "1",
                        "order_quantity": "1"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "2",
                        "order_quantity": "1"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }elseif ($_POST['tiket-peserta-0'] == 5){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "1",
                        "order_quantity": "1"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "3",
                        "order_quantity": "1"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }elseif ($_POST['tiket-peserta-0'] == 6){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "2",
                        "order_quantity": "1"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "3",
                        "order_quantity": "1"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }elseif ($_POST['tiket-peserta-0'] == 7){
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "4",
                        "order_quantity": "1"
                    }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }
                    $customer_server_response2 = curl_exec($curl2);
                    $customerPostResponse = json_decode($customer_server_response2, true);

                    curl_close($curl2);
                }else{
                    $tiket = $_POST['tiket-peserta-0'];
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $orderURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                        "invoice_id": "' . $invID . '",
                        "customer_id": "' . $customerID . '",
                        "ticket_id": "' . $tiket . '",
                        "order_quantity": "1"
                    }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));
                }
            }

            $customer_server_response = curl_exec($curl);
            $customerPostResponse = json_decode($customer_server_response, true);

            curl_close($curl);
        }
    }
    else {
        $curl = curl_init();

        //post to invoice
        curl_setopt_array($curl, array(
            CURLOPT_URL => $invoiceURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "customer_id": "' . $customerID . '",
                "invoice_total": "' . $price . '",
                "invoice_status": "pending",
                "invoice_date": "' . $currentDate->format('c') . '",
                "invoice_end": "' . $addDate->format('c') . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $invoice_server_response = curl_exec($curl);
        $invoicePostResponse = json_decode($invoice_server_response, true);

        curl_close($curl);

        //---

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-ticket.arisukarno.xyz/items/invitation?filter[customer_inviter_id]=" . $customerID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $responseGet = curl_exec($curl);
        $resultGet = json_decode($responseGet, true);
        $lengthGet = $resultGet["data"];
        curl_close($curl);

        for ($x = 0; $x < sizeof($lengthGet); $x++) {
            $invitationID = $lengthGet[$x]['invitation_id'];
            echo $invitationID;
            // --
            $curl = curl_init();

            // echo $invitationURL . '/' . $customerID . "--2";
            //post to invitation
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-ticket.arisukarno.xyz/items/invitation/' . $invitationID,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_POSTFIELDS =>'{
                "invitation_status": 2
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $responseA = curl_exec($curl);
            $resultA = json_decode($responseA, true);  
            echo "\n" . $responseA . "\n";

            curl_close($curl);
            // --
        }

        // ---

        $curl = curl_init();

        if (isset($invoicePostResponse['errors'][0]['extensions']['code'])) {
        echo $invoicePostResponse['errors'][0]['extensions']['code'];
//            header('Location: ../view/statuspesanan.php?errInv');
        } else {
            //      get invoice
            curl_setopt($curl, CURLOPT_URL, $invoiceURL . '?&filter[customer_id]=' . $customerID);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $responseInvID = curl_exec($curl);
            $resultInvID = json_decode($responseInvID, true);
            $invID = $resultInvID['data'][0]['invoice_id'];

            for ($x = 0; $x < sizeof($invitationDataLength); $x++) {
                $curl1_[$x] = curl_init();
                $tiketID = $_POST['tiket-peserta-' . $x];

                if ($tiketID > 3) {
                    $curl2_[$x] = curl_init();

                    if ($tiketID == 4) {
                        curl_setopt_array($curl1_[$x], array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": "' . $result['data'][$x]['customer_id']['customer_id'] . '",
                            "ticket_id": "1",
                            "order_quantity": "1"
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2_[$x], array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": "' . $result['data'][$x]['customer_id']['customer_id'] . '",
                            "ticket_id": "2",
                            "order_quantity": "1"
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    } elseif ($tiketID == 5) {
                        curl_setopt_array($curl1_[$x], array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": "' . $result['data'][$x]['customer_id']['customer_id'] . '",
                            "ticket_id": "1",
                            "order_quantity": "1"
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2_[$x], array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": "' . $result['data'][$x]['customer_id']['customer_id'] . '",
                            "ticket_id": "3",
                            "order_quantity": "1"
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    } elseif ($tiketID == 6) {
                        curl_setopt_array($curl1_[$x], array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": "' . $result['data'][$x]['customer_id']['customer_id'] . '",
                            "ticket_id": "2",
                            "order_quantity": "1"
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));

                        curl_setopt_array($curl2_[$x], array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": "' . $result['data'][$x]['customer_id']['customer_id'] . '",
                            "ticket_id": "3",
                            "order_quantity": "1"
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    } elseif ($tiketID == 7) {
                        curl_setopt_array($curl1_[$x], array(
                            CURLOPT_URL => $orderURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": "' . $result['data'][$x]['customer_id']['customer_id'] . '",
                            "ticket_id": "4",
                            "order_quantity": "1"
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                            ),
                        ));
                    }

                    $response2 = curl_exec($curl2_[$x]);
                    curl_close($curl2_[$x]);

                } else {
                    curl_setopt_array($curl1_[$x], array(
                        CURLOPT_URL => $orderURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => '{
                            "invoice_id": "' . $invID . '",
                            "customer_id": ' . $result['data'][$x]['customer_id']['customer_id'] . ',
                            "ticket_id": "' . $tiketID . '",
                            "order_quantity": "1"
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));
                }

                $response1 = curl_exec($curl1_[$x]);
                $customerPostResponse = json_decode($response1, true);
                curl_close($curl1_[$x]);

            }
        }
    }

    if (isset($customerPostResponse['errors'][0]['extensions']['code'])){
        echo $customerPostResponse;
        // header('Location: ../view/statusPesanan.php?errCus');
    }else{
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $orderURL . '?fields=invoice_id.invoice_id&filter[customer_id][customer_id]=' . $customerID . "&filter[invoice_id][invoice_status]=pending",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $responseGet = curl_exec($curl);
        $resultGet = json_decode($responseGet, true);
        $lengthGet = $resultGet["data"];
        curl_close($curl);
        $invoiceId = $lengthGet[0]['invoice_id']['invoice_id'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $orderURL . '?fields=customer_id.customer_id,customer_id.customer_name,ticket_id.ticket_type,ticket_id.ticket_price,invoice_id.invoice_id,invoice_id.invoice_total,invoice_id.invoice_status,voucher_id.voucher_discount&filter[invoice_id][invoice_id]=' . $invoiceId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $result = json_decode($response, true);
        $length = $result["data"];
        curl_close($curl);

        //ToPDF
        $output = '  
            <html>
            <style>
                .imgEvent {
                    margin-bottom: 32px;
                }
            
                .imgEvent img {
                    height: 300px;
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center center;
                    margin: 10px 0;
                }
            
                .imgEvent p,
                h4 {
                    margin: 8px;
                }
            
                #detail {
                    width: 100%;
                    border-collapse: collapse;
                    text-align: center;
                    margin-bottom: 20px;
                }
            
                #detail td {
                    border-bottom: 1pt solid grey;
                    padding: 10px;
                }
            
                .leftSide {
                    text-align: left;
                }
            
                .rightSide {
                    text-align: end;
                }
            
                .textCenter {
                    text-align: center;
                }
            
                .bank {
                    padding: 10px 24px;
                    margin: 10px 0;
                }
            
                @page { margin: 0 100px 0 100px; }
            body { margin: 0px; }
            </style>
            
            <body style="margin:0;padding:0;">
                <table role="presentation"
                    style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                    <tr>
                        <td align="center" style="padding:0;">
                            <table role="presentation"
                                style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                <!--<tr>
                                    <td align="center" style="padding:20px 0 0 0;background:#38435F;">
                                        <img src="data:image/png;base64, <?php echo $base64; ?>" alt="" width="20%" height="20%"
                                            style="height:auto;display:block; padding-top: 30px;" />
                                        <h2 style="color: #D4AF37; margin: 0 0 20px 0">Welcome to Symposium</h2>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td style="padding:20px 40px;">
                                        <table role="presentation"
                                            style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                            <tr>
                                                <td style="color:#153643;">
                                                    <div class="invoice">
                                                        <div align="center" style="padding:20px 0 10px 0;background:#38435F;">
                                                            <img src="data:image/png;base64, <?php echo $base64; ?>" alt="" width="20%" height="20%"
                                                                style="height:auto;display:block; padding-top: 30px;" />
                                                            <h2 style="color: #D4AF37; margin: 0 0 20px 0">Welcome to Symposium</h2>
                                                        </div>
                                                        <div class="">
                                                            <h2 class="textCenter">
                                                                Thanks For You Order!
                                                            </h2>
                                                            <div>
                                                                <h3 class="textCenter" style=" margin:0;line-height:24px;">
                                                                    <u>Your Order</u>
                                                                </h3>
                                                                <p class="textCenter" style=" font-size: small; margin-bottom: 32px">Monday, Oct 20 2021
                                                                    at 03.00pm</p>
                                                                <!-- <div class="imgEvent" alt="" align="center">
                                                                    <img src="./assets/event1.jpg" alt="">
                                                                    <h4 style="color: #D4AF37;">Dream World Wide in Jogja</h4>
                                                                    <p style="color: #38435F;">By Lumintu Logic</p>
                                                                </div> -->
                                                            </div>
                                                            <table id="detail" align="center">
                                                                <tr>
                                                                    <td class="leftSide"><b>Name</b></td>
                                                                    <td><b>Ticket</b></td>
                                                                    <td class="rightSide"><b>Price</b></td>
                                                                </tr>
                    ';

        for ($i = 0; $i < sizeof($length); $i++){
            $output .= '
                                                        <tr>
                                                            <td class="leftSide">' . $result["data"][$i]["customer_id"]["customer_name"] . '</td>
                                                            <td>' . $result["data"][$i]["ticket_id"]["ticket_type"] . '</td>
                                                            <td class="rightSide">' . $result["data"][$i]["ticket_id"]["ticket_price"] . '</td>
                                                        </tr>
                        ';
        }
        $output .= '
                                                        <tr>
                                                            <td class="leftSide"><b>Discount </b></td>
                                                            <td></td>
                                                            <td class="rightSide">-' . $result["data"][0]["voucher_id"]["voucher_discount"] . '</td>
                                                        </tr>
                        ';

        $output .= '
                                                        <tr>
                                                            <td class="leftSide"><b>Total</b></td>
                                                            <td></td>
                                                            <td class="rightSide">' . $result["data"][0]["invoice_id"]["invoice_total"] . '</td>
                                                        </tr>
                        ';

        $output .= '
                                                    </table>
                                                <div>
                                                    <div class="bank" style="color: white; background-color: #38435F;">
                                                        <p class="textCenter" style="font-size: 24px; margin: 0 0 20px 0;"><b>BRI</b>
                                                        </p>
                                                        <table align="center" class="textCenter" style="color: white;">
                                                            <tr>
                                                                <td style="padding-bottom: 10px; font-weight: 600;">Mohammad Arafat Maku
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>720222190601002</td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="bank"
                                                        style="color: #38435F; background-color: #D4AF37;">
                                                        <p class="textCenter" style="font-size: 24px; margin: 0 0 20px 0;"><b>BNI</b>
                                                        </p>
                                                        <table align="center" class="textCenter">
                                                            <tr>
                                                                <td style="padding-bottom: 10px; font-weight: 600;">Mohammad Arafat Maku
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>720222190601002</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="bank" style="color: white; background-color: #38435F;">
                                                        <p class="textCenter" style="font-size: 24px; margin: 0 0 20px 0;"><b>BCA</b>
                                                        </p>
                                                        <table align="center" class="textCenter" style="color: white;">
                                                            <tr>
                                                                <td style="padding-bottom: 10px; font-weight: 600;">Mohammad Arafat Maku
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>720222190601002</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="textCenter" style="padding:20px 40px; background-color: #38435F;">
                            <p
                                style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                &reg; Send by Lumintu Events<br />
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>


</html>';

        $document->loadHtml($output);
        $document->render();
        $invoiceOutput = $document->output();
        file_put_contents('../public/pdfFile/Invoice-' . $customerID . '.pdf', $invoiceOutput);
    //                $document->stream('Invoice', array("Attachment"=>0));

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
        $mail->Subject = "[Lumintu Events] Thank you for your order.";
        $mail->isHTML(true);

        $mailLocation = '../view/email/emailInvoice.html';
        $message = file_get_contents($mailLocation);
        $message = str_replace('%name%', $resultID['data'][0]['customer_name'], $message);
        $message = str_replace('%link%', $invoiceLink . "?m=" . $_SESSION['cred'], $message);

        $mail->msgHTML($message);
        $mail->addAttachment('../public/pdfFile/Invoice-' . $customerID . '.pdf');

        $mail->send();
    }

    // header('Location: ../view/paymentMidtrans.php?m=' . $_SESSION['cred']);
    
?>