<?php

$url = "https://api-ticket.arisukarno.xyz/items/invoice";


$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
$result = json_decode($response, true);
$data = $result["data"];
curl_close($curl);

// echo $response;

for ($i = 0; $i < sizeof($data); $i++) {
    $invoiceId = $data[$i]["invoice_id"];
    $invoiceEnd = $data[$i]["invoice_end"];
    $invoiceStatus = $data[$i]["invoice_status"];

    // hari ini > invoice end
    $currentDate = new DateTime();
    $endDate = new DateTime($invoiceEnd);

    if ($invoiceStatus == "pending") {
        if($currentDate > $endDate) {
            // echo "<br>" . $data[$i]["invoice_id"] . "<br>";
            // echo "<br>" . $endDate->format('c') . "<br>";
            // echo "<br>" . $currentDate->format('c') . "<br>";
            
            // echo "<br> expired <br>";
    
            $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api-ticket.arisukarno.xyz/items/invoice/$invoiceId",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'PATCH',
                        CURLOPT_POSTFIELDS =>'{
                            "invoice_status": "expire"
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));
    
                $response = curl_exec($curl);
                $result = json_decode($response, true);
                // echo "<br>" . var_export($result) . "<br>";
    
                curl_close($curl);
        }
    }
}

$urlInvitation = "https://api-ticket.arisukarno.xyz/items/invitation";

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $urlInvitation);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$responseInvitation = curl_exec($curl);
$resultInvitation = json_decode($responseInvitation, true);
$dataInvitation = $resultInvitation["data"];
curl_close($curl);

// echo $responseInvitation;

for ($j = 0; $j < sizeof($dataInvitation); $j++) {
    $invitationId = $dataInvitation[$j]["invitation_id"];
    $invitationEnd = $dataInvitation[$j]["invitation_end"];
    $invitationCustomerId = $dataInvitation[$j]["customer_id"];
    $invitationStatus = $dataInvitation[$j]["invitation_status"];

    // hari ini > invitation end
    $currentDateInvitation = new DateTime();
    $endDateInvitation = new DateTime($invitationEnd);

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://api-ticket.arisukarno.xyz/items/order?fields=ticket_id.event_id.event_date_finished&filter[customer_id.customer_id]=" . $invitationCustomerId);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseEvent = curl_exec($curl);
    $resultEvent = json_decode($responseEvent, true);
    $dataEvent = $resultEvent["data"];
    curl_close($curl);

    if (!empty($dataEvent)) {
        $getEventEnd = $dataEvent[0]['ticket_id']['event_id']['event_date_finished'];
        $eventEnd = new DateTime($getEventEnd);

        if (($invitationStatus == "0") or ($invitationStatus == "1") ) {
            echo "Jalan 1";
            if($currentDateInvitation > $endDateInvitation or $currentDateInvitation > $eventEnd) {
            echo "Jalan 2";
                $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api-ticket.arisukarno.xyz/items/invitation/$invitationId",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'PATCH',
                            CURLOPT_POSTFIELDS =>'{
                                "invitation_status": 4
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));
        
                    $response = curl_exec($curl);
                    $result = json_decode($response, true);
                    echo "<br>" . var_export($result) . "<br>";
        
                    curl_close($curl);
            }
        }
    }
}
?>