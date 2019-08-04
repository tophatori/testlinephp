<?php

/////////////////// ดึงข่าว ////////////////
$ch = curl_init('http://rssfeeds.sanook.com/rss/feeds/sanook/news.index.xml');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$contents = curl_exec($ch);
curl_close($ch);
 
$xml = new SimpleXmlElement($contents);
//for($i=0; $i<count($xml->channel->item); $i++){
	for($i=0; $i<10; $i++){
$url = $xml->channel->item[$i]->link;
$title = $xml->channel->item[$i]->title;
$description = $xml->channel->item[$i]->description;
$news .= $title."\n".$enclosure ."\n".$url."\n\n";

}
/////////////////////////////////////
//token 
$token_line = "aZwx0YSUCbOhljuJpTJAng2FUn2VaEH72QKBr6JZZzU";

$message = $_REQUEST['message'];
$chOne = curl_init(); 
curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
// SSL USE 
curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
//POST 
curl_setopt( $chOne, CURLOPT_POST, 1); 
// Message 
curl_setopt( $chOne, CURLOPT_POSTFIELDS, $message); 
//ถ้าต้องการใส่รุป ให้ใส่ 2 parameter imageThumbnail และimageFullsize
curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$news"); 
// follow redirects 
curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1); 
//ADD header array 
$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$token_line.'', ); 
curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
//RETURN 
curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec( $chOne ); 
//Check error 
if(curl_error($chOne)) { echo 'error:' . curl_error($chOne); } 
else { $result_ = json_decode($result, true); 
echo "status : ".$result_['status']; echo "message : ". $result_['message']; } 
//Close connect 
curl_close( $chOne ); 


?>
