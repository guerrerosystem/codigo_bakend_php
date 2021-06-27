<?php 
    header('Access-Control-Allow-Origin: *');
    //Function to connect to SMS sending server using HTTP GET
    function sendSms($recipients, $messagetext, $country_code) {
     $curl = curl_init();
	 $api_secret = "your_secret";
	 $api_key = "your_key";
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://rest.nexmo.com/sms/json",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "from=eKart&text=".$messagetext."&to=$country_code$recipients&api_key=$api_key&api_secret=$api_secret",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/x-www-form-urlencoded"
      ),
    ));
    $response = curl_exec($curl);
    // print_r($response);
    curl_close($curl);
    return $response;
}

?>