<?php

echo "Jalan..";

$curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "http://20.124.214.5:8055/items/registration/1",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,   
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'PATCH',
        //     CURLOPT_POSTFIELDS =>'{
        //         "id_participant": 1,
        //         "id_session": 2

        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://20.124.214.5:8055/items/registration",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "id_participant": "200",
                "id_session": "201",
                "ticket_type" : "Undangan"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $responseInvID = curl_exec($curl);
        $resultInvID = json_decode($responseInvID, true);  

        if (!isset($postResponse['errors'][0]['extensions']['code'])){
            echo "Bisaa" . "\n";
            echo var_export($responseInvID);
            }

        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => "https://register.ulin-app.xyz/v1/register",
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS =>'{
        //         "id_participant": "' . $resultID['data'][$x]['customer_id'] . '",
        //         "id_session": "' . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id'] . '",
        //         "ud_ticket : "' . $resultID['data'][$x]['ticket_id']['ticket_type'] . '"
        //     }',
        //         CURLOPT_HTTPHEADER => array(
        //             'Content-Type: application/json'
        //         ),
        //     ));

        //     $response = curl_exec($curl);
        //     $result = json_decode($response, true);

        //     if (isset($result['errors'][0]['extensions']['code'])){
        //         echo $result['errors'][0]['extensions']['code'];
        //     }
        //     else{
        //         echo 'scs';
        //     }

?>