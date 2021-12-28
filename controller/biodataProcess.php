<?php
    include('../config.php');

    $customerURL = 'http://192.168.18.67:8001/items/customer';
    $invitationURL = 'http://192.168.18.67:8001/items/invitation';

    $custID = $_POST['custID'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $phone = $_POST['phoneNum'];
    $customerCode = hash('sha512', $email.$phone);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $customerURL . '/' . $custID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
                    "customer_code": "' . $customerCode . '",
                    "customer_name": "' . $name . '",
                    "customer_phone": "' . $phone . '",
                    "customer_status": "1"
                }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $responseCustomer = curl_exec($curl);
    $resultCustomer = json_decode($responseCustomer, true);

    curl_close($curl);

    if (!isset($resultCustomer['errors'][0]['extensions']['code'])){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $invitationURL . '?&filter[customer_id]=' . $custID);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $responseID = curl_exec($curl);
        $resultID = json_decode($responseID, true);
        $invitationID = $resultID['data'][0]['invitation_id'];

        curl_close($curl);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $invitationURL . '/' . $invitationID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS =>'{
                    "invitation_status": "1"
                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $responseInvitation = curl_exec($curl);
        $resultInvitation = json_decode($responseInvitation, true);

        curl_close($curl);

        if (!isset($resultInvitation['errors'][0]['extensions']['code'])){
            header('Location: ../view/invitation.php?allScs');
        }else{
            header('Location: ../view/invitation.php?errInv');
        }

    }else{
        header('Location: ../view/invitation.php?errOnCus');
    }
?>