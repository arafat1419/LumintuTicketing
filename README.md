# DOCUMENTATION REST API LUMINTU TICKETING 

### DESCRIPTION
This API provides using [Directus](https://directus.io/) RESTfull API for ticket booking web applications by Lumintu Events. See full documentaion in [RESTfull API Directus](https://docs.directus.io/reference/introduction/).

### API ENDPOINT 
#### API Format
```ruby
https://api-ticket.arisukarno.xyz/items/{collections}}?access_token={token}
```
#### List Collections
* customer
* order
* invitation 
* invoice 
* event
* payment
* ticket 
* ticket_x_day
* day 
* ticket_x_session
* session
* ticket_x_voucher
* voucher 

### PARAMETERS
|Action     | Url   | Method | Return | Example     | Data Payload | 
|-----------|-------|--------|--------|-------------|--------------|
|Get data customer | /items/customer?access_token={token} | `GET` | Data Customer | https://api-ticket.arisukarno.xyz/items/customer?access_token={token} | `"name"`, `"email"`, `"phone"`, `"date"`, `"status"`, `"code"` |
|Get data invitation | /items/invitation?access_token={token} | `GET` | Data Customer Invitation | https://api-ticket.arisukarno.xyz/items/invitation | `"customer_id"`, `"invitation_status"`, `"customer_invitation_id"`, , `"invitation_date"` |
|Get specific order with data ticket and event | /items/order?fields=invoice_id,customer_id.customer_id,customer_id.customer_name,customer_id.customer_email,ticket_id.ticket_type,ticket_id.ticket_x_day.day_id.day_date,ticket_id.event_id.event_name,ticket_id.event_id.event_address&filter[invoice_id][invoice_status]=1&filter[customer_id][customer_id]={customer_id}?access_token={token} | `GET` | Data Customer Order with ticket and event | https://api-ticket.arisukarno.xyz/items/order?fields=invoice_id,customer_id.customer_id,customer_id.customer_name,customer_id.customer_email,ticket_id.ticket_type,ticket_id.ticket_x_day.day_id.day_date,ticket_id.event_id.event_name,ticket_id.event_id.event_address&filter[invoice_id][invoice_status]=1&filter[customer_id][customer_id]=178?access_token={token} | `"customer_id"`, `"customer_name"`, `"customer_name"`, , `"ticket_type"`, `"day_date"`, `"event_name"`, `"event_address"` |
|Get data customer have been successfully paid  | /items/order?fields=customer_id,invoice_id.invoice_status,ticket_id.ticket_type,ticket_id.ticket_x_session.session_id&filter[invoice_id][invoice_status]={invoice_status}?access_token={token} | `GET` | Data Customer Success Paid | https://api-ticket.arisukarno.xyz/items/order?fields=customer_id,invoice_id.invoice_status,ticket_id.ticket_type,ticket_id.ticket_x_session.session_id&filter[invoice_id][invoice_status]=1?access_token={token} | `"customer_id"`, `"invoice_status"`, `"ticket_type"`, , `"session_id"` |

### EXAMPLE REQUEST 
You can use the methods you usually use like PHP Curl, JavaScript Jquery and others.
* Example Using PHP Curl 
```
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-ticket.arisukarno.xyz/items/order?fields=customer_id,invoice_id.invoice_sstatus,ticket_id.ticket_type,ticket_id.ticket_x_session.session_id&filter%5Binvoice_id%5D%5Binvoice_status%5D=1?access_token={token}',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
``` 

### POSSIBLE ERROR 
|Status Code | Description | 
|------------|-------------|
|403 Forbiden | You don't have permission to access the collection, e.g. not include the token access | 
|401 Unauthorized | The token that you submit is false or invalid | 
|500 Internal Server | The api cannot be access cause the server is off or problem in API server |
