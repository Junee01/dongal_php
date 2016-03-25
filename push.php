<?php 
	
	include_once('common.php');
	conn();
	
	if($_SERVER['REQUEST_METHOD'] == "GET"){	
		
		$deviceToken = "9cb40d592d930901f6faeeba7b233cc5537f6a22b60bbbac6b875e626d735c70";
		$message = "[장학]새로운 공지가 등록되었습니다.";
		$result = push_send($deviceToken, $message);
		
		echo $deviceToken." / ".$result;
	
	} else if($_SERVER['REQUEST_METHOD'] == "POST"){
		$uuid = $_POST[uuid];
		$deviceToken = $_POST[token];
		sql_query("update dongal_member set mb_token='$deviceToken' where mb_uuid='$uuid'");
		
	} else{
		$results = array("results","error");
	}
		
		
	
	

	$results = json_encode($results); 	
	echo $results;
	

	
	
	
function push_send($token, $msg) {
	$passphrase = '동꾸기';
	
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

	
	// Open a connection to the APNS server
	$fp = stream_socket_client(
		'ssl://gateway.push.apple.com:2195', $err,
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	
	if (!$fp)
		return "Failed to connect: $err $errstr" . PHP_EOL;
	
	// Create the payload body
	$body['aps'] = array(
		'alert' => $msg,
		'sound' => 'default'
		);
	
	// Encode the payload as JSON
	$payload = json_encode($body);
	
	// Build the binary notification
	$msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
	
	// Send it to the server
	$result = fwrite($fp, $msg, strlen($msg));
	
	fclose($fp);

	if (!$result) {
		return "fail";
	} else {
		return "success";
	}
}
	