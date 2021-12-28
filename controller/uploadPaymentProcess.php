<?php
    session_start();
    $cred = $_SESSION['cred'];

    $urlIP = '192.168.18.67:8001';
    $invoiceURL = 'http://' . $urlIP . '/items/invoice';
    $paymentURL = 'http://' . $urlIP . '/items/payment';
    $fileURL = 'http://' . $urlIP . '/files';

    $curl = curl_init();

    //get customer ID
    curl_setopt($curl, CURLOPT_URL, $invoiceURL . '?fields=invoice_id,customer_id.customer_id,invoice_total&filter[customer_id][customer_code]=' . $cred);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseID = curl_exec($curl);
    $resultID = json_decode($responseID, true);
    $dataLengthID = $resultID["data"];
    $invoiceID = $resultID['data'][0]['invoice_id'];
    $invoiceTotal = $resultID['data'][0]['invoice_total'];

    curl_close($curl);

    if (isset($_POST['submit'])){
        $uploadFile = $_FILES['file']['name'];
        $fileName = substr($uploadFile, 0, -4) . $invoiceID . '.png';
        $temp_name = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        if (isset($uploadFile) and !empty($uploadFile)){
            $location = '../public/temporaryImg/';
            if (move_uploaded_file($temp_name, $location.$fileName)){

                $curl = curl_init();

                $fileLocation = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . '/public/temporaryImg/' . $fileName);

                // post to directus_file first before to payment
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $fileURL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('filename_download'=> new CURLFILE($fileLocation, 'image/png', $fileName)),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: multipart/form-data'
                    ),
                ));

                $uploadPayment = curl_exec($curl);
                $postResponse = json_decode($uploadPayment, true);

                curl_close($curl);

                if (isset($postResponse['errors'][0]['extensions']['code'])) {
                    echo $postResponse['errors'][0]['extensions']['code'];
                }else{
                    $curl = curl_init();

                    //      get fileID
                    curl_setopt($curl, CURLOPT_URL, $fileURL . '?fields=id&filter[filename_download]=' . $fileName);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $responseFile = curl_exec($curl);
                    $resultFile = json_decode($responseFile, true);
                    $fileID = $resultFile['data'][0]['id'];

                    curl_close($curl);

                    $curl = curl_init();

                    //post to payment
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $paymentURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                            "invoice_id": "' . $invoiceID . '",
                            "payment_total": "' . $invoiceTotal . '",
                            "payment_receipt": "' . $fileID . '",
                            "payment_status": 0
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $uploadPayment = curl_exec($curl);
                    $postResponse = json_decode($uploadPayment, true);

                    curl_close($curl);

                    if (isset($postResponse['errors'][0]['extensions']['code'])) {
                        echo $postResponse['errors'][0]['extensions']['code'];
                    }else{
                        echo 'scsAll';
                    }
                }
            }
        }else{
            echo 'failed';
        }
    }
?>