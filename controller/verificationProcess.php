<?php
    session_start();

    // include 'sendPaidCustomer.php';
    $accessToken = '?access_token=Q$Q68KDADkOvPtHPXhJxtfFafr0rKSuUL40fV5uy6JYDo';


    $_SESSION['cred'] = $_GET['m'];

    $customerURL = 'https://api-ticket.arisukarno.xyz/items/customer';

    $curl = curl_init();

    //get customer ID
    curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_code]=' . $_SESSION['cred']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseID = curl_exec($curl);
    $resultID = json_decode($responseID, true);
    $customerStatus = $resultID['data'][0]['customer_status'];

    curl_close($curl);

    if ($customerStatus == '0'){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $customerURL . '/' . $resultID['data'][0]['customer_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,   
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS =>'{
                "customer_status": 1
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $responseInvID = curl_exec($curl);
        $resultInvID = json_decode($responseInvID, true);  

        if (!isset($postResponse['errors'][0]['extensions']['code'])){
        //    echo $postResponse['errors'][0]['extensions']['code'];
            header('Location: ../view/main.php?scs');
        }
    }else{
        header('Location: ../view/main.php');
    }
?>
