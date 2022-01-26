<?php
echo "JALAN \n";

class advCurl {
    public $url;
    public $request;
    public $postJson;
    public $response;

    function __construct($url, $request, $postJson) {
    echo "JALAN 2 \n";

        $this->url = $url;
        $this->request = $request;
        $this->postJson = $postJson;
    }

    protected function doCurl() {
    echo "JALAN 3 \n";
        $curl = $curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->request,
            CURLOPT_POSTFIELDS => $this->postJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $responseCurl = curl_exec($curl);
        $resultCurl = json_decode($responseCurl, true);
        curl_close($curl);
        return var_export($resultCurl);
    }

    function getResponse() {
        $curl = $curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->request,
            CURLOPT_POSTFIELDS => $this->postJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $responseCurl = curl_exec($curl);
        $resultCurl = json_decode($responseCurl, true);
        curl_close($curl);
        $this->response =  var_export($resultCurl);
        $this->response = "arafat hebat";
        return $this->response;
    }
}

$advCurl = new advCurl("http://localhost:8055/items/invoice/179", "PATCH", '{"invoice_status": "expire"}');
echo $advCurl->getResponse();



?>