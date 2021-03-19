<?php
	echo json_encode($_POST);
	$orderId = $_POST["orderId"];
	$orderAmount = $_POST["orderAmount"];
	$referenceId = $_POST["referenceId"];
	$txStatus = $_POST["txStatus"];
	$paymentMode = $_POST["paymentMode"];
	$txMsg = $_POST["txMsg"];
	$txTime = $_POST["txTime"];
	$success = "success";
	$signature = $_POST["signature"];
	$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
	$secretKey = "3aadc1079c10030dfbb96550a95a5f049ce1b20c";
	$hash_hmac = hash_hmac('sha256', $data, $secretKey, true) ;
	$computedSignature = base64_encode($hash_hmac);
	if ($signature == $computedSignature) {
		echo "<h1>Success</h1>";
		echo "<p>Order ID: ".$orderId." </p>";
		$mysqli = new mysqli('localhost', 'root', '', 'football');
		$stmt = $mysqli->prepare("UPDATE bethesda SET payment=? WHERE id=?");
		$stmt->bind_param('ss',$success,$orderId);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	} else {
		echo "<h1>Unsuccessful</h1>";
	}
?>