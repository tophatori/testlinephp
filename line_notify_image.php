<?php
/*Get Data From POST Http Request*/
$datas = file_get_contents('php://input');
/*Decode Json From LINE Data Body*/
$deCode = json_decode($datas, true);
file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);
$replyToken = $deCode['events'][0]['replyToken'];


$userID = $jsonData["events"][0]["source"]["userId"];
$text = $jsonData["events"][0]["message"]["text"];
$timestamp = $jsonData["events"][0]["timestamp"];
$str_input =  explode("รูป", $text);


$str_path_image =  'http://vpn.idms.pw:9977/polis/imagebyte?id=' . $str_input['1'];

sendLineNotify($str_input['1']);

$messages = [];
$messages['replyToken'] = $replyToken;
$messages['messages'][0] = getFormatTextMessage("เอ้ย ถามอะไรก็ตอบได้");
$encodeJson = json_encode($messages);
$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
$LINEDatas['token'] = "WxsvRSt1nS5KyO1JGkPeKPd1GifWm5N+AuloKU4aIdb0OVJ/KYVGqift7JngSEBIfeuA9XqfN5px9AkjjSzbQuoJpKYP9ZL28UTGWfjlNbgDkBgMx/4F4dMKQK76uf7spBBX1vaFS9UobtaHPgeeiQdB04t89/1O/w1cDnyilFU=";
$results = sentMessage($encodeJson, $LINEDatas);
/*Return HTTP Request 200*/
http_response_code(200);
function getFormatTextMessage($text)
{
	$datas = [];
	$datas['type'] = 'text';
	$datas['text'] = $text;
	return $datas;
}
function sentMessage($encodeJson, $datas)
{
	$datasReturn = [];
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $datas['url'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $encodeJson,
		CURLOPT_HTTPHEADER => array(
			"authorization: Bearer " . $datas['token'],
			"cache-control: no-cache",
			"content-type: application/json; charset=UTF-8",
		),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
		$datasReturn['result'] = 'E';
		$datasReturn['message'] = $err;
	} else {
		if ($response == "{}") {
			$datasReturn['result'] = 'S';
			$datasReturn['message'] = 'Success';
		} else {
			$datasReturn['result'] = 'E';
			$datasReturn['message'] = $response;
		}
	}
	return $datasReturn;
}


function sendLineNotify($input)
{

	$inputimage = 'http://vpn.idms.pw:9977/polis/imagebyte?id=' . $input;

	date_default_timezone_set("Asia/Bangkok");
	//line Send
	$lineapi = "IlEhAsp3YTHdvz5LMNCiV30jLChieKokvsSppm4RrLn";  //โทเค่น line
	$chOne = curl_init();
	curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
	// SSL USE 
	curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
	//POST 
	curl_setopt($chOne, CURLOPT_POST, 1);
	// Message 
	curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=$mms&imageThumbnail=$inputimage&imageFullsize=$inputimage");
	// follow redirects 
	curl_setopt($chOne, CURLOPT_FOLLOWLOCATION, 1);
	//ADD header array 
	$headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $lineapi . '',);
	curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
	//RETURN 
	curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($chOne);
	//Check error 
	if (curl_error($chOne)) {
		echo 'error:' . curl_error($chOne);
	} else {
		$result_ = json_decode($result, true);
		echo "status : " . $result_['status'];
		echo "message : " . $result_['message'];
	}
	//Close connect 
	curl_close($chOne);
}
