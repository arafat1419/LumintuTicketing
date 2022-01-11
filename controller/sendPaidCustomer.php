<?php
//    $url = 'http://192.168.43.162:8055/items/ticket?fields=ticket_x_session.session_id';
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://api-ticket.arisukarno.xyz/items/order?fields=customer_id.customer_id,customer_id.customer_name,customer_id.customer_status,invoice_id.invoice_status,ticket_id.ticket_type,ticket_id.ticket_x_session.session_id.*&filter[invoice_id][invoice_status]=1");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseID = curl_exec($curl);
    $resultID = json_decode($responseID, true);
    $dataLengthID = $resultID["data"];


    curl_close($curl);

    for ($x = 0; $x < sizeof($dataLengthID); $x++){
        $lengthDua = $resultID['data'][$x]['ticket_id']['ticket_x_session'];
//        echo $resultID['data'][$x]['customer_id'] . ' ';
        for ($i = 0; $i < sizeof($lengthDua); $i++){
            // echo "Session ID" . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_id'] . "<br>";
            // echo "Session Desc" . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_type'] . "<br>";
            // echo "Session Type" . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_desc'] . "<br><br>";
            // echo "Customer ID" . $resultID['data'][$x]['customer_id']['customer_id'] . "<br>";
            // echo "Customer Name" . $resultID['data'][$x]['customer_id']['customer_name'] . "<br>";
            // echo "Customer Status" . $resultID['data'][$x]['customer_id']['customer_status'] . "<br><br>";
//            echo $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id'];

            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => "https://register.ulin-app.xyz/v1/register",
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //         CURLOPT_POSTFIELDS =>'{
            //             "id_participant": "' . $resultID['data'][$x]['customer_id']['customer_id'] . '",
            //             "id_seminar": "' . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_id'] . '",
            //             "ticket_type" : "' . $resultID['data'][$x]['ticket_id']['ticket_type'] . '"
            //     }',
            //     CURLOPT_HTTPHEADER => array(
            //         'Content-Type: application/json'
            //     ),
            // ));

            // $response = curl_exec($curl);
            // $result = json_decode($response, true);
            // echo var_export($result) . "\n";


            // if (isset($result['errors'][0]['extensions']['code'])){
            //     echo $result['errors'][0]['extensions']['code'];
            // }
            // else{
            //     echo 'scs';
            //     echo var_export($result) . "\n";
            // }

            // curl_close($curl);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://checkin.nvia.xyz/items/session",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "id": "' . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_id'] . '",
                        "session_type": "' . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_type'] . '",
                        "session_desc": "' . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_desc'] . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $result = json_decode($response, true);
            echo var_export($result) . "\n";


            if (isset($result['errors'][0]['extensions']['code'])){
                echo $result['errors'][0]['extensions']['code'];
            }
            else{
                echo 'scs';
                echo var_export($result) . "\n";
            }

            curl_close($curl);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://checkin.nvia.xyz/items/customer",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "id": "' . $resultID['data'][$x]['customer_id']['customer_id'] . '",
                        "name": "' . $resultID['data'][$x]['customer_id']['customer_name'] . '",
                        "status": ' . $resultID['data'][$x]['customer_id']['customer_status'] . '
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $result = json_decode($response, true);
            echo var_export($result) . "\n";


            if (isset($result['errors'][0]['extensions']['code'])){
                echo $result['errors'][0]['extensions']['code'];
            }
            else{
                echo 'scs';
                echo var_export($result) . "\n";
            }

            curl_close($curl);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://checkin.nvia.xyz/items/registration",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "id_participant": "' . $resultID['data'][$x]['customer_id']['customer_id'] . '",
                        "id_session": "' . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id']['session_id'] . '",
                        "ticket_type" : "' . $resultID['data'][$x]['ticket_id']['ticket_type'] . '"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $result = json_decode($response, true);
            echo var_export($result) . "\n";


            if (isset($result['errors'][0]['extensions']['code'])){
                echo $result['errors'][0]['extensions']['code'];
            }
            else{
                echo 'scs';
                echo var_export($result) . "\n";
            }

            curl_close($curl);
        }
        echo '<br/>';
    }
?>
