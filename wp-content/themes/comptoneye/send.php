<?php /* Template Name: send php */ 
get_header();
?>
<?php
/*  
Parameter Example */
	$data = array('title'=>'12345','body'=>'A Blog post is going on no idea');
	$target = array('e8S4WuLUvaI:APA91bFxCJ4f2NhY6UP44OwW4w7B7QAH-g-jlNFsESrOYOZtZYMZ6XQCYdJ9msVjXNAxyzhM1W3V8F3HgzFhdNFEih1gp4N8cbn_yzbFCMgxWGfN2KEGpaKHgVjzkiwcbLMTU4XjeJ1K','dMRHAuYIpDM:APA91bFeW3d3RtPD9-wh7I71E5f9v6-mYXRCUVmg1HNP0HdhxxQ9lS1KLGpYknzwuxqrDXq8ElX6AFyqw8ONea50Pl1NvfJSWiiTFDAUap4jpffac29pTf488w_jQZbEsyZzZ56xQuP_','clYR9qjzOPg:APA91bEZUDLgdHttI39guJcuIXLH-ij9go06xUwrSTYUduWQTUHqZRsuzLxsUL4qtIkQ7AushflS3km8Pm86BqiogV2CATx6STRMSKBG2oz2q1Gx6ebShJllXV3TmvuEEM1AlTjGNWst');
	// or
	// $target = array('token1','token2','...'); // up to 1000 in one request
// */
	sendMessage($data,$target);
function sendMessage($data,$target){
//FCM api URL
$url = 'https://fcm.googleapis.com/fcm/send';
//api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
$server_key = 'AAAAxOdsypE:APA91bHriZpLzpSHI_Okba4xw7ybqI74WubYqtcgOwue4dyOx3lXMTKUqVl4T-o8M3qTsr1oUlytD7PgBR0P-SC3XK6iQNH4rRcROVXQn5jp8iJcNIJ0Zl7tZquLZoaQ4-T_pmBDyL0e';
			
$fields = array();
$notification= array('notification' =>json_encode($data));
// print_r($notification);
$fields['data'] = array("notification" => array("title" => "Hello","body" => "world"));
// $fields['notification'] = $data;
if(is_array($target)){
	$fields['registration_ids'] = $target;
}else{
	$fields['to'] = $target;
}
//header with content_type api key
$headers = array(
	'Content-Type:application/json',
  'Authorization:key='.$server_key
);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($fields) );

$result = curl_exec($ch);
echo $result;
if ($result === FALSE) {
	die('FCM Send Error: ' . curl_error($ch));
}
curl_close($ch);
return $result;
}