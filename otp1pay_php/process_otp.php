<?php
function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid =
            substr($charid, 0, 8)
            .substr($charid, 8, 4)
            .substr($charid,12, 4)
            .substr($charid,16, 4)
            .substr($charid,20,12);
        return $uuid;
    }
}
 function execPostRequest($url, $data)
 {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
 }
 $access_key = ""; //access key do 1pay cung cấp.
 $secret = ""; //secret key do 1pay cung cấp.
 $msisdn = $_POST['msisdn'];   
 $amount = $_POST['amount'];    // thấp nhất là 1000 vnd.
 $content = $_POST['content'];   
 $requestId = getGUID(); // MC tu sinh ra
 $data = "access_key=" .$access_key. "&amount=" .$amount. "&content=" .$content. "&msisdn=" .$msisdn. "&requestId=" .$requestId;
 $signature = hash_hmac("sha256", $data, $secret);
 $data.= "&signature=" . $signature;
 $json_bankCharging = execPostRequest('http://api.1pay.vn/direct-charging/charge/request', $data);
 $decode_bankCharging=json_decode($json_bankCharging,true);  // decode json
 $errorMessage = $decode_bankCharging["errorMessage"];
 $requestId_back = $decode_bankCharging["requestId"];
 $transId = $decode_bankCharging["transId"];
 $errorCode = $decode_bankCharging["errorCode"];
 
 $url = "&access_key=".$access_key."&amount=".$amount."&requestId=".$requestId."&transId=".$transId;
 $pay_url= "./confirm_otp.php?".$url;
 header("Location: $pay_url");  //URL address implement submit request (redirect)
   
?>