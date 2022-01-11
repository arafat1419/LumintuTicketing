<?php

// $url = 'https://' . $urlIP . '/items/invitation?fields=invitation_id,customer_id.customer_id,customer_id.customer_email,customer_id.customer_name,customer_inviter_id.customer_email,invitation_status&filter[customer_inviter_id][customer_code]=' . $_SESSION['cred'];
// $invoiceURL = 'https://' . $urlIP . '/items/invoice';
// $customerURL = 'https://' . $urlIP . '/items/customer';
// $orderURL = 'https://' . $urlIP . '/items/order';
// $voucherURL = 'https://' . $urlIP . '/items/voucher';


//     $curl = curl_init();

//     //get customer ID
//     curl_setopt($curl, CURLOPT_URL, $customerURL . '?&filter[customer_code]=' . $_SESSION['cred']);
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//     $responseID = curl_exec($curl);
//     $resultID = json_decode($responseID, true);
//     $dataLengthID = $resultID["data"];
//     $customerID = $resultID['data'][0]['customer_id'];
//     $inviterEmail = $resultID['data'][0]['customer_email'];

//     curl_close($curl);

session_start();

header('Location: ../view/paymentMidtrans.php?m=' . $_SESSION['cred']);

?>