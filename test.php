<?php

echo "Jalan..";

$curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://13.92.234.79:8055/items/customer/165",
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
            echo "Bisaa" . "\n";
            echo var_export($responseInvID);
            }

?>